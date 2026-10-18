<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientEquipmentCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_tecnico_puede_crear_un_cliente_y_un_equipo(): void
    {
        $technician = User::factory()->tecnico()->create();

        $this->actingAs($technician)
            ->post(route('clients.store'), [
                'client_code' => 'C-0100',
                'name' => 'Cliente CRUD',
                'phone' => '7000-0100',
                'email' => 'crud@fixtrack.test',
                'active' => '1',
            ])
            ->assertRedirect(route('clients.index'));

        $client = Client::query()->where('client_code', 'C-0100')->firstOrFail();

        $this->actingAs($technician)
            ->post(route('equipments.store'), [
                'client_id' => $client->id,
                'equipment_type' => 'Laptop',
                'brand' => 'Dell',
                'model' => 'XPS',
                'serial_number' => 'CRUD-0100',
            ])
            ->assertRedirect(route('equipments.index'));

        $this->assertDatabaseHas('equipments', [
            'client_id' => $client->id,
            'serial_number' => 'CRUD-0100',
        ]);
    }

    public function test_los_listados_permiten_buscar_clientes_y_equipos(): void
    {
        $technician = User::factory()->tecnico()->create();
        $client = Client::create([
            'client_code' => 'C-FIND',
            'name' => 'Cliente Encontrable',
            'phone' => '7000-0200',
            'active' => true,
        ]);
        Equipment::create([
            'client_id' => $client->id,
            'equipment_type' => 'Consola',
            'brand' => 'Nintendo',
            'model' => 'Switch',
            'serial_number' => 'FIND-001',
        ]);

        $this->actingAs($technician)
            ->get(route('clients.index', ['search' => 'Encontrable']))
            ->assertOk()
            ->assertSee('C-FIND');

        $this->actingAs($technician)
            ->get(route('equipments.index', ['search' => 'FIND-001']))
            ->assertOk()
            ->assertSee('Nintendo Switch');
    }

    public function test_un_cliente_no_puede_gestionar_catalogos(): void
    {
        $client = User::factory()->cliente()->create();

        $this->actingAs($client)->get(route('clients.index'))->assertForbidden();
        $this->actingAs($client)->get(route('equipments.index'))->assertForbidden();
    }
}

<?php

namespace Tests\Feature;

use App\Enums\EstadoTicket;
use App\Enums\Rol;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceOrderCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_tecnico_puede_crear_y_consultar_una_orden(): void
    {
        $technician = User::factory()->tecnico()->create();
        [$client, $equipment] = $this->clientWithEquipment();

        $response = $this->actingAs($technician)->post(route('orders.store'), [
            'client_id' => $client->id,
            'equipment_id' => $equipment->id,
            'service_type' => 'repair',
            'priority' => 'high',
            'reported_problem' => 'No enciende.',
            'estimated_cost' => '50.00',
        ]);

        $order = ServiceOrder::query()->firstOrFail();

        $response->assertRedirect(route('orders.show', $order));
        $this->assertDatabaseHas('service_orders', [
            'id' => $order->id,
            'order_code' => 'A-0001',
            'priority' => 'high',
            'status' => EstadoTicket::PendienteDiagnostico->value,
        ]);
        $this->assertDatabaseHas('order_histories', [
            'service_order_id' => $order->id,
            'new_status' => EstadoTicket::PendienteDiagnostico->value,
        ]);

        $this->actingAs($technician)
            ->get(route('orders.index', ['search' => $order->order_code]))
            ->assertOk()
            ->assertSee($order->order_code);
    }

    public function test_no_se_puede_asociar_un_equipo_a_otro_cliente(): void
    {
        $technician = User::factory()->tecnico()->create();
        [$client, $equipment] = $this->clientWithEquipment();
        $otherClient = Client::create([
            'client_code' => 'C-OTHER',
            'name' => 'Otro cliente',
            'phone' => '7000-0002',
            'active' => true,
        ]);

        $this->actingAs($technician)
            ->from(route('orders.create'))
            ->post(route('orders.store'), [
                'client_id' => $otherClient->id,
                'equipment_id' => $equipment->id,
                'service_type' => 'repair',
                'priority' => 'medium',
                'reported_problem' => 'Equipo incorrecto.',
            ])
            ->assertRedirect(route('orders.create'))
            ->assertSessionHasErrors('equipment_id');

        $this->assertDatabaseCount('service_orders', 0);
        $this->assertSame(Rol::Tecnico, $technician->fresh()->role);
    }

    public function test_tecnico_puede_avanzar_el_estado_y_se_registra_el_historial(): void
    {
        $technician = User::factory()->tecnico()->create();
        [$client, $equipment] = $this->clientWithEquipment();
        $order = ServiceOrder::create([
            'order_code' => 'A-0099',
            'client_id' => $client->id,
            'equipment_id' => $equipment->id,
            'service_type' => 'repair',
            'reported_problem' => 'Falla de prueba.',
            'status' => EstadoTicket::PendienteDiagnostico,
            'priority' => 'medium',
            'authorization_status' => 'pending',
        ]);

        $this->actingAs($technician)
            ->patch(route('orders.status', $order), [
                'status' => EstadoTicket::EsperandoAutorizacion->value,
                'notes' => 'Diagnóstico listo para revisión.',
            ])
            ->assertRedirect(route('orders.show', $order));

        $this->assertDatabaseHas('service_orders', [
            'id' => $order->id,
            'status' => EstadoTicket::EsperandoAutorizacion->value,
        ]);
        $this->assertDatabaseHas('order_histories', [
            'service_order_id' => $order->id,
            'previous_status' => EstadoTicket::PendienteDiagnostico->value,
            'new_status' => EstadoTicket::EsperandoAutorizacion->value,
            'notes' => 'Diagnóstico listo para revisión.',
        ]);
    }

    public function test_tecnico_puede_guardar_diagnostico_y_reparacion(): void
    {
        $technician = User::factory()->tecnico()->create();
        [$client, $equipment] = $this->clientWithEquipment();
        $order = ServiceOrder::create([
            'order_code' => 'A-0100',
            'client_id' => $client->id,
            'equipment_id' => $equipment->id,
            'service_type' => 'repair',
            'reported_problem' => 'Falla de prueba.',
            'status' => EstadoTicket::PendienteDiagnostico,
            'priority' => 'medium',
            'authorization_status' => 'pending',
        ]);

        $this->actingAs($technician)
            ->post(route('orders.diagnosis', $order), [
                'diagnosis' => 'Falla en la fuente de poder.',
                'proposed_solution' => 'Reemplazar componente.',
            ])
            ->assertRedirect(route('orders.show', $order));

        $order->update([
            'status' => EstadoTicket::Autorizado,
            'authorization_status' => 'authorized',
        ]);

        $this->actingAs($technician)
            ->post(route('orders.repair', $order), [
                'performed_work' => 'Se reemplazó la fuente y se hicieron pruebas.',
                'parts_used' => 'Fuente compatible.',
                'started_at' => '2026-10-18 10:00',
            ])
            ->assertRedirect(route('orders.show', $order));

        $this->assertDatabaseHas('diagnoses', [
            'service_order_id' => $order->id,
            'diagnosis' => 'Falla en la fuente de poder.',
        ]);
        $this->assertDatabaseHas('repairs', [
            'service_order_id' => $order->id,
            'performed_work' => 'Se reemplazó la fuente y se hicieron pruebas.',
        ]);
        $this->assertDatabaseHas('service_orders', [
            'id' => $order->id,
            'status' => EstadoTicket::EnReparacion->value,
        ]);
    }

    public function test_un_cliente_no_puede_entrar_al_crud_de_ordenes(): void
    {
        $clientUser = User::factory()->cliente()->create();

        $this->actingAs($clientUser)
            ->get(route('orders.index'))
            ->assertForbidden();
    }

    public function test_un_invitado_es_redirigido_al_login(): void
    {
        $this->get(route('orders.index'))->assertRedirect(route('login'));
    }

    /** @return array{0: Client, 1: Equipment} */
    private function clientWithEquipment(): array
    {
        $client = Client::create([
            'client_code' => 'C-TEST-ORD',
            'name' => 'Cliente de órdenes',
            'phone' => '7000-0001',
            'active' => true,
        ]);

        $equipment = $client->equipments()->create([
            'equipment_type' => 'Laptop',
            'brand' => 'Lenovo',
            'model' => 'ThinkPad',
            'serial_number' => 'SER-ORD-001',
        ]);

        return [$client, $equipment];
    }
}

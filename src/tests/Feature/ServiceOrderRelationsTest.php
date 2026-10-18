<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ServiceOrder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceOrderRelationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifica las relaciones entre cliente, equipo
     * y orden de servicio.
     */
    public function test_client_equipment_and_service_order_are_related(): void
    {
        // Crear cliente
        $client = Client::create([
            'client_code' => 'C-TEST-003',
            'name' => 'Cliente Relaciones',
            'phone' => '7777-1234',
        ]);

        // Registrar equipo del cliente
        $equipment = $client->equipments()->create([
            'equipment_type' => 'Consola',
            'brand' => 'Sony',
            'model' => 'PlayStation 5',
        ]);

        // Crear orden de servicio
        $order = $client->serviceOrders()->create([
            'order_code' => 'A-TEST-001',
            'equipment_id' => $equipment->id,
            'service_type' => 'repair',
            'reported_problem' => 'La consola se apaga sola',
        ]);

        // Recuperar orden con sus relaciones
        $savedOrder = ServiceOrder::with([
            'client',
            'equipment',
        ])->findOrFail($order->id);

        // Verificar cliente relacionado
        $this->assertEquals(
            $client->id,
            $savedOrder->client->id
        );

        // Verificar equipo relacionado
        $this->assertEquals(
            $equipment->id,
            $savedOrder->equipment->id
        );

        // Verificar modelo del equipo
        $this->assertEquals(
            'PlayStation 5',
            $savedOrder->equipment->model
        );

        // Verificar persistencia en base de datos
        $this->assertDatabaseHas('service_orders', [
            'order_code' => 'A-TEST-001',
            'client_id' => $client->id,
            'equipment_id' => $equipment->id,
        ]);
    }

    /**
     * Verifica que no pueda eliminarse un cliente
     * que tenga equipos registrados.
     */
    public function test_cannot_delete_client_with_registered_equipment(): void
    {
        // Crear cliente
        $client = Client::create([
            'client_code' => 'C-TEST-004',
            'name' => 'Cliente Protegido',
            'phone' => '7777-5678',
        ]);

        // Registrar equipo asociado
        $equipment = $client->equipments()->create([
            'equipment_type' => 'Consola',
            'brand' => 'Sony',
            'model' => 'PlayStation 5',
        ]);

        // Intentar eliminar el cliente
        $deletionBlocked = false;

        try {
            $client->delete();
        } catch (QueryException $e) {
            $deletionBlocked = true;
        }

        // Verificar que la eliminación fue bloqueada
        $this->assertTrue(
            $deletionBlocked,
            'La base de datos permitió eliminar un cliente con equipos asociados.'
        );

        // Verificar que el cliente sigue existiendo
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'client_code' => 'C-TEST-004',
        ]);

        // Verificar que el equipo sigue asociado
        $this->assertDatabaseHas('equipments', [
            'id' => $equipment->id,
            'client_id' => $client->id,
        ]);
    }
}
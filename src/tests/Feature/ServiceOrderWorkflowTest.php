<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ServiceOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceOrderWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_order_can_have_diagnosis_repair_and_history(): void
    {
        // Crear cliente
        $client = Client::create([
            'client_code' => 'C-TEST-005',
            'name' => 'Cliente Flujo',
            'phone' => '7777-1234',
        ]);

        // Registrar equipo
        $equipment = $client->equipments()->create([
            'equipment_type' => 'Consola',
            'brand' => 'Sony',
            'model' => 'PlayStation 5',
        ]);

        // Crear orden
        $order = $client->serviceOrders()->create([
            'order_code' => 'A-TEST-002',
            'equipment_id' => $equipment->id,
            'service_type' => 'repair',
            'reported_problem' => 'La consola se sobrecalienta',
            'status' => 'pending_diagnosis',
            'authorization_status' => 'pending',
        ]);

        // Registrar diagnóstico
        $order->diagnosis()->create([
            'diagnosis' => 'Acumulación de polvo',
            'proposed_solution' => 'Limpieza interna',
            'observations' => 'Revisar ventilador',
        ]);

        // Actualizar autorización y estado
        $order->update([
            'status' => 'authorized',
            'authorization_status' => 'authorized',
            'estimated_cost' => 45.00,
        ]);

        // Registrar reparación
        $order->repair()->create([
            'performed_work' => 'Limpieza interna y pruebas',
            'parts_used' => 'Pasta térmica',
            'observations' => 'Equipo funcionando',
        ]);

        // Registrar historial
        $order->histories()->create([
            'previous_status' => 'pending_diagnosis',
            'new_status' => 'authorized',
            'notes' => 'Cliente autorizó la reparación',
        ]);

        // Consultar todos los datos relacionados
        $savedOrder = ServiceOrder::with([
            'client',
            'equipment',
            'diagnosis',
            'repair',
            'histories',
        ])->findOrFail($order->id);

        // Comprobar diagnóstico
        $this->assertEquals(
            'Acumulación de polvo',
            $savedOrder->diagnosis->diagnosis
        );

        // Comprobar reparación
        $this->assertEquals(
            'Limpieza interna y pruebas',
            $savedOrder->repair->performed_work
        );

        // Comprobar autorización
        $this->assertEquals(
            'authorized',
            $savedOrder->authorization_status
        );

        // Comprobar costo estimado
        $this->assertEquals(
            45.00,
            (float) $savedOrder->estimated_cost
        );

        // Comprobar historial
        $this->assertCount(
            1,
            $savedOrder->histories
        );

        $this->assertEquals(
            'authorized',
            $savedOrder->histories->first()->new_status
        );

        // Comprobar persistencia
        $this->assertDatabaseHas('diagnoses', [
            'service_order_id' => $order->id,
        ]);

        $this->assertDatabaseHas('repairs', [
            'service_order_id' => $order->id,
        ]);

        $this->assertDatabaseHas('order_histories', [
            'service_order_id' => $order->id,
            'new_status' => 'authorized',
        ]);
    }
}
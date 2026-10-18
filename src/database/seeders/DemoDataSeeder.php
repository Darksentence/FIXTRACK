<?php

namespace Database\Seeders;

use App\Enums\EstadoTicket;
use App\Enums\PrioridadTicket;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Datos mínimos para que la interfaz pueda demostrarse después de migrar.
     */
    public function run(): void
    {
        $client = Client::firstOrCreate(
            ['client_code' => 'C-0001'],
            [
                'name' => 'Cliente Demo',
                'phone' => '7000-0000',
                'email' => 'cliente.demo@fixtrack.test',
                'address' => 'San Salvador',
                'active' => true,
            ],
        );

        $equipment = Equipment::firstOrCreate(
            [
                'client_id' => $client->id,
                'serial_number' => 'FX-DEMO-001',
            ],
            [
                'equipment_type' => 'Consola',
                'brand' => 'Sony',
                'model' => 'PlayStation 5',
                'description' => 'Equipo de demostración del flujo de órdenes.',
            ],
        );

        $order = ServiceOrder::firstOrCreate(
            ['order_code' => 'A-0001'],
            [
                'client_id' => $client->id,
                'equipment_id' => $equipment->id,
                'service_type' => 'repair',
                'reported_problem' => 'La consola se apaga después de unos minutos.',
                'status' => EstadoTicket::PendienteDiagnostico,
                'priority' => PrioridadTicket::Alta,
                'authorization_status' => 'pending',
                'estimated_cost' => 35,
                'received_at' => now()->subDay(),
            ],
        );

        $technician = User::query()->where('email', 'tecnico@fixtrack.test')->first();

        if ($technician && $order->histories()->doesntExist()) {
            $order->histories()->create([
                'user_id' => $technician->id,
                'previous_status' => null,
                'new_status' => $order->status instanceof EstadoTicket
                    ? $order->status->value
                    : (string) $order->status,
                'notes' => 'Registro de demostración creado por el seeder.',
                'changed_at' => $order->created_at ?? now(),
            ]);
        }
    }
}

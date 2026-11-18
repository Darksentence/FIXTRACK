<?php

namespace Tests\Feature;

use App\Enums\EstadoTicket;
use App\Enums\PrioridadTicket;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderAttachment;
use App\Models\User;
use App\Services\TicketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClientPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_cliente_vinculado_ve_solo_sus_ordenes(): void
    {
        $user = User::factory()->cliente()->create();
        $client = $this->createClientFor($user, 'C-PORTAL-1');
        $order = $this->createOrder($client, 'A-PORTAL-1');

        $otherClient = Client::create([
            'client_code' => 'C-PORTAL-2',
            'name' => 'Otro Cliente',
            'phone' => '7000-0002',
            'active' => true,
        ]);
        $otherOrder = $this->createOrder($otherClient, 'A-PORTAL-2');

        $this->actingAs($user)
            ->get(route('client.orders.index'))
            ->assertOk()
            ->assertSee($order->order_code)
            ->assertDontSee($otherOrder->order_code);

        $this->actingAs($user)
            ->get(route('client.orders.show', $order))
            ->assertOk()
            ->assertSee($order->equipment->brand);

        $this->actingAs($user)
            ->get(route('client.orders.show', $otherOrder))
            ->assertNotFound();
    }

    public function test_un_cliente_sin_vinculo_recibe_un_estado_controlado(): void
    {
        $this->actingAs(User::factory()->cliente()->create())
            ->get(route('client.orders.index'))
            ->assertOk()
            ->assertSee('Cuenta pendiente de vinculación');
    }

    public function test_un_cliente_puede_cargar_y_consultar_un_archivo_de_su_orden(): void
    {
        Storage::fake('local');

        $user = User::factory()->cliente()->create();
        $client = $this->createClientFor($user, 'C-ARCHIVOS-1');
        $order = $this->createOrder($client, 'A-ARCHIVOS-1');
        $file = UploadedFile::fake()->create('comprobante.pdf', 100, 'application/pdf');

        $this->actingAs($user)
            ->post(route('client.orders.attachments.store', $order), ['file' => $file])
            ->assertRedirect();

        $attachment = ServiceOrderAttachment::query()->firstOrFail();

        $this->assertDatabaseHas('service_order_attachments', [
            'service_order_id' => $order->id,
            'uploaded_by_user_id' => $user->id,
            'original_name' => 'comprobante.pdf',
            'mime_type' => 'application/pdf',
        ]);
        Storage::disk('local')->assertExists($attachment->path);

        $this->actingAs($user)
            ->get(route('client.orders.attachments.view', [$order, $attachment]))
            ->assertOk();
    }

    public function test_un_cliente_no_puede_cargar_archivos_en_ordenes_ajenas(): void
    {
        Storage::fake('local');

        $user = User::factory()->cliente()->create();
        $owner = Client::create([
            'client_code' => 'C-ARCHIVOS-2',
            'name' => 'Propietario',
            'phone' => '7000-0002',
            'active' => true,
        ]);
        $order = $this->createOrder($owner, 'A-ARCHIVOS-2');

        $this->actingAs($user)
            ->post(route('client.orders.attachments.store', $order), [
                'file' => UploadedFile::fake()->create('ajeno.pdf', 100, 'application/pdf'),
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('service_order_attachments', 0);
    }

    public function test_el_personal_puede_eliminar_un_archivo(): void
    {
        Storage::fake('local');

        $technician = User::factory()->tecnico()->create();
        $client = Client::create([
            'client_code' => 'C-ARCHIVOS-3',
            'name' => 'Cliente Técnico',
            'phone' => '7000-0003',
            'active' => true,
        ]);
        $order = $this->createOrder($client, 'A-ARCHIVOS-3');
        $path = UploadedFile::fake()->create('diagnostico.pdf', 100, 'application/pdf')->store('service-orders/'.$order->id, 'local');
        $attachment = $order->attachments()->create([
            'uploaded_by_user_id' => $technician->id,
            'original_name' => 'diagnostico.pdf',
            'path' => $path,
            'disk' => 'local',
            'mime_type' => 'application/pdf',
            'size' => 100 * 1024,
        ]);

        $this->actingAs($technician)
            ->delete(route('orders.attachments.destroy', [$order, $attachment]))
            ->assertRedirect();

        $this->assertDatabaseMissing('service_order_attachments', ['id' => $attachment->id]);
        Storage::disk('local')->assertMissing($path);
    }

    public function test_eliminar_una_orden_limpia_sus_archivos(): void
    {
        Storage::fake('local');

        $technician = User::factory()->tecnico()->create();
        $client = Client::create([
            'client_code' => 'C-ARCHIVOS-4',
            'name' => 'Cliente Limpieza',
            'phone' => '7000-0004',
            'active' => true,
        ]);
        $order = $this->createOrder($client, 'A-ARCHIVOS-4');
        $path = UploadedFile::fake()->create('orden.pdf', 100, 'application/pdf')->store('service-orders/'.$order->id, 'local');
        $order->attachments()->create([
            'uploaded_by_user_id' => $technician->id,
            'original_name' => 'orden.pdf',
            'path' => $path,
            'disk' => 'local',
            'mime_type' => 'application/pdf',
            'size' => 100 * 1024,
        ]);

        app(TicketService::class)->delete($order);

        Storage::disk('local')->assertMissing($path);
        $this->assertDatabaseCount('service_order_attachments', 0);
    }

    private function createClientFor(User $user, string $code): Client
    {
        return Client::create([
            'user_id' => $user->id,
            'client_code' => $code,
            'name' => 'Cliente Portal',
            'phone' => '7000-0001',
            'email' => $user->email,
            'active' => true,
        ]);
    }

    private function createOrder(Client $client, string $code): ServiceOrder
    {
        $equipment = Equipment::create([
            'client_id' => $client->id,
            'equipment_type' => 'Laptop',
            'brand' => 'Lenovo',
            'model' => 'ThinkPad',
            'serial_number' => $code.'-SERIAL',
            'description' => 'Equipo para prueba del portal.',
        ]);

        return $client->serviceOrders()->create([
            'order_code' => $code,
            'equipment_id' => $equipment->id,
            'service_type' => 'repair',
            'reported_problem' => 'No enciende.',
            'status' => EstadoTicket::PendienteDiagnostico,
            'priority' => PrioridadTicket::Media,
            'authorization_status' => 'pending',
            'received_at' => now(),
        ]);
    }
}

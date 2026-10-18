<?php

namespace Tests\Feature;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_and_retrieve_client(): void
    {
        $client = Client::create([
            'client_code' => 'C-TEST-001',
            'name' => 'Cliente de Prueba',
            'phone' => '7777-1234',
            'email' => 'prueba@fixtrack.com',
            'address' => 'San Salvador',
            'active' => true,
        ]);

        $this->assertDatabaseHas('clients', [
            'client_code' => 'C-TEST-001',
            'name' => 'Cliente de Prueba',
        ]);

        $this->assertEquals(
            'Cliente de Prueba',
            Client::find($client->id)->name
        );
    }

    public function test_can_update_client(): void
    {
        $client = Client::create([
            'client_code' => 'C-TEST-002',
            'name' => 'Cliente Original',
            'phone' => '7777-1234',
        ]);

        $client->update([
            'name' => 'Cliente Actualizado',
        ]);

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => 'Cliente Actualizado',
        ]);
    }
}
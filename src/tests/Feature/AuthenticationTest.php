<?php

namespace Tests\Feature;

use App\Enums\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Rutas de prueba para verificar el middleware de roles.
        Route::middleware(['web', 'auth', 'role:admin'])
            ->get('/_prueba/solo-admin', fn () => 'ok');
        Route::middleware(['web', 'auth', 'role:admin,tecnico'])
            ->get('/_prueba/personal', fn () => 'ok');
    }

    public function test_la_pagina_de_login_se_muestra_a_invitados(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_un_usuario_puede_iniciar_sesion_con_credenciales_validas(): void
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('panel'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_el_login_falla_con_contrasena_incorrecta(): void
    {
        $user = User::factory()->create();

        $this->from('/login')
            ->post('/login', ['email' => $user->email, 'password' => 'incorrecta'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_el_login_exige_correo_y_contrasena(): void
    {
        $this->post('/login', [])->assertSessionHasErrors(['email', 'password']);
    }

    public function test_el_login_se_bloquea_tras_demasiados_intentos(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'incorrecta']);
        }

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertStatus(429);

        $this->assertGuest();
    }

    public function test_cerrar_sesion_termina_la_sesion(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_un_invitado_es_redirigido_al_login_al_pedir_el_panel(): void
    {
        $this->get('/panel')->assertRedirect(route('login'));
    }

    public function test_un_usuario_autenticado_no_ve_el_login(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/login')
            ->assertRedirect(route('panel'));
    }

    public function test_el_admin_accede_a_rutas_de_admin(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/_prueba/solo-admin')
            ->assertOk();
    }

    public function test_el_tecnico_no_accede_a_rutas_de_admin(): void
    {
        $this->actingAs(User::factory()->tecnico()->create())
            ->get('/_prueba/solo-admin')
            ->assertForbidden();
    }

    public function test_el_tecnico_accede_a_rutas_del_personal(): void
    {
        $this->actingAs(User::factory()->tecnico()->create())
            ->get('/_prueba/personal')
            ->assertOk();
    }

    public function test_el_cliente_no_accede_a_rutas_del_personal(): void
    {
        $this->actingAs(User::factory()->cliente()->create())
            ->get('/_prueba/personal')
            ->assertForbidden();
    }

    public function test_un_invitado_no_accede_a_rutas_por_rol(): void
    {
        $this->get('/_prueba/solo-admin')->assertRedirect(route('login'));
    }

    public function test_el_rol_no_se_puede_asignar_de_forma_masiva(): void
    {
        $user = User::create([
            'name' => 'Intruso',
            'email' => 'intruso@fixtrack.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $this->assertSame(Rol::Cliente, $user->fresh()->role);
    }
}

<?php

namespace Database\Seeders;

use App\Enums\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Usuarios de prueba, uno por rol. Se puede ejecutar varias veces.
     */
    public function run(): void
    {
        $usuarios = [
            ['Administrador', 'admin@fixtrack.test', Rol::Admin],
            ['Técnico', 'tecnico@fixtrack.test', Rol::Tecnico],
            ['Cliente', 'cliente@fixtrack.test', Rol::Cliente],
        ];

        foreach ($usuarios as [$nombre, $correo, $rol]) {
            // 'role' no es fillable, por eso se asigna con forceFill.
            // El cast 'hashed' del modelo cifra la contraseña al guardarla.
            User::firstOrNew(['email' => $correo])
                ->forceFill([
                    'name' => $nombre,
                    'password' => 'Fixtrack123*',
                    'role' => $rol,
                    'email_verified_at' => now(),
                ])
                ->save();
        }
    }
}

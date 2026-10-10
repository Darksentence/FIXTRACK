<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Rol;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 'role' no se incluye en Fillable a propósito: así nadie puede
// asignarse un rol mandando un campo extra en un formulario.
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Rol::class,
        ];
    }

    /**
     * Indica si el usuario tiene alguno de los roles dados.
     */
    public function tieneRol(Rol|string ...$roles): bool
    {
        if ($this->role === null) {
            return false;
        }

        foreach ($roles as $rol) {
            $valor = $rol instanceof Rol ? $rol->value : $rol;

            if ($this->role->value === $valor) {
                return true;
            }
        }

        return false;
    }
}

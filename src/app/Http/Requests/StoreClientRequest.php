<?php

namespace App\Http\Requests;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->tieneRol(Rol::Admin, Rol::Tecnico) ?? false;
    }

    public function rules(): array
    {
        return [
            'client_code' => ['required', 'string', 'max:30', 'unique:clients,client_code'],
            'user_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', Rol::Cliente->value)),
                Rule::unique('clients', 'user_id'),
            ],
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:500'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}

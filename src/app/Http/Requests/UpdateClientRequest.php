<?php

namespace App\Http\Requests;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->tieneRol(Rol::Admin, Rol::Tecnico) ?? false;
    }

    public function rules(): array
    {
        $client = $this->route('client');

        return [
            'client_code' => [
                'required',
                'string',
                'max:30',
                Rule::unique('clients', 'client_code')->ignore($client),
            ],
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^(?=(?:\D*\d){8})\+?[0-9][0-9\s().-]{6,28}[0-9]$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:500'],
            'active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_code.required' => 'Ingresa el código del cliente.',
            'client_code.unique' => 'Este código de cliente ya está registrado por otro cliente.',
            'name.required' => 'Ingresa el nombre del cliente.',
            'name.min' => 'El nombre debe contener al menos 2 caracteres.',
            'phone.required' => 'Ingresa el número telefónico.',
            'phone.regex' => 'El teléfono debe contener al menos 8 dígitos y solo puede incluir números, espacios, guiones, paréntesis o el signo +.',
            'email.email' => 'Ingresa un correo electrónico válido.',
        ];
    }
}

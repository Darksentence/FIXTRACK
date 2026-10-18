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
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:500'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}

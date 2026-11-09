<?php

namespace App\Http\Requests;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->tieneRol(Rol::Admin, Rol::Tecnico) ?? false;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'equipment_type' => ['required', 'string', 'min:2', 'max:100'],
            'brand' => ['required', 'string', 'min:2', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }
    public function messages(): array
    {
        return [
            'client_id.exists' => 'El cliente seleccionado no existe.',
            'equipment_type.min' => 'El tipo de equipo debe contener al menos 2 caracteres.',
            'brand.min' => 'La marca debe contener al menos 2 caracteres.',
        ];
    }
}

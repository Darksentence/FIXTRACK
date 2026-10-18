<?php

namespace App\Http\Requests;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class SaveRepairRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->tieneRol(Rol::Admin, Rol::Tecnico) ?? false;
    }

    public function rules(): array
    {
        return [
            'performed_work' => ['required', 'string', 'max:5000'],
            'parts_used' => ['nullable', 'string', 'max:5000'],
            'observations' => ['nullable', 'string', 'max:5000'],
            'started_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'final_cost' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }

    public function messages(): array
    {
        return [
            'final_cost.numeric' => 'El costo final debe ser numérico.',
            'final_cost.min' => 'El costo final no puede ser negativo.',
            'final_cost.max' => 'El costo final supera el monto permitido.',
        ];
    }
}

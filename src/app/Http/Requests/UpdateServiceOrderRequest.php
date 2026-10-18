<?php

namespace App\Http\Requests;

use App\Enums\PrioridadTicket;
use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->tieneRol(Rol::Admin, Rol::Tecnico) ?? false;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'equipment_id' => [
                'required',
                'integer',
                Rule::exists('equipments', 'id')
                    ->where(fn ($query) => $query->where('client_id', $this->input('client_id'))),
            ],
            'service_type' => ['required', Rule::in(['repair', 'maintenance'])],
            'reported_problem' => ['required', 'string', 'max:5000'],
            'priority' => ['required', Rule::in(array_column(PrioridadTicket::cases(), 'value'))],
            'estimated_cost' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'final_cost' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }

    public function messages(): array
    {
        return (new StoreServiceOrderRequest)->messages();
    }
}

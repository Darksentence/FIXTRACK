<?php

namespace App\Http\Requests;

use App\Enums\EstadoTicket;
use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeServiceOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->tieneRol(Rol::Admin, Rol::Tecnico) ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(array_column(EstadoTicket::cases(), 'value'))],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}

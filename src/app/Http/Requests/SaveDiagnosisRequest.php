<?php

namespace App\Http\Requests;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class SaveDiagnosisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->tieneRol(Rol::Admin, Rol::Tecnico) ?? false;
    }

    public function rules(): array
    {
        return [
            'diagnosis' => ['required', 'string', 'min:5', 'max:5000'],
            'proposed_solution' => ['nullable', 'string', 'max:5000'],
            'observations' => ['nullable', 'string', 'max:5000'],
        ];
    }
    public function messages(): array
    {
        return [
            'diagnosis.required' => 'Describe el diagnóstico realizado.',
            'diagnosis.min' => 'El diagnóstico debe contener al menos 5 caracteres.',
        ];
    }
}

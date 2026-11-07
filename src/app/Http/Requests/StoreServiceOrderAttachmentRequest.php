<?php

namespace App\Http\Requests;

use App\Enums\Rol;
use App\Models\ServiceOrder;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceOrderAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $order = $this->route('serviceOrder');

        if (! $user || ! $order instanceof ServiceOrder) {
            return false;
        }

        if ($user->tieneRol(Rol::Admin, Rol::Tecnico)) {
            return true;
        }

        return $user->tieneRol(Rol::Cliente)
            && (int) $user->client?->id === (int) $order->client_id;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Selecciona una imagen o documento.',
            'file.file' => 'El archivo enviado no es válido.',
            'file.mimes' => 'Solo se permiten imágenes JPG, PNG, WEBP o documentos PDF.',
            'file.max' => 'El archivo no puede superar los 10 MB.',
        ];
    }
}

<?php

namespace App\Enums;

enum PrioridadTicket: string
{
    case Baja = 'low';
    case Media = 'medium';
    case Alta = 'high';
    case Urgente = 'urgent';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Baja => 'Baja',
            self::Media => 'Media',
            self::Alta => 'Alta',
            self::Urgente => 'Urgente',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Baja => 'slate',
            self::Media => 'blue',
            self::Alta => 'amber',
            self::Urgente => 'red',
        };
    }
}

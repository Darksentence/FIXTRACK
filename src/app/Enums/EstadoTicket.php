<?php

namespace App\Enums;

enum EstadoTicket: string
{
    case PendienteDiagnostico = 'pending_diagnosis';
    case EsperandoAutorizacion = 'waiting_authorization';
    case Autorizado = 'authorized';
    case Rechazado = 'rejected';
    case EnReparacion = 'in_repair';
    case ListoParaRetiro = 'ready_for_pickup';
    case Entregado = 'delivered';

    public function etiqueta(): string
    {
        return match ($this) {
            self::PendienteDiagnostico => 'Pendiente de diagnóstico',
            self::EsperandoAutorizacion => 'Esperando autorización',
            self::Autorizado => 'Autorizado',
            self::Rechazado => 'Rechazado',
            self::EnReparacion => 'En reparación',
            self::ListoParaRetiro => 'Listo para retiro',
            self::Entregado => 'Entregado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PendienteDiagnostico => 'slate',
            self::EsperandoAutorizacion => 'amber',
            self::Autorizado, self::EnReparacion => 'blue',
            self::Rechazado => 'red',
            self::ListoParaRetiro => 'green',
            self::Entregado => 'purple',
        };
    }
}

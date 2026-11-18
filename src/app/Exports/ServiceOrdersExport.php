<?php

namespace App\Exports;

use App\Models\ServiceOrder;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ServiceOrdersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Enumerable
    {
        return ServiceOrder::query()
            ->orderByDesc('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Código',
            'Tipo de servicio',
            'Estado',
            'Prioridad',
            'Costo estimado (USD)',
            'Costo final (USD)',
            'Fecha de recepción',
            'Fecha de entrega',
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_code,
            $order->service_type,
            $order->status?->etiqueta() ?? 'Sin estado',
            $order->priority?->etiqueta() ?? 'Sin prioridad',
            (float) $order->estimated_cost,
            (float) $order->final_cost,
            $order->received_at?->format('d/m/Y') ?? '',
            $order->delivered_at?->format('d/m/Y') ?? '',
        ];
    }
}

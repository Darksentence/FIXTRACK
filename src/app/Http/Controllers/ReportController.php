<?php

namespace App\Http\Controllers;

use App\Enums\EstadoTicket;
use App\Enums\PrioridadTicket;
use App\Models\ServiceOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Exports\ServiceOrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function summary(): JsonResponse
    {
        // Indicadores generales.
        $totalOrders = ServiceOrder::count();

        $deliveredOrders = ServiceOrder::where(
            'status',
            EstadoTicket::Entregado->value
        )->count();

        $inRepairOrders = ServiceOrder::where(
            'status',
            EstadoTicket::EnReparacion->value
        )->count();

        $totalFinalCost = ServiceOrder::where(
            'status',
            EstadoTicket::Entregado->value
        )->sum('final_cost');

        // Distribución de órdenes por estado.
        $statusCounts = ServiceOrder::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $ordersByStatus = [];

        foreach (EstadoTicket::cases() as $status) {
            $ordersByStatus[] = [
                'label' => $status->etiqueta(),
                'value' => $status->value,
                'total' => (int) ($statusCounts[$status->value] ?? 0),
            ];
        }

        // Distribución de órdenes por prioridad.
        $priorityCounts = ServiceOrder::query()
            ->select('priority', DB::raw('COUNT(*) as total'))
            ->groupBy('priority')
            ->pluck('total', 'priority');

        $ordersByPriority = [];

        foreach (PrioridadTicket::cases() as $priority) {
            $ordersByPriority[] = [
                'label' => $priority->etiqueta(),
                'value' => $priority->value,
                'total' => (int) ($priorityCounts[$priority->value] ?? 0),
            ];
        }

        // Servicios registrados por tipo.
        $ordersByService = ServiceOrder::query()
            ->select('service_type', DB::raw('COUNT(*) as total'))
            ->groupBy('service_type')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'indicators' => [
                'total_orders' => $totalOrders,
                'delivered_orders' => $deliveredOrders,
                'in_repair_orders' => $inRepairOrders,
                'delivered_final_cost' => round((float) $totalFinalCost, 2),
            ],
            'orders_by_status' => $ordersByStatus,
            'orders_by_priority' => $ordersByPriority,
            'orders_by_service' => $ordersByService,
        ]);
    }

    public function exportPdf()
    {
        // Consultar las órdenes registradas.
        $orders = ServiceOrder::query()
            ->orderByDesc('created_at')
            ->get();

        // Calcular indicadores para el documento.
        $totalOrders = $orders->count();

        $deliveredOrders = $orders->filter(
            fn ($order) => $order->status === EstadoTicket::Entregado
        )->count();

        $inRepairOrders = $orders->filter(
            fn ($order) => $order->status === EstadoTicket::EnReparacion
        )->count();

        $totalFinalCost = $orders->filter(
            fn ($order) => $order->status === EstadoTicket::Entregado
        )->sum('final_cost');

        // Generar PDF a partir de una vista Blade.
        $pdf = Pdf::loadView('reports.pdf', [
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'deliveredOrders' => $deliveredOrders,
            'inRepairOrders' => $inRepairOrders,
            'totalFinalCost' => $totalFinalCost,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download(
            'FIXTRACK_Reporte_' . now()->format('Y-m-d_H-i-s') . '.pdf'
        );
    }

    public function exportExcel()
    {
        return Excel::download(
            new ServiceOrdersExport(),
            'FIXTRACK_Ordenes_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }

}

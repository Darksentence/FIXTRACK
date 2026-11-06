<?php
namespace App\Http\Controllers;

use App\Enums\EstadoTicket;
use App\Enums\PrioridadTicket;
use App\Models\ServiceOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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
}


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de servicios - FIXTRACK</title>
    <style>
        @page {
            margin: 28px 32px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #252d2d;
            font-size: 10px;
        }

        .header {
            border-bottom: 3px solid #b84d2d;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            color: #b84d2d;
        }

        .subtitle {
            font-size: 13px;
            margin-top: 4px;
        }

        .date {
            color: #69716c;
            font-size: 9px;
            margin-top: 8px;
        }

        .section-title {
            font-size: 13px;
            margin: 20px 0 10px;
            color: #34413d;
        }

        .summary {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin-bottom: 16px;
        }

        .summary td {
            width: 25%;
            padding: 12px;
            background-color: #f2f0e9;
            border: 1px solid #d9d6cc;
        }

        .summary-label {
            font-size: 9px;
            color: #69716c;
            margin-bottom: 7px;
        }

        .summary-value {
            font-size: 17px;
            font-weight: bold;
        }

        .orders {
            width: 100%;
            border-collapse: collapse;
        }

        .orders th {
            background-color: #34413d;
            color: #ffffff;
            padding: 9px 7px;
            text-align: left;
            font-size: 9px;
        }

        .orders td {
            padding: 8px 7px;
            border-bottom: 1px solid #d9d6cc;
            font-size: 9px;
        }

        .orders tr:nth-child(even) {
            background-color: #f7f6f2;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #d9d6cc;
            color: #69716c;
            font-size: 8px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="brand">FIXTRACK</div>
        <div class="subtitle">Reporte general de servicios técnicos</div>
        <div class="date">
            Generado el {{ $generatedAt->format('d/m/Y H:i') }}
        </div>
    </div>

    <h2 class="section-title">Resumen general</h2>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-label">Órdenes registradas</div>
                <div class="summary-value">{{ $totalOrders }}</div>
            </td>
            <td>
                <div class="summary-label">En reparación</div>
                <div class="summary-value">{{ $inRepairOrders }}</div>
            </td>
            <td>
                <div class="summary-label">Órdenes entregadas</div>
                <div class="summary-value">{{ $deliveredOrders }}</div>
            </td>
            <td>
                <div class="summary-label">Valor de órdenes entregadas</div>
                <div class="summary-value">
                    ${{ number_format((float) $totalFinalCost, 2) }}
                </div>
            </td>
        </tr>
    </table>

    <h2 class="section-title">Detalle de órdenes de servicio</h2>

    <table class="orders">
        <thead>
            <tr>
                <th>Código</th>
                <th>Servicio</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Costo estimado</th>
                <th>Costo final</th>
                <th>Fecha de recepción</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->order_code }}</td>
                    <td>{{ ucfirst($order->service_type ?? 'N/D') }}</td>
                    <td>
                        {{ $order->status?->etiqueta() ?? 'Sin estado' }}
                    </td>
                    <td>
                        {{ $order->priority?->etiqueta() ?? 'Sin prioridad' }}
                    </td>
                    <td>
                        ${{ number_format((float) $order->estimated_cost, 2) }}
                    </td>
                    <td>
                        ${{ number_format((float) $order->final_cost, 2) }}
                    </td>
                    <td>
                        {{ $order->received_at?->format('d/m/Y') ?? 'N/D' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">
                        No hay órdenes registradas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        FIXTRACK - Sistema de gestión y seguimiento de servicios técnicos.
        Documento generado automáticamente.
    </div>

</body>
</html>

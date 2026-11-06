
@extends('layouts.app')

@section('title', 'Reportes · FIXTRACK')

@section('content')
<div class="page-heading">
    <div>
        <p class="eyebrow">Taller / Estadísticas</p>
        <h1>Reportes y estadísticas</h1>
        <p class="muted">
            Indicadores de rendimiento y seguimiento de servicios técnicos.
        </p>
    </div>




<div class="report-actions">
    <a href="{{ route('reports.pdf') }}"
       class="button button-primary">
        Descargar PDF
    </a>

    <a href="{{ route('reports.excel') }}"
       class="button button-primary">
        Descargar Excel
    </a>

    <a href="{{ route('panel') }}"
       class="button button-secondary">
        Volver al panel
    </a>

</div>

</div>

<style>
    .report-kpis {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .report-kpi {
        padding: 1.2rem;
    }

    .report-kpi p {
        margin: 0 0 .7rem;
        color: var(--muted);
        font-size: .85rem;
    }

    .report-kpi strong {
        font-size: 2rem;
        color: var(--ink);
    }

    .report-charts {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .report-chart {
        padding: 1.2rem;
        min-width: 0;
    }

    .report-chart h2 {
        margin-bottom: 1rem;
    }

    .report-chart canvas {
        width: 100% !important;
        max-height: 300px;
    }

    .report-message {
        margin-bottom: 1rem;
    }

    @media (max-width: 850px) {
        .report-kpis {
            grid-template-columns: repeat(2, 1fr);
        }

        .report-charts {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .report-kpis {
            grid-template-columns: 1fr;
        }
    }

.report-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    align-items: center;
}

</style>

<div id="report-message" class="note report-message" role="status">
    Cargando estadísticas...
</div>

<div class="report-kpis">
    <div class="card report-kpi">
        <p>Órdenes registradas</p>
        <strong id="total-orders">—</strong>
    </div>

    <div class="card report-kpi">
        <p>En reparación</p>
        <strong id="in-repair">—</strong>
    </div>

    <div class="card report-kpi">
        <p>Órdenes entregadas</p>
        <strong id="delivered">—</strong>
    </div>

    <div class="card report-kpi">
        <p>Total de órdenes entregadas</p>
        <strong id="total-cost">—</strong>
    </div>
</div>

<div class="report-charts">
    <section class="card report-chart">
        <h2>Órdenes por estado</h2>
        <canvas id="status-chart"
                aria-label="Órdenes por estado"
                role="img"></canvas>
    </section>

    <section class="card report-chart">
        <h2>Órdenes por prioridad</h2>
        <canvas id="priority-chart"
                aria-label="Órdenes por prioridad"
                role="img"></canvas>
    </section>

    <section class="card report-chart">
        <h2>Servicios registrados</h2>
        <canvas id="service-chart"
                aria-label="Servicios registrados"
                role="img"></canvas>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const message = document.getElementById('report-message');

    try {
        const response = await fetch(@json(route('reports.summary')), {
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('No se pudieron consultar las estadísticas.');
        }

        const data = await response.json();
        const indicators = data.indicators;

        document.getElementById('total-orders').textContent =
            indicators.total_orders;

        document.getElementById('in-repair').textContent =
            indicators.in_repair_orders;

        document.getElementById('delivered').textContent =
            indicators.delivered_orders;

        document.getElementById('total-cost').textContent =
            new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(indicators.delivered_final_cost);

        if (typeof Chart === 'undefined') {
            throw new Error('No se pudo cargar la biblioteca de gráficos.');
        }

        const colors = [
            '#b84d2d',
            '#d39a4a',
            '#34413d',
            '#6c8b7b',
            '#7b7191',
            '#9e665c',
            '#b3b0a3'
        ];

        function createChart(id, type, items, labelKey, valueKey) {
            const labels = items.map(item => item[labelKey]);
            const values = items.map(item => Number(item[valueKey]));

            new Chart(document.getElementById(id), {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Órdenes',
                        data: values,
                        backgroundColor: colors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: type === 'bar' ? {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    } : {}
                }
            });
        }

        createChart(
            'status-chart',
            'doughnut',
            data.orders_by_status,
            'label',
            'total'
        );

        createChart(
            'priority-chart',
            'bar',
            data.orders_by_priority,
            'label',
            'total'
        );

        createChart(
            'service-chart',
            'bar',
            data.orders_by_service,
            'service_type',
            'total'
        );

        message.textContent =
            'Estadísticas actualizadas correctamente.';

    } catch (error) {
        console.error(error);
        message.textContent = error.message;
    }
});
</script>
@endsection

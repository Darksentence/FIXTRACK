@extends('layouts.app')

@section('title', 'Mis órdenes · FIXTRACK')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Portal del cliente</p>
            <h1>Mis órdenes</h1>
            <p class="muted">Consulta el avance de tus equipos y los archivos asociados a cada servicio.</p>
        </div>
        <span class="badge badge-slate">{{ $client->client_code }}</span>
    </div>

    <section class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Orden</th>
                        <th>Equipo</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Recibido</th>
                        <th>Archivos</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><span class="code">{{ $order->order_code }}</span></td>
                            <td>{{ $order->equipment->brand }} {{ $order->equipment->model }}<br><small class="muted">{{ $order->equipment->equipment_type }}</small></td>
                            <td>{{ $order->service_type === 'repair' ? 'Reparación' : 'Mantenimiento' }}</td>
                            <td><span class="badge badge-{{ $order->status->color() }}">{{ $order->status->etiqueta() }}</span></td>
                            <td>{{ $order->received_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td>{{ $order->attachments->count() }}</td>
                            <td><a class="button button-secondary button-small" href="{{ route('client.orders.show', $order) }}">Consultar</a></td>
                        </tr>
                    @empty
                        <tr><td class="empty" colspan="7">Todavía no tienes órdenes registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="pagination">
                <span>{{ $orders->firstItem() }}–{{ $orders->lastItem() }} de {{ $orders->total() }}</span>
                <div class="pagination-links">
                    @if($orders->previousPageUrl())
                        <a class="button button-secondary button-small" href="{{ $orders->previousPageUrl() }}">Anterior</a>
                    @endif
                    @if($orders->nextPageUrl())
                        <a class="button button-secondary button-small" href="{{ $orders->nextPageUrl() }}">Siguiente</a>
                    @endif
                </div>
            </div>
        @endif
    </section>
@endsection

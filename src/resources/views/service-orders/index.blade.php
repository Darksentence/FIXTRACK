
@extends('layouts.app')

@section('title', 'Órdenes de servicio · FIXTRACK')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Operación del taller</p>
            <h1>Órdenes de servicio</h1>
            <p class="muted">Consulta, filtra y administra el flujo de los tickets registrados.</p>
        </div>
        <a class="button button-primary" href="{{ route('orders.create') }}">+ Nueva orden</a>
    </div>

    <section class="card">
        <form class="filters" method="GET" action="{{ route('orders.index') }}">
            <div class="field">
                <label for="search">Buscar</label>
                <input
                    id="search"
                    type="search"
                    name="search"
                    value="{{ $filters['search'] }}"
                    placeholder="Código, cliente, marca o serie"
                >
            </div>

            <div class="field">
                <label for="status">Estado</label>
                <select id="status" name="status">
                    <option value="">Todos los estados</option>
                    @foreach($statuses as $status)
                        <option
                            value="{{ $status->value }}"
                            @selected($filters['status'] === $status->value)
                        >
                            {{ $status->etiqueta() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="priority">Prioridad</label>
                <select id="priority" name="priority">
                    <option value="">Todas las prioridades</option>
                    @foreach($priorities as $priority)
                        <option
                            value="{{ $priority->value }}"
                            @selected($filters['priority'] === $priority->value)
                        >
                            {{ $priority->etiqueta() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="button-primary" type="submit">Filtrar</button>
            <a class="button button-secondary" href="{{ route('orders.index') }}">Limpiar</a>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Cliente</th>
                        <th>Equipo</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($orders as $order)
                        @php
                            $status = $order->status;
                            $priority = $order->priority;

                            /*
                             * FASE 3 - PARTE 1:
                             * Las órdenes entregadas quedan en modo de consulta.
                             * La protección de rutas y operaciones del servidor
                             * debe implementarse también en la Parte 3.
                             */
                            $isDelivered = $status === \App\Enums\EstadoTicket::Entregado;
                        @endphp

                        <tr>
                            <td>
                                <a class="code" href="{{ route('orders.show', $order) }}">
                                    {{ $order->order_code }}
                                </a>
                            </td>

                            <td>
                                {{ $order->client->name }}
                                <br>
                                <small class="muted">{{ $order->client->client_code }}</small>
                            </td>

                            <td>
                                {{ $order->equipment->brand }} {{ $order->equipment->model }}
                            </td>

                            <td>
                                {{ $order->service_type === 'repair' ? 'Reparación' : 'Mantenimiento' }}
                            </td>

                            <td>
                                <span class="badge badge-{{ $status->color() }}">
                                    {{ $status->etiqueta() }}
                                </span>
                            </td>

                            <td>
                                <span class="badge badge-{{ $priority->color() }}">
                                    {{ $priority->etiqueta() }}
                                </span>
                            </td>

                            <td>
                                <div class="actions">
                                    {{-- Consultar siempre está permitido. --}}
                                    <a
                                        class="button button-secondary button-small"
                                        href="{{ route('orders.show', $order) }}"
                                    >
                                        Ver
                                    </a>

                                    {{-- FASE 3: ocultar edición y eliminación al entregar. --}}
                                    @unless($isDelivered)
                                        <a
                                            class="button button-secondary button-small"
                                            href="{{ route('orders.edit', $order) }}"
                                        >
                                            Editar
                                        </a>

                                        <form
                                            class="inline"
                                            method="POST"
                                            action="{{ route('orders.destroy', $order) }}"
                                            onsubmit="return confirm('¿Eliminar esta orden y su historial?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button class="button-danger button-small" type="submit">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty" colspan="7">
                                No hay órdenes que coincidan con los filtros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="pagination">
                <span>
                    Mostrando {{ $orders->firstItem() }}–{{ $orders->lastItem() }}
                    de {{ $orders->total() }}
                </span>

                <div class="pagination-links">
                    @if($orders->onFirstPage())
                        <span
                            class="button button-secondary button-small"
                            aria-disabled="true"
                        >
                            Anterior
                        </span>
                    @else
                        <a
                            class="button button-secondary button-small"
                            href="{{ $orders->previousPageUrl() }}"
                        >
                            Anterior
                        </a>
                    @endif

                    @if($orders->hasMorePages())
                        <a
                            class="button button-secondary button-small"
                            href="{{ $orders->nextPageUrl() }}"
                        >
                            Siguiente
                        </a>
                    @else
                        <span
                            class="button button-secondary button-small"
                            aria-disabled="true"
                        >
                            Siguiente
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </section>
@endsection

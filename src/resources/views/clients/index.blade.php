@extends('layouts.app')

@section('title', 'Clientes · FIXTRACK')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Catálogo de personas</p>
            <h1>Clientes</h1>
            <p class="muted">Mantén la información de contacto que se utilizará en cada orden.</p>
        </div>
        <a class="button button-primary" href="{{ route('clients.create') }}">+ Nuevo cliente</a>
    </div>

    <section class="card">
        <form class="filters" style="grid-template-columns: minmax(210px, 1fr) auto auto;" method="GET" action="{{ route('clients.index') }}">
            <div class="field">
                <label for="search">Buscar</label>
                <input id="search" type="search" name="search" value="{{ $search }}" placeholder="Código, nombre o correo">
            </div>
            <button class="button-primary" type="submit">Buscar</button>
            <a class="button button-secondary" href="{{ route('clients.index') }}">Limpiar</a>
        </form>

        <div class="table-wrap">
            <table style="min-width: 700px;">
                <thead><tr><th>Código</th><th>Nombre</th><th>Contacto</th><th>Registros</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td class="code">{{ $client->client_code }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->phone }}<br><small class="muted">{{ $client->email ?: 'Sin correo' }}</small></td>
                            <td>{{ $client->equipments_count }} equipo(s)<br><small class="muted">{{ $client->service_orders_count }} orden(es)</small></td>
                            <td><span class="badge badge-{{ $client->active ? 'green' : 'slate' }}">{{ $client->active ? 'Activo' : 'Inactivo' }}</span></td>
                            <td>
                                <div class="actions">
                                    <a class="button button-secondary button-small" href="{{ route('clients.edit', $client) }}">Editar</a>
                                    <form class="inline" method="POST" action="{{ route('clients.destroy', $client) }}" onsubmit="return confirm('¿Eliminar este cliente? Solo se podrá si no tiene registros asociados.');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="button-danger button-small" type="submit">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="empty" colspan="6">No hay clientes registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clients->hasPages())
            <div class="pagination"><span>{{ $clients->firstItem() }}–{{ $clients->lastItem() }} de {{ $clients->total() }}</span><div class="pagination-links">
                @if($clients->previousPageUrl())<a class="button button-secondary button-small" href="{{ $clients->previousPageUrl() }}">Anterior</a>@endif
                @if($clients->nextPageUrl())<a class="button button-secondary button-small" href="{{ $clients->nextPageUrl() }}">Siguiente</a>@endif
            </div></div>
        @endif
    </section>
@endsection

@extends('layouts.app')

@section('title', 'Equipos · FIXTRACK')

@section('content')
    <div class="page-heading">
        <div><p class="eyebrow">Inventario de equipos</p><h1>Equipos</h1><p class="muted">Consulta los equipos registrados y su cliente propietario.</p></div>
        <a class="button button-primary" href="{{ route('equipments.create') }}">+ Nuevo equipo</a>
    </div>

    <section class="card">
        <form class="filters" style="grid-template-columns: minmax(210px, 2fr) minmax(180px, 1fr) auto auto;" method="GET" action="{{ route('equipments.index') }}">
            <div class="field"><label for="search">Buscar</label><input id="search" type="search" name="search" value="{{ $search }}" placeholder="Marca, modelo, serie o cliente"></div>
            <div class="field"><label for="client_id">Cliente</label><select id="client_id" name="client_id"><option value="">Todos los clientes</option>@foreach($clients as $client)<option value="{{ $client->id }}" @selected((string) $clientId === (string) $client->id)>{{ $client->client_code }} · {{ $client->name }}</option>@endforeach</select></div>
            <button class="button-primary" type="submit">Filtrar</button><a class="button button-secondary" href="{{ route('equipments.index') }}">Limpiar</a>
        </form>
        <div class="table-wrap"><table style="min-width: 720px;"><thead><tr><th>Equipo</th><th>Cliente</th><th>Serie</th><th>Descripción</th><th>Acciones</th></tr></thead><tbody>
            @forelse($equipments as $equipment)
                <tr><td><strong>{{ $equipment->brand }} {{ $equipment->model }}</strong><br><small class="muted">{{ $equipment->equipment_type }}</small></td><td>{{ $equipment->client->client_code }} · {{ $equipment->client->name }}</td><td>{{ $equipment->serial_number ?: 'Sin serie' }}</td><td>{{ $equipment->description ?: '—' }}</td><td><div class="actions"><a class="button button-secondary button-small" href="{{ route('equipments.edit', $equipment) }}">Editar</a><form class="inline" method="POST" action="{{ route('equipments.destroy', $equipment) }}" onsubmit="return confirm('¿Eliminar este equipo? Solo se podrá si no tiene órdenes asociadas.');">@csrf @method('DELETE')<button class="button-danger button-small" type="submit">Eliminar</button></form></div></td></tr>
            @empty
                <tr><td class="empty" colspan="5">No hay equipos registrados.</td></tr>
            @endforelse
        </tbody></table></div>
        @if($equipments->hasPages())<div class="pagination"><span>{{ $equipments->firstItem() }}–{{ $equipments->lastItem() }} de {{ $equipments->total() }}</span><div class="pagination-links">@if($equipments->previousPageUrl())<a class="button button-secondary button-small" href="{{ $equipments->previousPageUrl() }}">Anterior</a>@endif @if($equipments->nextPageUrl())<a class="button button-secondary button-small" href="{{ $equipments->nextPageUrl() }}">Siguiente</a>@endif</div></div>@endif
    </section>
@endsection

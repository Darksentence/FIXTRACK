@extends('layouts.app')

@section('title', 'Editar equipo · FIXTRACK')

@section('content')
    <div class="page-heading"><div><p class="eyebrow">Inventario de equipos</p><h1>Editar equipo</h1><p class="muted">Actualiza sus datos sin perder las órdenes asociadas.</p></div></div>
    <section class="card"><div class="card-body"><form method="POST" action="{{ route('equipments.update', $equipment) }}">@csrf @method('PUT') @include('equipments._form')</form></div></section>
@endsection

@extends('layouts.app')

@section('title', 'Nuevo equipo · FIXTRACK')

@section('content')
    <div class="page-heading"><div><p class="eyebrow">Inventario de equipos</p><h1>Nuevo equipo</h1><p class="muted">Asocia el equipo con un cliente para poder usarlo en una orden.</p></div></div>
    <section class="card"><div class="card-body"><form method="POST" action="{{ route('equipments.store') }}">@csrf @include('equipments._form')</form></div></section>
@endsection

@extends('layouts.app')

@section('title', 'Nuevo cliente · FIXTRACK')

@section('content')
    <div class="page-heading"><div><p class="eyebrow">Catálogo de personas</p><h1>Nuevo cliente</h1><p class="muted">Registra los datos básicos antes de asociar sus equipos.</p></div></div>
    <section class="card"><div class="card-body"><form method="POST" action="{{ route('clients.store') }}">@csrf @include('clients._form')</form></div></section>
@endsection

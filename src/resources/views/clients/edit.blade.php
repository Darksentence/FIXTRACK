@extends('layouts.app')

@section('title', 'Editar '.$client->client_code.' · FIXTRACK')

@section('content')
    <div class="page-heading"><div><p class="eyebrow">Catálogo de personas</p><h1>Editar {{ $client->client_code }}</h1><p class="muted">Actualiza los datos de contacto del cliente.</p></div></div>
    <section class="card"><div class="card-body"><form method="POST" action="{{ route('clients.update', $client) }}">@csrf @method('PUT') @include('clients._form')</form></div></section>
@endsection

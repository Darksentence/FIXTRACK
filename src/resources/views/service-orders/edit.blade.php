@extends('layouts.app')

@section('title', 'Editar '.$order->order_code.' · FIXTRACK')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Edición de ticket</p>
            <h1>Editar {{ $order->order_code }}</h1>
            <p class="muted">El estado se cambia desde la vista detallada para conservar el historial del flujo.</p>
        </div>
        <span class="badge badge-{{ $order->status->color() }}">{{ $order->status->etiqueta() }}</span>
    </div>

    <section class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('orders.update', $order) }}">
                @csrf
                @method('PUT')
                @include('service-orders._form')
            </form>
        </div>
    </section>
@endsection

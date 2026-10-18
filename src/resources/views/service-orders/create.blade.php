@extends('layouts.app')

@section('title', 'Nueva orden · FIXTRACK')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Registro operativo</p>
            <h1>Nueva orden de servicio</h1>
            <p class="muted">La orden recibirá un código automático y comenzará como pendiente de diagnóstico.</p>
        </div>
    </div>

    <section class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('orders.store') }}">
                @csrf
                @include('service-orders._form')
            </form>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('title', 'Panel · FIXTRACK')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Área de trabajo</p>
            <h1>Hola, {{ auth()->user()->name }}</h1>
            <p class="muted">Administra el seguimiento de los servicios del taller desde un solo lugar.</p>
        </div>
        <span class="badge badge-blue">{{ auth()->user()->role?->etiqueta() }}</span>
    </div>

    <div class="two-columns">
        <section class="card">
            <div class="card-header">
                <div>
                    <p class="eyebrow">Fase 2</p>
                    <h2>Órdenes de servicio</h2>
                </div>
                @if(auth()->user()->tieneRol(\App\Enums\Rol::Admin, \App\Enums\Rol::Tecnico))
                    <a class="button button-primary" href="{{ route('orders.index') }}">Ver órdenes</a>
                @endif
            </div>
            <div class="card-body">
                <p class="muted">Consulta tickets, filtra por estado o prioridad, registra nuevas órdenes y actualiza su flujo operativo.</p>
                @if(auth()->user()->tieneRol(\App\Enums\Rol::Admin, \App\Enums\Rol::Tecnico))
                    <a class="button button-secondary" href="{{ route('orders.create') }}">Registrar una orden</a>
                @else
                    <p class="note">El acceso de cliente está preparado para la siguiente iteración. Por ahora las órdenes son gestionadas por administración y técnicos.</p>
                @endif
            </div>
        </section>

        <section class="card">
            <div class="card-header"><h2>Acceso actual</h2></div>
            <div class="card-body">
                <dl class="detail-grid">
                    <div class="detail"><dt>Usuario</dt><dd>{{ auth()->user()->email }}</dd></div>
                    <div class="detail"><dt>Rol</dt><dd>{{ auth()->user()->role?->etiqueta() }}</dd></div>
                </dl>
                <p class="note" style="margin-top: 1rem;">Las acciones de creación, edición, eliminación y cambio de estado requieren rol de administrador o técnico.</p>
            </div>
        </section>
    </div>
@endsection

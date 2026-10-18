@extends('layouts.app')

@section('title', 'Panel · FIXTRACK')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Taller / Inicio</p>
            <h1>Buen día, {{ auth()->user()->name }}</h1>
            <p class="muted">Accesos rápidos para el trabajo de hoy.</p>
        </div>
        <span class="badge badge-slate">{{ auth()->user()->role?->etiqueta() }}</span>
    </div>

    <div class="two-columns dashboard-top">
        <section class="card work-card">
            <div class="card-header">
                <div>
                    <p class="eyebrow">Trabajo en curso</p>
                    <h2>Órdenes de servicio</h2>
                </div>
                @if(auth()->user()->tieneRol(\App\Enums\Rol::Admin, \App\Enums\Rol::Tecnico))
                    <a class="button button-primary" href="{{ route('orders.index') }}">Ver órdenes</a>
                @endif
            </div>
            <div class="card-body">
                <p class="muted">Busca un ticket, registra el ingreso de un equipo o actualiza el avance de una reparación.</p>
                @if(auth()->user()->tieneRol(\App\Enums\Rol::Admin, \App\Enums\Rol::Tecnico))
                    <a class="button button-secondary" href="{{ route('orders.create') }}">Registrar una orden</a>
                @else
                    <p class="note">El acceso para clientes aún no está disponible. Estamos trabajando para habilitarlo.</p>
                @endif
            </div>
        </section>

        <section class="card session-card">
            <div class="card-header"><h2>Acceso actual</h2></div>
            <div class="card-body">
                <dl class="detail-grid">
                    <div class="detail"><dt>Usuario</dt><dd>{{ auth()->user()->email }}</dd></div>
                    <div class="detail"><dt>Rol</dt><dd>{{ auth()->user()->role?->etiqueta() }}</dd></div>
                </dl>
                <p class="note" style="margin-top: 1rem;">Tu cuenta define qué registros puedes administrar.</p>
            </div>
        </section>
    </div>

    @if(auth()->user()->tieneRol(\App\Enums\Rol::Admin, \App\Enums\Rol::Tecnico))
        <div class="two-columns dashboard-catalogs" style="margin-top: 1rem;">
            <section class="card">
                <div class="card-header"><h2>Clientes</h2><a class="button button-secondary button-small" href="{{ route('clients.index') }}">Gestionar</a></div>
                <div class="card-body"><p class="muted">Registra y actualiza la información de las personas que dejan equipos en el taller.</p></div>
            </section>
            <section class="card">
                <div class="card-header"><h2>Equipos</h2><a class="button button-secondary button-small" href="{{ route('equipments.index') }}">Gestionar</a></div>
                <div class="card-body"><p class="muted">Asocia laptops, consolas y periféricos a sus clientes antes de crear una orden.</p></div>
            </section>
        </div>
    @endif
@endsection

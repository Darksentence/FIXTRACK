@extends('layouts.app')

@section('title', 'Cuenta pendiente · FIXTRACK')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Portal del cliente</p>
            <h1>Cuenta pendiente de vinculación</h1>
            <p class="muted">Tu usuario inició sesión correctamente, pero todavía no está asociado a un cliente.</p>
        </div>
    </div>

    <section class="card">
        <div class="card-body">
            <p class="note">Solicita al personal del taller que vincule tu cuenta con tu ficha de cliente para consultar tus órdenes.</p>
        </div>
    </section>
@endsection

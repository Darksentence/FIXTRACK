<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FIXTRACK')</title>
    <style>
        :root {
            --ink: #252d2d;
            --muted: #69716c;
            --line: #d9d6cc;
            --surface: #fffefa;
            --soft: #f2f0e9;
            --primary: #b84d2d;
            --primary-dark: #963d24;
            --danger: #a3342c;
            --shadow: 0 5px 16px rgba(37, 45, 45, .045);
        }

        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; font-family: "Segoe UI", Arial, sans-serif; color: var(--ink); background: #efede6; }
        body::before { content: ""; display: block; height: 4px; background: var(--primary); }
        a { color: var(--primary); text-decoration: none; }
        a:hover { color: var(--primary-dark); }
        button, input, select, textarea { font: inherit; }
        button, .button { border: 1px solid transparent; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: .4rem; padding: .65rem .9rem; font-weight: 650; transition: background-color .15s ease, border-color .15s ease; }
        .button-primary { color: #fff; background: var(--primary); }
        .button-primary:hover { color: #fff; background: var(--primary-dark); }
        .button-secondary { color: #343d3a; background: #e6e3d9; border-color: #d5d1c6; }
        .button-secondary:hover { color: var(--ink); background: #dcd8cc; }
        .button-danger { color: #fff; background: var(--danger); }
        .button-small { padding: .38rem .58rem; border-radius: 3px; font-size: .8rem; }
        .topbar { color: #f4f1e9; background: #263332; border-bottom: 1px solid #45504b; }
        .topbar-inner { max-width: 1180px; margin: 0 auto; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
        .brand { color: #fff; display: flex; align-items: center; gap: .65rem; font-size: 1.05rem; font-weight: 800; letter-spacing: .025em; }
        .brand-mark { display: grid; place-items: center; width: 2rem; height: 2rem; color: #fff5eb; border: 1px solid #d16c47; font-size: .8rem; font-weight: 800; letter-spacing: -.04em; }
        .brand-name { display: block; }
        .brand small { display: block; margin-top: .1rem; color: #c0c3b8; font-size: .68rem; font-weight: 450; letter-spacing: .015em; }
        .nav { display: flex; align-items: stretch; align-self: stretch; flex-wrap: wrap; gap: .15rem; }
        .nav a { display: flex; align-items: center; color: #d1d4ca; padding: .4rem .65rem; border-bottom: 2px solid transparent; font-size: .88rem; }
        .nav a:hover { color: #fff; border-bottom-color: #d16c47; }
        .nav a.is-active { color: #fff; border-bottom-color: #d16c47; }
        .user-area { display: flex; align-items: center; gap: .7rem; font-size: .84rem; color: #d1d4ca; }
        .user-area form { display: inline; }
        .logout { color: #f2c0aa; background: transparent; padding: .4rem .55rem; border: 1px solid #64706a; }
        .logout:hover { color: #fff; border-color: #d16c47; }
        .container { max-width: 1180px; margin: 0 auto; padding: 1.8rem 1.25rem 3rem; }
        .page-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: 1.15rem; padding-bottom: .95rem; border-bottom: 1px solid #d0ccc1; }
        h1 { margin: 0; font-size: clamp(1.6rem, 3vw, 2.05rem); font-weight: 700; letter-spacing: -.045em; }
        h2 { margin: 0; font-size: 1.15rem; font-weight: 700; letter-spacing: -.02em; }
        h3 { font-size: 1rem; font-weight: 700; }
        .eyebrow { margin: 0 0 .35rem; color: var(--primary); font-size: .72rem; font-weight: 700; letter-spacing: .045em; text-transform: none; }
        .muted { color: var(--muted); }
        .card { background: var(--surface); border: 1px solid var(--line); border-radius: 5px; box-shadow: var(--shadow); }
        .card-header { padding: .9rem 1.1rem; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
        .card-body { padding: 1.1rem; }
        .flash { margin-bottom: 1rem; padding: .75rem .9rem; border-radius: 3px; color: #31553b; background: #e6ece2; border-left: 3px solid #64805c; }
        .errors { margin-bottom: 1rem; padding: .75rem 1rem .75rem 2rem; color: #832c24; background: #f4e4df; border-left: 3px solid var(--danger); }
        .filters { padding: .85rem 1rem; display: grid; grid-template-columns: minmax(210px, 2fr) repeat(2, minmax(160px, 1fr)) auto auto; gap: .6rem; align-items: end; border-bottom: 1px solid var(--line); background: #f7f5ef; }
        .field { display: grid; gap: .35rem; }
        label { color: #404844; font-size: .82rem; font-weight: 650; }
        input, select, textarea { width: 100%; color: var(--ink); background: #fffefa; border: 1px solid #c9c6bc; border-radius: 3px; padding: .64rem .7rem; outline: none; }
        input:focus, select:focus, textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 2px rgba(184, 77, 45, .12); }
        textarea { min-height: 112px; resize: vertical; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 760px; }
        th, td { padding: .78rem .9rem; text-align: left; border-bottom: 1px solid #e7e4dc; vertical-align: middle; }
        th { color: #656c66; background: #f1efe8; font-size: .72rem; text-transform: none; letter-spacing: .015em; font-weight: 700; }
        tbody tr:hover { background: #f8f5ed; }
        .code { color: #a44227; font-family: ui-monospace, "Cascadia Code", Consolas, monospace; font-size: .9rem; font-weight: 700; }
        .badge { display: inline-flex; align-items: center; border-radius: 3px; padding: .24rem .48rem; font-size: .72rem; font-weight: 700; white-space: nowrap; }
        .badge-slate { color: #4c5752; background: #e7e6df; }
        .badge-blue { color: #35556b; background: #dce6e9; }
        .badge-amber { color: #81552b; background: #f0e5cf; }
        .badge-red { color: #87372e; background: #f0dfd9; }
        .badge-green { color: #3e5d42; background: #e0e9dc; }
        .badge-purple { color: #62516a; background: #e9e2ea; }
        .actions { display: flex; align-items: center; flex-wrap: wrap; gap: .4rem; }
        .inline { display: inline; }
        .empty { padding: 3rem 1rem; text-align: center; color: var(--muted); }
        .pagination { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .85rem 1rem; color: var(--muted); font-size: .84rem; }
        .pagination-links { display: flex; gap: .4rem; }
        .detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
        .detail { padding: .85rem; background: var(--soft); border-left: 2px solid #c8c1b2; }
        .detail dt { color: var(--muted); font-size: .72rem; font-weight: 700; text-transform: none; letter-spacing: .01em; }
        .detail dd { margin: .3rem 0 0; font-weight: 600; overflow-wrap: anywhere; }
        .two-columns { display: grid; grid-template-columns: minmax(0, 1.35fr) minmax(300px, .65fr); gap: .85rem; align-items: start; }
        .stack { display: grid; gap: 1rem; }
        .timeline { display: grid; gap: .8rem; }
        .timeline-item { padding-left: .85rem; border-left: 2px solid #cf8c6d; }
        .timeline-item strong { display: block; }
        .timeline-item small { color: var(--muted); }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
        .full { grid-column: 1 / -1; }
        .form-actions { display: flex; justify-content: flex-end; gap: .65rem; margin-top: 1.25rem; }
        .note { margin: 0; padding: .8rem .9rem; color: #505a54; background: #f2f0e9; border-left: 2px solid #9b9b75; font-size: .88rem; }
        .dashboard-top .work-card { color: #f3f0e8; background: #34413d; border-color: #34413d; }
        .dashboard-top .work-card .card-header { border-color: #53605a; }
        .dashboard-top .work-card .eyebrow { color: #e2a181; }
        .dashboard-top .work-card .muted { color: #d1d4ca; }
        .dashboard-top .work-card .button-secondary { color: #f4f1e9; background: transparent; border-color: #879088; }
        .dashboard-top .work-card .button-secondary:hover { background: #46554f; }
        .dashboard-top .session-card { background: #f7f5ef; }
        .dashboard-top .session-card .detail-grid { grid-template-columns: 1fr; }
        .dashboard-catalogs .card { box-shadow: none; }
        .dashboard-catalogs .card-header { border-bottom: 0; padding-bottom: .25rem; }
        .dashboard-catalogs .card-body { padding-top: .25rem; }
        .dashboard-catalogs h2 { font-size: 1.05rem; }
        @media (max-width: 850px) {
            .topbar-inner, .page-heading { align-items: flex-start; flex-direction: column; }
            .user-area { width: 100%; justify-content: space-between; }
            .filters, .detail-grid, .form-grid, .two-columns { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
        }
        @media (max-width: 560px) {
            .topbar-inner { gap: .8rem; }
            .nav { width: 100%; order: 3; }
            .nav a { padding: .45rem .5rem; font-size: .82rem; }
            .container { padding: 1.25rem .85rem 2rem; }
            .page-heading { align-items: flex-start; }
            .filters { padding: .75rem; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a class="brand" href="{{ route('panel') }}">
                <span class="brand-mark" aria-hidden="true">FT</span>
                <span class="brand-name">FIXTRACK<small>Servicio técnico</small></span>
            </a>
            <nav class="nav" aria-label="Navegación principal">
                <a class="{{ request()->routeIs('panel') ? 'is-active' : '' }}" href="{{ route('panel') }}">Panel</a>
                @if(auth()->user()->tieneRol(\App\Enums\Rol::Admin, \App\Enums\Rol::Tecnico))
                    <a class="{{ request()->routeIs('clients.*') ? 'is-active' : '' }}" href="{{ route('clients.index') }}">Clientes</a>
                    <a class="{{ request()->routeIs('equipments.*') ? 'is-active' : '' }}" href="{{ route('equipments.index') }}">Equipos</a>
                    <a class="{{ request()->routeIs('orders.*') && !request()->routeIs('orders.create') ? 'is-active' : '' }}" href="{{ route('orders.index') }}">Órdenes</a>
                    <a class="{{ request()->routeIs('orders.create') ? 'is-active' : '' }}" href="{{ route('orders.create') }}">Nueva orden</a>
                @endif
            </nav>
            <div class="user-area">
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout" type="submit">Salir</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container">
        @if(session('success'))
            <div class="flash" role="status">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="errors" role="alert">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="errors" role="alert">
                <strong>Revisa los datos:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>

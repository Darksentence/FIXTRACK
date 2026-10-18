<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FIXTRACK')</title>
    <style>
        :root {
            --ink: #172033;
            --muted: #64748b;
            --line: #e2e8f0;
            --surface: #ffffff;
            --soft: #f8fafc;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --danger: #dc2626;
            --shadow: 0 12px 35px rgba(15, 23, 42, .08);
        }

        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif; color: var(--ink); background: #f1f5f9; }
        a { color: var(--primary); text-decoration: none; }
        a:hover { color: var(--primary-dark); }
        button, input, select, textarea { font: inherit; }
        button, .button { border: 0; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: .4rem; padding: .68rem 1rem; font-weight: 700; transition: .15s ease; }
        button:hover, .button:hover { transform: translateY(-1px); }
        .button-primary { color: #fff; background: var(--primary); }
        .button-primary:hover { color: #fff; background: var(--primary-dark); }
        .button-secondary { color: #334155; background: #e2e8f0; }
        .button-danger { color: #fff; background: var(--danger); }
        .button-small { padding: .45rem .7rem; border-radius: 8px; font-size: .83rem; }
        .topbar { color: #e2e8f0; background: #0f172a; box-shadow: 0 2px 12px rgba(15, 23, 42, .2); }
        .topbar-inner { max-width: 1180px; margin: 0 auto; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
        .brand { color: #fff; font-size: 1.2rem; font-weight: 850; letter-spacing: .04em; }
        .brand small { display: block; color: #94a3b8; font-size: .68rem; font-weight: 500; letter-spacing: .02em; }
        .nav { display: flex; align-items: center; flex-wrap: wrap; gap: .35rem; }
        .nav a { color: #cbd5e1; padding: .5rem .7rem; border-radius: 8px; font-size: .9rem; }
        .nav a:hover { color: #fff; background: #1e293b; }
        .user-area { display: flex; align-items: center; gap: .7rem; font-size: .85rem; color: #cbd5e1; }
        .user-area form { display: inline; }
        .logout { color: #cbd5e1; background: transparent; padding: .45rem .65rem; }
        .logout:hover { color: #fff; background: #1e293b; }
        .container { max-width: 1180px; margin: 0 auto; padding: 2rem 1.25rem 3rem; }
        .page-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1.35rem; }
        h1 { margin: 0; font-size: clamp(1.55rem, 3vw, 2.1rem); letter-spacing: -.03em; }
        h2 { margin: 0; font-size: 1.25rem; }
        .eyebrow { margin: 0 0 .35rem; color: var(--primary); font-size: .76rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
        .muted { color: var(--muted); }
        .card { background: var(--surface); border: 1px solid var(--line); border-radius: 16px; box-shadow: var(--shadow); }
        .card-header { padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
        .card-body { padding: 1.25rem; }
        .flash { margin-bottom: 1rem; padding: .85rem 1rem; border-radius: 10px; color: #166534; background: #dcfce7; border: 1px solid #bbf7d0; }
        .errors { margin-bottom: 1rem; padding: .85rem 1rem .85rem 2rem; color: #991b1b; background: #fee2e2; border: 1px solid #fecaca; border-radius: 10px; }
        .filters { padding: 1rem; display: grid; grid-template-columns: minmax(210px, 2fr) repeat(2, minmax(160px, 1fr)) auto auto; gap: .7rem; align-items: end; }
        .field { display: grid; gap: .35rem; }
        label { color: #334155; font-size: .86rem; font-weight: 750; }
        input, select, textarea { width: 100%; color: var(--ink); background: #fff; border: 1px solid #cbd5e1; border-radius: 9px; padding: .68rem .75rem; outline: none; }
        input:focus, select:focus, textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, .12); }
        textarea { min-height: 120px; resize: vertical; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 760px; }
        th, td { padding: .85rem 1rem; text-align: left; border-bottom: 1px solid var(--line); vertical-align: middle; }
        th { color: #475569; background: #f8fafc; font-size: .75rem; text-transform: uppercase; letter-spacing: .07em; }
        tbody tr:hover { background: #f8fafc; }
        .code { font-weight: 800; color: #1e40af; }
        .badge { display: inline-flex; align-items: center; border-radius: 999px; padding: .27rem .6rem; font-size: .74rem; font-weight: 800; white-space: nowrap; }
        .badge-slate { color: #334155; background: #e2e8f0; }
        .badge-blue { color: #1e40af; background: #dbeafe; }
        .badge-amber { color: #92400e; background: #fef3c7; }
        .badge-red { color: #991b1b; background: #fee2e2; }
        .badge-green { color: #166534; background: #dcfce7; }
        .badge-purple { color: #6b21a8; background: #f3e8ff; }
        .actions { display: flex; align-items: center; flex-wrap: wrap; gap: .4rem; }
        .inline { display: inline; }
        .empty { padding: 3rem 1rem; text-align: center; color: var(--muted); }
        .pagination { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.25rem; color: var(--muted); font-size: .88rem; }
        .pagination-links { display: flex; gap: .4rem; }
        .detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
        .detail { padding: 1rem; background: var(--soft); border: 1px solid var(--line); border-radius: 12px; }
        .detail dt { color: var(--muted); font-size: .75rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; }
        .detail dd { margin: .35rem 0 0; font-weight: 650; }
        .two-columns { display: grid; grid-template-columns: minmax(0, 1.35fr) minmax(300px, .65fr); gap: 1rem; align-items: start; }
        .stack { display: grid; gap: 1rem; }
        .timeline { display: grid; gap: .8rem; }
        .timeline-item { padding-left: 1rem; border-left: 3px solid #bfdbfe; }
        .timeline-item strong { display: block; }
        .timeline-item small { color: var(--muted); }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
        .full { grid-column: 1 / -1; }
        .form-actions { display: flex; justify-content: flex-end; gap: .65rem; margin-top: 1.25rem; }
        .note { margin: 0; padding: .85rem 1rem; color: #475569; background: #f8fafc; border-left: 3px solid #93c5fd; border-radius: 0 9px 9px 0; font-size: .9rem; }
        @media (max-width: 850px) {
            .topbar-inner, .page-heading { align-items: flex-start; flex-direction: column; }
            .user-area { width: 100%; justify-content: space-between; }
            .filters, .detail-grid, .form-grid, .two-columns { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a class="brand" href="{{ route('panel') }}">FIXTRACK <small>Gestión de servicios técnicos</small></a>
            <nav class="nav" aria-label="Navegación principal">
                <a href="{{ route('panel') }}">Panel</a>
                @if(auth()->user()->tieneRol(\App\Enums\Rol::Admin, \App\Enums\Rol::Tecnico))
                    <a href="{{ route('orders.index') }}">Órdenes</a>
                    <a href="{{ route('orders.create') }}">Nueva orden</a>
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

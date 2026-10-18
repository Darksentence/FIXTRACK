<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FIXTRACK · Gestión técnica</title>
    <style>
        :root { --ink: #172033; --muted: #64748b; --primary: #2563eb; --dark: #0f172a; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; color: var(--ink); font-family: Inter, system-ui, -apple-system, "Segoe UI", sans-serif; background: radial-gradient(circle at 80% 10%, #dbeafe 0, transparent 35%), #f8fafc; }
        .wrap { width: min(1120px, calc(100% - 2.5rem)); margin: 0 auto; }
        header { display: flex; justify-content: space-between; align-items: center; padding: 1.4rem 0; }
        .brand { color: var(--dark); font-size: 1.35rem; font-weight: 900; letter-spacing: .06em; }
        .brand span { display: block; color: var(--muted); font-size: .7rem; font-weight: 600; letter-spacing: .02em; }
        .login { color: #fff; background: var(--primary); border-radius: 10px; padding: .7rem 1rem; font-weight: 800; text-decoration: none; }
        .login:hover { background: #1d4ed8; }
        main { display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(320px, .9fr); align-items: center; gap: 4rem; min-height: calc(100vh - 95px); padding: 2rem 0 5rem; }
        h1 { max-width: 670px; margin: 0 0 1rem; font-size: clamp(2.5rem, 6vw, 5.2rem); line-height: .98; letter-spacing: -.065em; }
        .accent { color: var(--primary); }
        .lead { max-width: 580px; color: var(--muted); font-size: 1.08rem; line-height: 1.7; }
        .buttons { display: flex; flex-wrap: wrap; gap: .7rem; margin-top: 1.7rem; }
        .button { display: inline-flex; align-items: center; border-radius: 10px; padding: .8rem 1.1rem; font-weight: 800; text-decoration: none; }
        .button-primary { color: #fff; background: var(--primary); }
        .button-secondary { color: #334155; background: #e2e8f0; }
        .hero-card { padding: 1.5rem; border: 1px solid #dbeafe; border-radius: 24px; background: rgba(255,255,255,.88); box-shadow: 0 24px 60px rgba(37, 99, 235, .12); }
        .hero-card h2 { margin: 0 0 1rem; font-size: 1.25rem; }
        .steps { display: grid; gap: .75rem; margin: 0; padding: 0; list-style: none; }
        .steps li { display: flex; align-items: center; gap: .75rem; padding: .75rem; border-radius: 12px; background: #f8fafc; }
        .number { display: grid; place-items: center; width: 2rem; height: 2rem; flex: 0 0 auto; color: #1e40af; background: #dbeafe; border-radius: 9px; font-weight: 900; }
        .steps strong { display: block; }
        .steps small { color: var(--muted); }
        @media (max-width: 800px) { main { grid-template-columns: 1fr; gap: 2rem; min-height: auto; padding-top: 4rem; } h1 { font-size: clamp(2.8rem, 15vw, 4.5rem); } }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <div class="brand">FIXTRACK <span>Gestión de servicios técnicos</span></div>
            @auth
                <a class="login" href="{{ route('panel') }}">Ir al panel</a>
            @else
                <a class="login" href="{{ route('login') }}">Iniciar sesión</a>
            @endauth
        </header>

        <main>
            <section>
                <p class="accent" style="font-weight: 900; letter-spacing: .1em; text-transform: uppercase;">Control claro para cada reparación</p>
                <h1>El taller avanza. <span class="accent">FIXTRACK</span> lo mantiene visible.</h1>
                <p class="lead">Registra clientes, equipos y órdenes de servicio; consulta el progreso, controla costos y conserva un historial de cada cambio.</p>
                <div class="buttons">
                    <a class="button button-primary" href="{{ route('login') }}">Entrar al sistema</a>
                    <a class="button button-secondary" href="#flujo">Ver el flujo</a>
                </div>
            </section>

            <section class="hero-card" id="flujo">
                <h2>Flujo de una orden</h2>
                <ol class="steps">
                    <li><span class="number">1</span><div><strong>Recibir el equipo</strong><small>Cliente, equipo y problema reportado</small></div></li>
                    <li><span class="number">2</span><div><strong>Diagnosticar y autorizar</strong><small>Costos y decisión del cliente</small></div></li>
                    <li><span class="number">3</span><div><strong>Reparar y entregar</strong><small>Seguimiento hasta el cierre</small></div></li>
                </ol>
            </section>
        </main>
    </div>
</body>
</html>

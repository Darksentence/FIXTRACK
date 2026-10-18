<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FIXTRACK · Servicio técnico</title>
    <style>
        :root { --ink: #252d2d; --muted: #69716c; --paper: #efede6; --white: #fffefa; --rust: #b84d2d; --dark: #263332; --line: #d9d6cc; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; color: var(--ink); background: var(--paper); font-family: "Segoe UI", Arial, sans-serif; }
        .top-rule { height: 4px; background: var(--rust); }
        .wrap { width: min(1100px, calc(100% - 2.5rem)); margin: 0 auto; }
        header { display: flex; justify-content: space-between; align-items: center; padding: 1.15rem 0; border-bottom: 1px solid var(--line); }
        .brand { display: flex; align-items: center; gap: .65rem; color: var(--ink); font-weight: 800; letter-spacing: .025em; }
        .brand-mark { display: grid; place-items: center; width: 2rem; height: 2rem; color: var(--rust); border: 1px solid var(--rust); font-size: .78rem; }
        .brand small { display: block; margin-top: .1rem; color: var(--muted); font-size: .68rem; font-weight: 450; letter-spacing: 0; }
        .login { padding: .58rem .8rem; color: var(--ink); border: 1px solid #b9b6ac; border-radius: 3px; font-size: .9rem; font-weight: 650; text-decoration: none; }
        .login:hover { color: var(--rust); border-color: var(--rust); }
        main { display: grid; grid-template-columns: minmax(0, 1.12fr) minmax(310px, .88fr); align-items: center; gap: 4.5rem; min-height: calc(100vh - 78px); padding: 3rem 0 4rem; }
        .kicker { margin: 0 0 .85rem; color: var(--rust); font-size: .82rem; font-weight: 700; }
        h1 { max-width: 640px; margin: 0; font-size: clamp(2.6rem, 5.4vw, 4.5rem); line-height: 1.02; letter-spacing: -.055em; font-weight: 720; }
        .lead { max-width: 520px; margin: 1.2rem 0 0; color: var(--muted); font-size: 1.03rem; line-height: 1.65; }
        .buttons { display: flex; flex-wrap: wrap; gap: .6rem; margin-top: 1.6rem; }
        .button { display: inline-flex; align-items: center; border-radius: 3px; padding: .72rem .9rem; font-weight: 650; text-decoration: none; }
        .button-primary { color: #fff; background: var(--rust); }
        .button-primary:hover { color: #fff; background: #963d24; }
        .button-secondary { color: var(--ink); border: 1px solid #b9b6ac; }
        .button-secondary:hover { border-color: var(--rust); }
        .work-ticket { color: #f4f1e9; background: var(--dark); border-top: 4px solid var(--rust); }
        .ticket-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 1.25rem 1.3rem; border-bottom: 1px solid #4a5751; }
        .ticket-head p { margin: 0 0 .25rem; color: #dfa084; font-size: .76rem; font-weight: 650; }
        .ticket-head h2 { margin: 0; font-size: 1.18rem; font-weight: 650; }
        .ticket-stamp { color: #eee9de; font-family: ui-monospace, Consolas, monospace; font-size: .85rem; }
        .steps { margin: 0; padding: .35rem 1.3rem 1rem; list-style: none; }
        .steps li { display: grid; grid-template-columns: 2.3rem 1fr; gap: .6rem; align-items: baseline; padding: .83rem 0; border-bottom: 1px solid #414d48; }
        .steps li:last-child { border-bottom: 0; }
        .step-no { color: #d98561; font-family: ui-monospace, Consolas, monospace; font-size: .8rem; }
        .steps strong { display: block; font-size: .94rem; font-weight: 650; }
        .steps small { display: block; margin-top: .18rem; color: #c3c8bd; font-size: .82rem; }
        .ticket-foot { display: flex; justify-content: space-between; gap: 1rem; padding: .8rem 1.3rem; color: #c3c8bd; background: #202a28; font-size: .76rem; }
        @media (max-width: 760px) { main { grid-template-columns: 1fr; gap: 2rem; min-height: auto; padding-top: 3rem; } h1 { font-size: clamp(2.7rem, 12vw, 4rem); } }
    </style>
</head>
<body>
    <div class="top-rule"></div>
    <div class="wrap">
        <header>
            <div class="brand"><span class="brand-mark" aria-hidden="true">FT</span><span>FIXTRACK<small>Servicio técnico</small></span></div>
            @auth
                <a class="login" href="{{ route('panel') }}">Abrir el panel</a>
            @else
                <a class="login" href="{{ route('login') }}">Iniciar sesión</a>
            @endauth
        </header>

        <main>
            <section>
                <p class="kicker">Taller / Recepción y seguimiento</p>
                <h1>De la recepción a la entrega, cada reparación queda en orden.</h1>
                <p class="lead">FIXTRACK reúne los datos del cliente, su equipo y el trabajo realizado para que el taller pueda dar seguimiento a cada servicio.</p>
                <div class="buttons">
                    <a class="button button-primary" href="{{ route('login') }}">Entrar al sistema</a>
                    <a class="button button-secondary" href="#flujo">Conocer el proceso</a>
                </div>
            </section>

            <section class="work-ticket" id="flujo" aria-label="Etapas del servicio técnico">
                <div class="ticket-head">
                    <div><p>PROCESO DE SERVICIO</p><h2>Seguimiento del equipo</h2></div>
                    <span class="ticket-stamp">FT / 01</span>
                </div>
                <ol class="steps">
                    <li><span class="step-no">01</span><div><strong>Recepción</strong><small>Cliente, equipo y falla reportada</small></div></li>
                    <li><span class="step-no">02</span><div><strong>Diagnóstico</strong><small>Revisión y autorización del trabajo</small></div></li>
                    <li><span class="step-no">03</span><div><strong>Reparación</strong><small>Trabajo, repuestos y costo final</small></div></li>
                    <li><span class="step-no">04</span><div><strong>Entrega</strong><small>Cierre del servicio e historial</small></div></li>
                </ol>
                <div class="ticket-foot"><span>Registro por orden de servicio</span><span>FIXTRACK</span></div>
            </section>
        </main>
    </div>
</body>
</html>

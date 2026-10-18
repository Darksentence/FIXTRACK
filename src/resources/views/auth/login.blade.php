<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión · FIXTRACK</title>
    <style>
        * { box-sizing: border-box; }
        :root { color-scheme: light; --ink: #252d2d; --muted: #6e746f; --paper: #f2f0e9; --white: #fffefa; --line: #d9d6cc; --rust: #b84d2d; }
        body { min-height: 100vh; margin: 0; display: grid; grid-template-columns: minmax(300px, .9fr) 1.1fr;
               background: var(--paper); color: var(--ink); font-family: "Segoe UI", Arial, sans-serif; }
        .identity { min-height: 100vh; padding: clamp(2rem, 5vw, 5rem); display: flex; flex-direction: column;
                    justify-content: space-between; background: var(--ink); color: #f7f5ee; border-top: 6px solid var(--rust); }
        .wordmark { display: flex; align-items: center; gap: .85rem; text-decoration: none; color: inherit; width: fit-content; }
        .mark { display: grid; place-items: center; width: 42px; height: 42px; border: 1px solid #747b73; color: #f0a27f;
                font-family: Consolas, monospace; font-weight: 700; letter-spacing: -.08em; }
        .wordmark strong { display: block; font-size: 1.08rem; letter-spacing: .08em; }
        .wordmark small { display: block; margin-top: .15rem; color: #b8beb5; font-size: .75rem; }
        .identity-copy { max-width: 440px; margin: 4rem 0; }
        .eyebrow { margin: 0 0 1rem; color: #e2a083; font: 600 .75rem Consolas, monospace; letter-spacing: .12em; text-transform: uppercase; }
        h1 { max-width: 410px; margin: 0; font-size: clamp(2.15rem, 4vw, 3.5rem); line-height: 1.08; font-weight: 600; letter-spacing: -.045em; }
        .identity-copy > p:last-child { max-width: 365px; margin: 1.2rem 0 0; color: #c6cbc3; font-size: 1rem; line-height: 1.7; }
        .identity-foot { display: flex; justify-content: space-between; gap: 1rem; padding-top: 1rem; border-top: 1px solid #4b5350;
                         color: #b8beb5; font-size: .75rem; }
        .form-side { display: grid; place-items: center; padding: clamp(1.5rem, 5vw, 5rem); }
        .form-wrap { width: min(100%, 420px); }
        .form-kicker { margin: 0 0 .6rem; color: var(--rust); font: 600 .75rem Consolas, monospace; letter-spacing: .1em; text-transform: uppercase; }
        h2 { margin: 0; font-size: 2rem; font-weight: 600; letter-spacing: -.035em; }
        .sub { margin: .55rem 0 2rem; color: var(--muted); line-height: 1.6; }
        label { display: block; margin: 1.2rem 0 .45rem; font-size: .88rem; font-weight: 600; }
        input[type=email], input[type=password] { width: 100%; min-height: 47px; padding: .7rem .8rem; border: 1px solid #c9c8bf;
                border-radius: 2px; background: var(--white); color: var(--ink); font: inherit; }
        input:focus { outline: 2px solid #c47556; outline-offset: 1px; border-color: var(--rust); }
        .check { display: flex; align-items: center; gap: .55rem; margin: 1.3rem 0 1.5rem; color: #545d58; font-size: .86rem; }
        .check label { margin: 0; font-weight: 400; }
        .check input { accent-color: var(--rust); }
        button { width: 100%; min-height: 48px; padding: .75rem 1rem; border: 0; border-radius: 2px; background: var(--rust);
                 color: #fffefa; font: 600 .95rem "Segoe UI", Arial, sans-serif; cursor: pointer; }
        button:hover { background: #9e3e24; }
        .error { margin: .45rem 0 0; color: #a3342c; font-size: .84rem; }
        .back { display: inline-block; margin-top: 1.7rem; color: #59615c; font-size: .84rem; text-decoration-color: #aaa99f; text-underline-offset: 3px; }
        .back:hover { color: var(--rust); }
        @media (max-width: 760px) {
            body { grid-template-columns: 1fr; }
            .identity { min-height: auto; padding: 1.4rem 1.5rem 1.6rem; }
            .identity-copy { margin: 2.6rem 0 1.8rem; }
            h1 { font-size: clamp(1.85rem, 8vw, 2.7rem); }
            .identity-copy > p:last-child { margin-top: .8rem; }
            .identity-foot { font-size: .7rem; }
            .form-side { padding: 2.6rem 1.5rem 3rem; }
        }
        @media (max-width: 380px) { .identity-foot span:last-child { text-align: right; } }
    </style>
</head>
<body>
    <aside class="identity">
        <a class="wordmark" href="/" aria-label="FIXTRACK, volver al inicio">
            <span class="mark" aria-hidden="true">FT</span>
            <span><strong>FIXTRACK</strong><small>Gestión de taller</small></span>
        </a>

        <div class="identity-copy">
            <p class="eyebrow">Acceso al sistema</p>
            <h1>El trabajo del taller, bajo control.</h1>
            <p>Consulta órdenes, equipos y clientes desde un solo lugar. Ingresa con tu cuenta para continuar.</p>
        </div>

        <div class="identity-foot"><span>Servicio técnico</span><span>Órdenes · Equipos · Clientes</span></div>
    </aside>

    <main class="form-side">
        <section class="form-wrap" aria-labelledby="login-title">
            <p class="form-kicker">Área de trabajo</p>
            <h2 id="login-title">Iniciar sesión</h2>
            <p class="sub">Usa tus credenciales para abrir el panel del taller.</p>

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf

                <label for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror

                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror

                <div class="check">
                    <input id="remember" type="checkbox" name="remember" value="1">
                    <label for="remember">Mantener la sesión iniciada</label>
                </div>

                <button type="submit">Entrar al panel</button>
            </form>
            <a class="back" href="/">← Volver a FIXTRACK</a>
        </section>
    </main>
</body>
</html>

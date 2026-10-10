<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión · FIXTRACK</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
               font-family: system-ui, -apple-system, 'Segoe UI', sans-serif; background: #f3f4f6; color: #111827; }
        .card { width: 100%; max-width: 380px; margin: 1rem; padding: 2rem; background: #fff;
                border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        h1 { margin: 0 0 .25rem; font-size: 1.5rem; }
        p.sub { margin: 0 0 1.5rem; color: #6b7280; font-size: .9rem; }
        label { display: block; margin-bottom: .25rem; font-size: .85rem; font-weight: 600; }
        input[type=email], input[type=password] { width: 100%; padding: .6rem .75rem; margin-bottom: 1rem;
                border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; }
        .check { display: flex; align-items: center; gap: .5rem; margin-bottom: 1.25rem; font-size: .85rem; }
        .check label { margin: 0; font-weight: 400; }
        button { width: 100%; padding: .7rem; border: 0; border-radius: 8px; background: #2563eb;
                 color: #fff; font-size: 1rem; font-weight: 600; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .error { margin: -.5rem 0 1rem; color: #b91c1c; font-size: .85rem; }
    </style>
</head>
<body>
    <main class="card">
        <h1>FIXTRACK</h1>
        <p class="sub">Inicia sesión para continuar</p>

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

            <button type="submit">Entrar</button>
        </form>
    </main>
</body>
</html>

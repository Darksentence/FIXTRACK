<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel · FIXTRACK</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
               font-family: system-ui, -apple-system, 'Segoe UI', sans-serif; background: #f3f4f6; color: #111827; }
        .card { width: 100%; max-width: 420px; margin: 1rem; padding: 2rem; background: #fff;
                border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        h1 { margin: 0 0 .5rem; font-size: 1.4rem; }
        .rol { display: inline-block; margin-bottom: 1.5rem; padding: .2rem .6rem; border-radius: 999px;
               background: #dbeafe; color: #1e40af; font-size: .8rem; font-weight: 600; }
        button { padding: .6rem 1.2rem; border: 0; border-radius: 8px; background: #374151;
                 color: #fff; font-size: .95rem; cursor: pointer; }
        button:hover { background: #111827; }
    </style>
</head>
<body>
    <main class="card">
        <h1>Hola, {{ auth()->user()->name }}</h1>
        <span class="rol">{{ auth()->user()->role?->etiqueta() }}</span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Cerrar sesión</button>
        </form>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página no encontrada · FIXTRACK</title>
    <style>
        body{margin:0;min-height:100vh;display:grid;place-items:center;padding:1rem;background:#efede6;color:#252d2d;font-family:Segoe UI,Arial,sans-serif}
        main{max-width:34rem;padding:2rem;background:#fffefa;border:1px solid #d9d6cc;border-radius:6px;text-align:center}
        h1{margin:0;color:#b84d2d;font-size:3rem}p{line-height:1.6;color:#69716c}a{display:inline-block;margin-top:1rem;padding:.7rem 1rem;background:#b84d2d;color:white;text-decoration:none;border-radius:4px}
    </style>
</head>
<body><main><h1>404</h1><h2>Página no encontrada</h2><p>El recurso que buscas no existe o pudo haber sido movido. Verifica la dirección e inténtalo nuevamente.</p><a href="{{ auth()->check() ? route('panel') : route('login') }}">{{ auth()->check() ? 'Volver al panel' : 'Ir al inicio de sesión' }}</a></main></body>
</html>

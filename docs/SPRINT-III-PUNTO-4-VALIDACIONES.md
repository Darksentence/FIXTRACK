# Sprint III — Punto 4: Validaciones y manejo de errores

## Objetivo

Evitar que FIXTRACK almacene información inválida, mostrar retroalimentación clara a los usuarios y reforzar el control de acceso a las operaciones del sistema.

## Cambios realizados

### Validación del servidor (Laravel)

- Se reforzó el registro y actualización de clientes: nombre con longitud mínima, teléfono con al menos 8 dígitos y caracteres telefónicos permitidos, correo electrónico válido y código único.
- Se reforzó el registro y actualización de equipos: tipo y marca con longitud mínima, y cliente existente.
- Se reforzó el registro y actualización de órdenes: descripción del problema con longitud mínima, equipo perteneciente al cliente seleccionado y costos no negativos dentro del máximo permitido.
- Se reforzó el diagnóstico y la reparación: descripción mínima del diagnóstico/trabajo, costos no negativos y fecha de finalización igual o posterior al inicio.
- Se agregaron mensajes de validación en español para reglas relevantes.

### Validación del cliente (HTML)

- Se añadieron atributos `required`, `minlength`, `maxlength`, `type`, `min`, `max`, `step` y `pattern` donde corresponde.
- Se muestran errores debajo de los campos mediante `resources/views/partials/field-error.blade.php`.
- Los controles inválidos se identifican con `aria-invalid` y estilos visuales.

### Manejo de errores y retroalimentación

- Se mantiene el resumen de errores de validación y los mensajes de éxito/error existentes.
- Se añadió soporte visual para mensajes de advertencia mediante `session('warning')`.
- Se añadieron vistas personalizadas para los errores HTTP 403 y 404.
- Se conserva el middleware de roles para impedir operaciones de usuarios sin autorización.

## Matriz de pruebas

| ID | Escenario | Datos / acción | Resultado esperado | Prueba automatizada |
|---|---|---|---|---|
| VAL-01 | Crear cliente válido | Teléfono `7000-0100`, correo válido | Cliente guardado | Existente |
| VAL-02 | Teléfono inválido | `telefono123` | Error en `phone`; no guardar | Añadida |
| VAL-03 | Nombre demasiado corto | `A` | Error en `name`; no guardar | Añadida |
| VAL-04 | Equipo de otro cliente | Cliente y equipo no relacionados | Error en `equipment_id`; no crear orden | Existente |
| VAL-05 | Costo estimado negativo | `-10.00` | Error en `estimated_cost`; no crear orden | Añadida |
| VAL-06 | Problema demasiado corto | `Mal` | Error en `reported_problem`; no crear orden | Añadida |
| SEG-01 | Cliente intenta acceder a catálogos | Perfil cliente | HTTP 403 | Existente |
| SEG-02 | Invitado intenta acceder a módulo privado | Sin sesión | Redirección al login | Existente |
| ERR-01 | Recurso inexistente | Ruta no registrada | HTTP 404 con vista personalizada | Añadida |
| ERR-02 | Usuario sin rol intenta acceder a admin | Perfil técnico | HTTP 403 | Existente |

## Ejecución de pruebas

Desde la carpeta `src/` del proyecto:

```bash
composer install
php artisan test
```

Para ejecutar únicamente las pruebas de clientes/equipos y órdenes:

```bash
php artisan test --filter=ClientEquipmentCrudTest
php artisan test --filter=ServiceOrderCrudTest
php artisan test --filter=AuthenticationTest
```

## Estado de verificación

- Sintaxis PHP de los archivos de aplicación y pruebas: verificada con `php -l`.
- Pruebas PHPUnit/Laravel: pendientes de ejecución en un entorno con Composer y dependencias instaladas (`vendor/`).
- Pruebas visuales en navegador y validación en diferentes resoluciones: pendientes.

## Archivos principales modificados

- `app/Http/Requests/StoreClientRequest.php`
- `app/Http/Requests/UpdateClientRequest.php`
- `app/Http/Requests/StoreEquipmentRequest.php`
- `app/Http/Requests/UpdateEquipmentRequest.php`
- `app/Http/Requests/StoreServiceOrderRequest.php`
- `app/Http/Requests/UpdateServiceOrderRequest.php`
- `app/Http/Requests/SaveDiagnosisRequest.php`
- `app/Http/Requests/SaveRepairRequest.php`
- `resources/views/partials/field-error.blade.php`
- `resources/views/errors/403.blade.php`
- `resources/views/errors/404.blade.php`
- Formularios Blade de clientes, equipos y órdenes de servicio.
- `tests/Feature/ClientEquipmentCrudTest.php`
- `tests/Feature/ServiceOrderCrudTest.php`

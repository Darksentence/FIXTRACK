# FIXTRACK — Fase 2

## Alcance implementado

La aplicación Laravel incluye ahora el flujo operativo principal de órdenes de servicio:

- autenticación con roles `admin`, `tecnico` y `cliente`;
- migraciones y relaciones de clientes, equipos, órdenes, diagnósticos, reparaciones e historial;
- CRUD de órdenes para administración y técnicos;
- búsqueda por código, cliente, marca, modelo o número de serie;
- filtros por estado y prioridad;
- vista detallada con costos, equipo, diagnóstico, reparación e historial;
- transiciones de estado controladas por reglas de negocio;
- registro automático de cada cambio en `order_histories`;
- validación de que el equipo pertenece al cliente seleccionado;
- pruebas de autenticación, persistencia, relaciones y CRUD;
- datos de demostración para abrir la aplicación inmediatamente después de sembrar la base.

## Ejecutar localmente

Desde `src/`:

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

La aplicación queda disponible en `http://127.0.0.1:8000`.

Para una demostración rápida sin MySQL, se puede usar SQLite en `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Después se crea el archivo `database/database.sqlite` y se ejecuta nuevamente `php artisan migrate:fresh --seed`.

## Usuarios de demostración

Todos usan la contraseña `Fixtrack123*`:

| Rol | Correo |
| --- | --- |
| Administrador | `admin@fixtrack.test` |
| Técnico | `tecnico@fixtrack.test` |
| Cliente | `cliente@fixtrack.test` |

El CRUD de órdenes está restringido a administradores y técnicos para evitar que un cliente vea información de otros clientes. El portal de consulta individual de clientes queda como siguiente incremento.

## Verificar

```bash
php artisan test
php artisan route:list
```

Los cambios se encuentran en la rama local `fase2-completa-local` y no se han subido al remoto.

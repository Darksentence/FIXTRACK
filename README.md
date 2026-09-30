# FIXTRACK

> Sistema Web de Gestión y Seguimiento de Servicios Técnicos

## ¿De qué trata este proyecto?

FIXTRACK nace para resolver un problema común en los micro y pequeños negocios de reparación de computadoras y videojuegos. Muchas veces, el control de los equipos se lleva en libretas, facturas de papel o chats de WhatsApp, lo que hace difícil darle seguimiento a un trabajo o buscar el historial si un cliente vuelve por una garantía.

La idea de este sistema es organizar y centralizar todo el proceso. Queremos que tanto el técnico como el cliente tengan claro en qué estado está cada equipo, sin la necesidad de estar cruzando llamadas o mensajes constantemente.

## ¿Cómo va a funcionar?

Nos estamos enfocando en mantener las cosas simples, rápidas y directas:

- **IDs únicos para no perder nada:** Se manejarán identificadores propios para cada cliente (ej. `C-0142`) y tickets independientes para cada reparación (ej. `A-0052`). Si el cliente vuelve tiempo después, su registro se mantiene y se le asocian nuevos tickets.
- **Todo tipo de equipos:** El sistema permitirá registrar laptops, PCs de escritorio, consolas, controles y periféricos.
- **Seguimiento desde el celular:** El cliente podrá acceder a una interfaz web optimizada para móviles para consultar el estado de su equipo.

## Flujo de los tickets

Cada equipo que ingrese al taller pasará por diferentes estados para mantener un seguimiento ordenado:

1. **RECIBIDO**
2. **EN DIAGNÓSTICO**
3. **DIAGNÓSTICO REALIZADO**
4. **PENDIENTE DE AUTORIZACIÓN**
5. **EN REPARACIÓN**
6. **LISTO PARA RETIRO**
7. **ENTREGADO**

---

# Stack tecnológico

Para la Fase 2 del proyecto se utiliza una arquitectura basada en Laravel siguiendo el patrón MVC (Modelo-Vista-Controlador).

- **Backend:** PHP 8.3 + Laravel 13
- **Framework:** Laravel
- **Gestor de dependencias PHP:** Composer
- **Base de datos:** MySQL 8
- **Servidor local de desarrollo:** Laravel Artisan / WampServer
- **Frontend:** HTML5, CSS3 y recursos proporcionados por Laravel
- **Control de versiones:** Git
- **Repositorio:** GitHub

---

# Estructura del repositorio

El código de la aplicación Laravel se encuentra dentro de la carpeta:

`src/`

Estructura principal:

```text
FIXTRACK/
├── docs/
├── src/
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── tests/
│   ├── .env.example
│   ├── artisan
│   ├── composer.json
│   └── composer.lock
└── README.md
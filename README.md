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
---

# Requisitos para ejecutar el proyecto

Antes de ejecutar FIXTRACK, es necesario tener instaladas las siguientes herramientas:

- PHP 8.3 o superior
- Composer
- MySQL 8
- Git
- Un entorno local como WampServer
- Visual Studio Code o cualquier editor compatible

---

# Instalación del proyecto

## 1. Clonar el repositorio

Abrir una terminal y ejecutar:

```bash
git clone https://github.com/Darksentence/FIXTRACK.git
```

Luego ingresar a la carpeta del proyecto:

```bash
cd FIXTRACK
```

## 2. Entrar al proyecto Laravel

La aplicación Laravel se encuentra dentro de la carpeta `src`:

```bash
cd src
```

## 3. Instalar las dependencias

Ejecutar:

```bash
composer install
```

Composer instalará automáticamente las dependencias definidas en `composer.json` y `composer.lock`.

## 4. Crear el archivo de entorno

El archivo `.env` contiene la configuración local de cada equipo y no se almacena en el repositorio.

Crear el archivo `.env` utilizando `.env.example` como base.

En Windows se puede ejecutar:

```bash
copy .env.example .env
```

## 5. Generar la clave de Laravel

Ejecutar:

```bash
php artisan key:generate
```

Laravel generará automáticamente la clave de la aplicación dentro del archivo `.env`.

---

# Configuración de MySQL

Cada integrante deberá configurar en su archivo `.env` los parámetros correspondientes a su entorno local.

Ejemplo:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fixtrack
DB_USERNAME=root
DB_PASSWORD=
```

La contraseña y demás parámetros pueden variar según la configuración de MySQL de cada integrante.

El archivo `.env` no debe subirse al repositorio.

---

# Base de datos

La conexión del proyecto Laravel con MySQL se encuentra preparada para el desarrollo de la aplicación.

Las migraciones incluidas actualmente corresponden a la estructura base proporcionada por Laravel.

Las tablas, relaciones, migraciones y lógica de persistencia propias de FIXTRACK serán desarrolladas durante la implementación de los módulos correspondientes de la Fase 2.

---

# Ejecutar el proyecto

Con MySQL iniciado y estando dentro de la carpeta `src`, ejecutar:

```bash
php artisan serve
```

Laravel iniciará el servidor local de desarrollo.

Por defecto estará disponible en:

```text
http://127.0.0.1:8000
```

Abrir esa dirección en el navegador para acceder al proyecto.

Para detener el servidor se puede utilizar:

```text
Ctrl + C
```

---

# Comandos útiles

Instalar dependencias:

```bash
composer install
```

Limpiar la configuración almacenada en caché:

```bash
php artisan config:clear
```

Iniciar el servidor:

```bash
php artisan serve
```

Consultar las rutas registradas:

```bash
php artisan route:list
```

---

# Estado actual del proyecto

La configuración base para el desarrollo de la Fase 2 se encuentra preparada:

- Proyecto Laravel creado.
- Dependencias administradas mediante Composer.
- Estructura MVC de Laravel disponible.
- Configuración de entorno mediante `.env`.
- Conexión con MySQL preparada y comprobada.
- Código fuente integrado dentro de `src/`.
- Repositorio Git configurado para el trabajo colaborativo.

A partir de esta base pueden desarrollarse los módulos, controladores, modelos, rutas, servicios, autenticación, migraciones y operaciones CRUD correspondientes a FIXTRACK.
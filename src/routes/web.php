<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ServiceOrderAttachmentController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| FIXTRACK - Rutas del sistema
|--------------------------------------------------------------------------
| Se conservan las rutas existentes de autenticación, clientes,
| equipos y órdenes de servicio.
|
| FASE 3 - PARTE 2:
| Se integran las rutas del portal del cliente y de los archivos
| adjuntos desarrolladas por Z.
|
| El módulo de reportes de Eduardo se integrará por separado.
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Autenticación (solo invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login')
        ->name('login.attempt');
});

// Rutas que requieren sesión iniciada
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::view('/panel', 'panel')->name('panel');

    /*
    |--------------------------------------------------------------------------
    | Administrador y Técnico
    |--------------------------------------------------------------------------
    | Se conservan las rutas originales para gestionar clientes,
    | equipos y órdenes de servicio.
    |--------------------------------------------------------------------------
    */

    // Las órdenes contienen información operativa y solo las gestionan admin/técnico.
    Route::middleware('role:admin,tecnico')->group(function () {

        // Clientes
        Route::get('/clientes', [ClientController::class, 'index'])->name('clients.index');

        Route::get('/clientes/crear', [ClientController::class, 'create'])->name('clients.create');

        Route::post('/clientes', [ClientController::class, 'store'])->name('clients.store');

        Route::get('/clientes/{client}/editar', [ClientController::class, 'edit'])
            ->whereNumber('client')
            ->name('clients.edit');

        Route::put('/clientes/{client}', [ClientController::class, 'update'])
            ->whereNumber('client')
            ->name('clients.update');

        Route::delete('/clientes/{client}', [ClientController::class, 'destroy'])
            ->whereNumber('client')
            ->name('clients.destroy');

        // Equipos
        Route::get('/equipos', [EquipmentController::class, 'index'])->name('equipments.index');

        Route::get('/equipos/crear', [EquipmentController::class, 'create'])->name('equipments.create');

        Route::post('/equipos', [EquipmentController::class, 'store'])->name('equipments.store');

        Route::get('/equipos/{equipment}/editar', [EquipmentController::class, 'edit'])
            ->whereNumber('equipment')
            ->name('equipments.edit');

        Route::put('/equipos/{equipment}', [EquipmentController::class, 'update'])
            ->whereNumber('equipment')
            ->name('equipments.update');

        Route::delete('/equipos/{equipment}', [EquipmentController::class, 'destroy'])
            ->whereNumber('equipment')
            ->name('equipments.destroy');

        // Órdenes de servicio
        Route::get('/ordenes', [TicketController::class, 'index'])->name('orders.index');

        Route::get('/ordenes/crear', [TicketController::class, 'create'])->name('orders.create');

        Route::post('/ordenes', [TicketController::class, 'store'])->name('orders.store');

        Route::get('/ordenes/{serviceOrder}', [TicketController::class, 'show'])
            ->whereNumber('serviceOrder')
            ->name('orders.show');

        Route::get('/ordenes/{serviceOrder}/editar', [TicketController::class, 'edit'])
            ->whereNumber('serviceOrder')
            ->name('orders.edit');

        Route::put('/ordenes/{serviceOrder}', [TicketController::class, 'update'])
            ->whereNumber('serviceOrder')
            ->name('orders.update');

        Route::delete('/ordenes/{serviceOrder}', [TicketController::class, 'destroy'])
            ->whereNumber('serviceOrder')
            ->name('orders.destroy');

        Route::patch('/ordenes/{serviceOrder}/estado', [TicketController::class, 'changeStatus'])
            ->whereNumber('serviceOrder')
            ->name('orders.status');

        Route::post('/ordenes/{serviceOrder}/diagnostico', [TicketController::class, 'saveDiagnosis'])
            ->whereNumber('serviceOrder')
            ->name('orders.diagnosis');

        Route::post('/ordenes/{serviceOrder}/reparacion', [TicketController::class, 'saveRepair'])
            ->whereNumber('serviceOrder')
            ->name('orders.repair');

        /*
        |--------------------------------------------------------------------------
        | FASE 3 - PARTE 2: Archivos adjuntos de órdenes
        |--------------------------------------------------------------------------
        | Rutas incorporadas del trabajo de Z.
        |
        | Permiten al Administrador y al Técnico:
        | 1. Subir archivos relacionados con una orden.
        | 2. Consultar los archivos adjuntos.
        | 3. Eliminar archivos adjuntos.
        |
        | Se conserva el middleware role:admin,tecnico.
        |--------------------------------------------------------------------------
        */

        Route::post('/ordenes/{serviceOrder}/archivos', [ServiceOrderAttachmentController::class, 'store'])
            ->whereNumber('serviceOrder')
            ->name('orders.attachments.store');

        Route::get('/ordenes/{serviceOrder}/archivos/{attachment}', [ServiceOrderAttachmentController::class, 'view'])
            ->whereNumber('serviceOrder')
            ->whereNumber('attachment')
            ->name('orders.attachments.view');

        Route::delete('/ordenes/{serviceOrder}/archivos/{attachment}', [ServiceOrderAttachmentController::class, 'destroy'])
            ->whereNumber('serviceOrder')
            ->whereNumber('attachment')
            ->name('orders.attachments.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | FASE 3 - PARTE 2: Portal del cliente
    |--------------------------------------------------------------------------
    | Rutas incorporadas del trabajo de Z.
    |
    | Solo pueden acceder los usuarios con rol Cliente.
    |
    | Funciones:
    | 1. Consultar sus órdenes de servicio.
    | 2. Ver el detalle de una orden.
    | 3. Adjuntar archivos a una orden.
    | 4. Consultar los archivos asociados.
    |
    | Las comprobaciones de propiedad y autorización de cada orden
    | corresponden a los controladores del módulo.
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:cliente')->group(function () {

        // Listado de órdenes del cliente autenticado
        Route::get('/mis-ordenes', [ClientPortalController::class, 'index'])
            ->name('client.orders.index');

        // Detalle de una orden del cliente
        Route::get('/mis-ordenes/{serviceOrder}', [ClientPortalController::class, 'show'])
            ->whereNumber('serviceOrder')
            ->name('client.orders.show');

        // Subir archivos a una orden del cliente
        Route::post('/mis-ordenes/{serviceOrder}/archivos', [ServiceOrderAttachmentController::class, 'store'])
            ->whereNumber('serviceOrder')
            ->name('client.orders.attachments.store');

        // Consultar un archivo adjunto de una orden del cliente
        Route::get('/mis-ordenes/{serviceOrder}/archivos/{attachment}', [ServiceOrderAttachmentController::class, 'view'])
            ->whereNumber('serviceOrder')
            ->whereNumber('attachment')
            ->name('client.orders.attachments.view');
    });
});

// Ejemplo de uso del middleware de roles para el resto del equipo:
//
// Route::middleware(['auth', 'role:admin'])->group(function () {
//     // solo administradores
// });
//
// Route::middleware(['auth', 'role:admin,tecnico'])->group(function () {
//     // administradores y técnicos
// });

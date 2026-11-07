<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceOrderAttachmentController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



// Autenticación (solo invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login')
        ->name('login.attempt');
});

// Rutas que requieren sesión iniciada
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::view('/panel', 'panel')
        ->name('panel');

    /*
    |--------------------------------------------------------------------------
    | PARTE 4 - SPRINT III: REPORTES Y ESTADÍSTICAS
    |--------------------------------------------------------------------------
    | Acceso exclusivo para administradores.
    | Dashboard, estadísticas y exportaciones PDF y Excel.
    */

    Route::middleware('role:admin')->group(function () {

        // Dashboard de reportes
        Route::get('/reportes', function () {
            return view('reports.index');
        })->name('reports.index');

        // Datos estadísticos en formato JSON
        Route::get('/reportes/resumen', [ReportController::class, 'summary'])
            ->name('reports.summary');

        // Exportación de reporte en PDF
        Route::get('/reportes/pdf', [ReportController::class, 'exportPdf'])
            ->name('reports.pdf');

        // Exportación de órdenes de servicio en Excel
        Route::get('/reportes/excel', [ReportController::class, 'exportExcel'])
            ->name('reports.excel');

    });

    /*
    |--------------------------------------------------------------------------
    | CLIENTES, EQUIPOS Y ÓRDENES DE SERVICIO
    |--------------------------------------------------------------------------
    | Información operativa disponible para administradores y técnicos.
    */

    Route::middleware('role:admin,tecnico')->group(function () {

        // CLIENTES
        Route::get('/clientes', [ClientController::class, 'index'])
            ->name('clients.index');

        Route::get('/clientes/crear', [ClientController::class, 'create'])
            ->name('clients.create');

        Route::post('/clientes', [ClientController::class, 'store'])
            ->name('clients.store');

        Route::get('/clientes/{client}/editar', [ClientController::class, 'edit'])
            ->whereNumber('client')
            ->name('clients.edit');

        Route::put('/clientes/{client}', [ClientController::class, 'update'])
            ->whereNumber('client')
            ->name('clients.update');

        Route::delete('/clientes/{client}', [ClientController::class, 'destroy'])
            ->whereNumber('client')
            ->name('clients.destroy');

        // EQUIPOS
        Route::get('/equipos', [EquipmentController::class, 'index'])
            ->name('equipments.index');

        Route::get('/equipos/crear', [EquipmentController::class, 'create'])
            ->name('equipments.create');

        Route::post('/equipos', [EquipmentController::class, 'store'])
            ->name('equipments.store');

        Route::get('/equipos/{equipment}/editar', [EquipmentController::class, 'edit'])
            ->whereNumber('equipment')
            ->name('equipments.edit');

        Route::put('/equipos/{equipment}', [EquipmentController::class, 'update'])
            ->whereNumber('equipment')
            ->name('equipments.update');

        Route::delete('/equipos/{equipment}', [EquipmentController::class, 'destroy'])
            ->whereNumber('equipment')
            ->name('equipments.destroy');

        // ÓRDENES DE SERVICIO
        Route::get('/ordenes', [TicketController::class, 'index'])
            ->name('orders.index');

        Route::get('/ordenes/crear', [TicketController::class, 'create'])
            ->name('orders.create');

        Route::post('/ordenes', [TicketController::class, 'store'])
            ->name('orders.store');

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

    Route::middleware('role:cliente')->group(function () {
        Route::get('/mis-ordenes', [ClientPortalController::class, 'index'])
            ->name('client.orders.index');

        Route::get('/mis-ordenes/{serviceOrder}', [ClientPortalController::class, 'show'])
            ->whereNumber('serviceOrder')
            ->name('client.orders.show');

        Route::post('/mis-ordenes/{serviceOrder}/archivos', [ServiceOrderAttachmentController::class, 'store'])
            ->whereNumber('serviceOrder')
            ->name('client.orders.attachments.store');

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

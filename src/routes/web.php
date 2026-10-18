<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

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

    // Las órdenes contienen información operativa y solo las gestionan admin/técnico.
    Route::middleware('role:admin,tecnico')->group(function () {
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

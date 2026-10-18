<?php

use App\Http\Controllers\AuthController;
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

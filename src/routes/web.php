<?php

use App\Http\Controllers\AuthController;
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

    // Pantalla provisional de destino tras el login (cualquier rol).
    Route::view('/panel', 'panel')->name('panel');
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

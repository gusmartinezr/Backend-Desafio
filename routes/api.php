<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EspacioController;
use App\Http\Controllers\ReservaController;

// Rutas públicas
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Rutas protegidas con middleware de autenticación
Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('espacios', EspacioController::class);
    Route::apiResource('reservas', ReservaController::class);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CitaController;

Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando 🚀']);
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas por token
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Citas
    Route::get('/citas', [CitaController::class, 'index']); // Listar citas
    Route::post('/citas', [CitaController::class, 'store']); // Registrar cita
    Route::get('/citas/{id}', [CitaController::class, 'show']); // Ver cita
    Route::put('/citas/{id}', [CitaController::class, 'update']); // Modificar cita
    Route::delete('/citas/{id}', [CitaController::class, 'destroy']); // Cancelar cita
});
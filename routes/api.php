<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RepuestoApiController;
use App\Http\Controllers\Api\VehiculoApiController;

/*
|--------------------------------------------------------------------------
| Rutas de la API de AutoSys
|--------------------------------------------------------------------------
| Estas rutas son cargadas por el RouteServiceProvider y todas tendrán
| el prefijo "/api" asignado automáticamente.
*/

// Rutas públicas de prueba
Route::get('/ping', function () {
    return response()->json(['mensaje' => 'La API de AutoSys está en línea.']);
});

// Rutas protegidas (Se necesita enviar un Token desde la App)
Route::middleware('auth:sanctum')->group(function () {
    
    // Devolver datos del usuario conectado
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    // Gestión de Repuestos vía API
    Route::get('/repuestos', [RepuestoApiController::class, 'index']);
    Route::post('/repuestos', [RepuestoApiController::class, 'store']);
    
    // Gestión de Vehículos vía API 
    Route::get('/vehiculos', [VehiculoApiController::class, 'index']);
    Route::get('/vehiculos/placa/{placa}', [VehiculoApiController::class, 'buscarPorPlaca']);
    
});
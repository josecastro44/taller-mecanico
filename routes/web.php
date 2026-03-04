<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// ==========================================
// RUTAS PÚBLICAS (Cualquier usuario puede verlas)
// ==========================================

// Landing Page (Página de inicio para clientes)
Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\ServicioController; // Verifica que esta línea esté arriba

Route::get('/catalogo', [ServicioController::class, 'index']);

// Pantalla de Login para el personal del taller
Route::get('/login', function() {
    return view('login');
});


// ==========================================
// RUTAS DEL SISTEMA (Módulos Internos)
// ==========================================

// Dashboard principal (Muestra las estadísticas y tarjetas)
Route::get('/inicio', function () {
    return view('inicio');
});

// Módulo 1: Recepción (Formulario para registrar clientes y vehículos)
Route::get('/recepcion', function () {
    return view('recepcion');
});

Route::get('/servicios', function () {
    return view('servicios');
});

// Ruta para la pantalla de Repuestos e Insumos
Route::get('/repuestos', function () {
    return view('repuestos');
})->name('repuestos');


Route::get('/empleados', function () {
    return view('empleados');
})->name('empleados');

Route::get('/finanzas', function () {
    return view('finanzas');
})->name('finanzas');



Route::post('/logout', function (Illuminate\Http\Request $request) {
    Illuminate\Support\Facades\Auth::logout(); 
    $request->session()->invalidate(); 
    $request->session()->regenerateToken(); 

    // Aquí cambiamos /login por / (que es tu página de inicio 'welcome')
    return redirect('/'); 
})->name('logout');
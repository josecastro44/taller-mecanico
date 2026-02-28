<?php

use Illuminate\Support\Facades\Route;

// ==========================================
// RUTAS PÚBLICAS (Cualquier usuario puede verlas)
// ==========================================

// Landing Page (Página de inicio para clientes)
Route::get('/', function () {
    return view('welcome');
});

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

// Aquí tus compañeros pueden ir agregando: /servicios, /repuestos, etc.
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\ServicioController; // Verifica que esta línea esté arriba

Route::get('/catalogo', [ServicioController::class, 'index']);
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Aquí unimos tus controladores y los de tu compañero
use App\Http\Controllers\RepuestoController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ServicioController;

// ==========================================
// RUTAS PÚBLICAS
// ==========================================

Route::get('/', function () {
    return view('welcome');
});

Route::get('/catalogo', [ServicioController::class, 'index']);

Route::get('/login', function() {
    return view('login');
});

Route::post('/login', function (Request $request) {
    $credenciales = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credenciales)) {
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'email' => 'El correo o la contraseña son incorrectos.',
    ]);
})->name('login.post');


// ==========================================
// RUTAS DEL SISTEMA (Módulos Internos)
// ==========================================

Route::get('/inicio', function () {
    return view('inicio');
});

// Módulo: Recepción
Route::get('/recepcion', [RecepcionController::class, 'index'])->name('recepcion.index');
Route::post('/recepcion', [RecepcionController::class, 'guardar'])->name('recepcion.guardar');

// Módulo: Servicios
Route::get('/servicios', function () {
    return view('servicios');
})->name('servicios.index');

// Módulo: Repuestos (Tus cambios)
Route::get('/repuestos', [RepuestoController::class, 'index'])->name('repuestos.index');
Route::put('/repuestos/{id}', [RepuestoController::class, 'update'])->name('repuestos.update');
Route::post('/repuestos', [RepuestoController::class, 'store'])->name('repuestos.store');

// Módulo: Empleados
Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
Route::post('/empleados', [EmpleadoController::class, 'guardar'])->name('empleados.guardar');

Route::get('/ventas', function () { return view('ventas'); })->name('ventas');
Route::get('/compras', function () { return view('compras'); })->name('compras');
Route::get('/proveedores', function () { return view('proveedores'); })->name('proveedores');
Route::get('/finanzas', function () { return view('finanzas'); })->name('finanzas');

// Reportes (Cambio de tu compañero)
Route::get('/reportes', function () {
    return view('reportes');
})->name('reportes');


// --- DISTRIBUIDOR DE TRÁFICO ---
Route::get('/dashboard', function () {
    $rol = Auth::user()->rol;
    if ($rol === 'gerente') return redirect()->route('gerente');
    if ($rol === 'administrador') return redirect()->route('administrador');
    if ($rol === 'mecanico') return redirect()->route('mecanico');
    return redirect('/');
})->middleware(['auth'])->name('dashboard');


// --- RUTAS PROTEGIDAS POR ROLES ---

Route::get('/mecanico', function () {
    return view('mecanico');
})->middleware(['auth', 'rol:mecanico'])->name('mecanico');

Route::get('/gerente', function () {
    return view('gerente');
})->middleware(['auth', 'rol:gerente'])->name('gerente');

Route::get('/administrador', function () {
    return view('administrador');
})->middleware(['auth', 'rol:administrador'])->name('administrador');

// Historial e Insumos para Mecánicos
Route::get('/mecanico/historial', function () {
    return view('mecanico_historial');
})->middleware(['auth', 'rol:mecanico']);

Route::get('/mecanico/insumos', function () {
    return view('mecanico_insumos');
})->middleware(['auth', 'rol:mecanico']);


// --- CIERRE DE SESIÓN ---
Route::get('/salir', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// --- RUTAS TEMPORALES PARA CREAR USUARIOS (Solo para desarrollo) ---
Route::get('/crear-admin', function () {
    \App\Models\User::create(['name' => 'Johan', 'email' => 'admin@taller.com', 'password' => bcrypt('123456'), 'rol' => 'administrador']);
    return '¡Usuario Administrador creado!';
});

Route::get('/crear-mecanico', function () {
    \App\Models\User::create(['name' => 'hildemar', 'email' => 'mecanico@taller.com', 'password' => bcrypt('123456'), 'rol' => 'mecanico']);
    return '¡Usuario Mecánico creado!';
});

Route::get('/crear-gerente', function () {
    \App\Models\User::create(['name' => 'Jose', 'email' => 'gerente@taller.com', 'password' => bcrypt('123456'), 'rol' => 'gerente']);
    return '¡Usuario Gerente creado!';
});

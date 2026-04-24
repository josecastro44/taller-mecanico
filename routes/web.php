<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Aquí unimos tus controladores y los de tu compañero
use App\Http\Controllers\RepuestoController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\FinanzasController;
use App\Http\Controllers\MecanicoController; // <-- FIX 1: Controlador agregado

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
Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
Route::post('/servicios', [ServicioController::class, 'guardar'])->name('servicios.guardar');
Route::put('/servicios/{id}', [ServicioController::class, 'actualizar'])->name('servicios.actualizar');
Route::delete('/servicios/{id}', [ServicioController::class, 'eliminar'])->name('servicios.eliminar');

// Módulo: Repuestos (Tus cambios)
Route::get('/repuestos', [RepuestoController::class, 'index'])->name('repuestos.index');
Route::put('/repuestos/{id}', [RepuestoController::class, 'update'])->name('repuestos.update');
Route::post('/repuestos', [RepuestoController::class, 'store'])->name('repuestos.store');
// Reportes de Inventario (PDFs)
Route::get('/repuestos/imprimir/{id}', [App\Http\Controllers\RepuestoController::class, 'imprimirIndividual'])->name('repuestos.imprimir');
Route::get('/repuestos/reporte', [App\Http\Controllers\RepuestoController::class, 'imprimirGeneral'])->name('repuestos.reporte');


// Módulo: Empleados
Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
Route::post('/empleados', [EmpleadoController::class, 'guardar'])->name('empleados.guardar');
Route::put('/empleados/{id}', [EmpleadoController::class, 'actualizar'])->name('empleados.actualizar');
Route::delete('/empleados/{id}', [EmpleadoController::class, 'eliminar'])->name('empleados.eliminar');

// Módulo: ventas
Route::get('/ventas', [VentaController::class, 'index'])->name('ventas');
Route::post('/ventas', [VentaController::class, 'guardar'])->name('ventas.guardar');
// Reportes de Ventas
Route::get('/ventas/imprimir/{id}', [App\Http\Controllers\VentaController::class, 'imprimirTicket'])->name('ventas.imprimir');
Route::get('/ventas/reporte', [App\Http\Controllers\VentaController::class, 'imprimirReporte'])->name('ventas.reporte');


// Módulo: Compras
Route::get('/compras', [CompraController::class, 'index'])->name('compras');
Route::post('/compras', [CompraController::class, 'guardar'])->name('compras.guardar');
Route::post('/compras/{id}/recibir', [CompraController::class, 'marcarRecibido'])->name('compras.recibir');


// Módulo: Proveedores
Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores');
Route::post('/proveedores', [ProveedorController::class, 'guardar'])->name('proveedores.guardar');
Route::put('/proveedores/{id}', [ProveedorController::class, 'actualizar'])->name('proveedores.actualizar');
Route::delete('/proveedores/{id}', [ProveedorController::class, 'eliminar'])->name('proveedores.eliminar');


// Módulo: Finanzas
Route::get('/finanzas', [FinanzasController::class, 'index'])->name('finanzas');
Route::post('/finanzas/facturar', [FinanzasController::class, 'guardar'])->name('finanzas.guardar');
Route::get('/finanzas/cobrar/{id}', [App\Http\Controllers\FinanzasController::class, 'prepararCobro'])->name('finanzas.preparar');

// Rutas de Impresión PDF
Route::get('/finanzas/imprimir/{id}', [App\Http\Controllers\FinanzasController::class, 'imprimirFactura'])->name('finanzas.imprimir');
Route::get('/finanzas/libro', [App\Http\Controllers\FinanzasController::class, 'imprimirLibro'])->name('finanzas.libro');

// Monitor de Taller
Route::get('/monitor', [RecepcionController::class, 'monitor'])->name('monitor');


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

// FIX 2: Ruta del Mecánico combinada (Lógica del Controlador + Protección Middleware)
Route::get('/mecanico', [MecanicoController::class, 'index'])->middleware(['auth', 'rol:mecanico'])->name('mecanico');
Route::post('/mecanico/orden/{id}/estado/{estado}', [MecanicoController::class, 'cambiarEstado'])->name('mecanico.estado');
Route::post('/mecanico/orden/{id}/repuesto', [MecanicoController::class, 'agregarRepuesto'])->name('mecanico.repuesto');

Route::get('/gerente', function () {
    return view('gerente');
})->middleware(['auth', 'rol:gerente'])->name('gerente');

Route::get('/administrador', function () {
    return view('administrador');
})->middleware(['auth', 'rol:administrador'])->name('administrador');


// Historial para Mecánicos
Route::get('/mecanico/historial', [MecanicoController::class, 'historial'])->middleware(['auth', 'rol:mecanico'])->name('mecanico.historial');

// Historial e Insumos para Mecánicos
Route::get('/mecanico/insumos', [MecanicoController::class, 'insumos'])->middleware(['auth', 'rol:mecanico'])->name('mecanico.insumos');


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
    \App\Models\User::create(['name' => 'José', 'email' => 'gerente@taller.com', 'password' => bcrypt('123456'), 'rol' => 'gerente']);
    return '¡Usuario Gerente creado!';
});

Route::get('/instalar-db', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh');
    return '¡Base de datos construida con éxito en la nube!';
});
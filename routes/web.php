<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\EmpleadoController; // Pon esto arriba del todo con los otros "use"
use App\Http\Controllers\ServicioController; // Verifica que esta línea esté arriba


// ==========================================
// RUTAS PÚBLICAS (Cualquier usuario puede verlas)
// ==========================================

// Landing Page (Página de inicio para clientes)
Route::get('/', function () {
    return view('welcome');
});


Route::get('/catalogo', [ServicioController::class, 'index']);

// Pantalla de Login para el personal del taller
Route::get('/login', function() {
    return view('login');
});



Route::post('/login', function (Request $request) {
    // 1. Validar que enviaron datos
    $credenciales = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // 2. Intentar iniciar sesión de verdad
    if (Auth::attempt($credenciales)) {
        $request->session()->regenerate();
        
        // 3. Si es correcto, lo mandamos al distribuidor de tráfico
        return redirect()->route('dashboard');
    }

    // 4. Si se equivoca, lo devolvemos con un error
    return back()->withErrors([
        'email' => 'El correo o la contraseña son incorrectos.',
    ]);
})->name('login.post');


// ==========================================
// RUTAS DEL SISTEMA (Módulos Internos)
// ==========================================

// Dashboard principal (Muestra las estadísticas y tarjetas)
Route::get('/inicio', function () {
    return view('inicio');
});

// Módulo 1: Recepción (Formulario para registrar clientes y vehículos)
Route::get('/recepcion', [RecepcionController::class, 'index'])->name('recepcion.index');
Route::post('/recepcion', [RecepcionController::class, 'guardar'])->name('recepcion.guardar');



// Módulo de Servicios
Route::get('/servicios', function () {
    return view('servicios');
})->name('servicios.index');

// Ruta para la pantalla de Repuestos e Insumos
Route::get('/repuestos', function () {
    return view('repuestos');
})->name('repuestos');




// Borra la ruta vieja de /empleados y pon estas dos:
Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
Route::post('/empleados', [EmpleadoController::class, 'guardar'])->name('empleados.guardar');

Route::get('/ventas', function () {
    return view('ventas');
})->name('ventas');

Route::get('/compras', function () {
    return view('compras');
})->name('compras');

Route::get('/proveedores', function () {
    return view('proveedores');
})->name('proveedores');

Route::get('/finanzas', function () {
    return view('finanzas');
})->name('finanzas');

Route::get('/mecanico', function () {
    return view('mecanico');
})->name('mecanico');

Route::post('/logout', function (Illuminate\Http\Request $request) {
    Illuminate\Support\Facades\Auth::logout(); 
    $request->session()->invalidate(); 
    $request->session()->regenerateToken(); 

    
    return redirect('/'); 
})->name('logout');






// --- DISTRIBUIDOR DE TRÁFICO (Al iniciar sesión, Laravel los manda aquí primero) ---
Route::get('/dashboard', function () {
    $rol = Auth::user()->rol;

    if ($rol === 'gerente') {
        return redirect()->route('gerente');
    } elseif ($rol === 'administrador') {
        return redirect()->route('administrador');
    } elseif ($rol === 'mecanico') {
        return redirect()->route('mecanico');
    }

    return redirect('/'); // Por si acaso no tiene rol asignado
})->middleware(['auth'])->name('dashboard');


// --- RUTAS PROTEGIDAS POR ROLES ---

// 1. Ruta del Mecánico (Solo mecánicos)
Route::get('/mecanico', function () {
    return view('mecanico'); 
})->middleware(['auth', 'rol:mecanico'])->name('mecanico');

// 2. Ruta del Gerente (Solo gerentes)
Route::get('/gerente', function () {
    return view('gerente'); // Pronto crearemos esta vista
})->middleware(['auth', 'rol:gerente'])->name('gerente');

// 3. Ruta del Administrador (Solo administradores)
Route::get('/administrador', function () {
    return view('administrador'); // Pronto crearemos esta vista
})->middleware(['auth', 'rol:administrador'])->name('administrador');

Route::get('/crear-admin', function () {
    \App\Models\User::create([
        'name' => 'Johan',
        'email' => 'admin@taller.com',
        'password' => bcrypt('123456'), // Clave encriptada
        'rol' => 'administrador'
    ]);
    return '¡Usuario Administrador creado con éxito!';
});

// --- RUTA PARA CREAR AL MECÁNICO ---
Route::get('/crear-mecanico', function () {
    \App\Models\User::create([
        'name' => 'hildemar', // Puedes poner el nombre que quieras
        'email' => 'mecanico@taller.com',
        'password' => bcrypt('123456'), // Clave encriptada
        'rol' => 'mecanico'
    ]);
    return '¡Usuario Mecánico creado con éxito!';
});

// Ruta del Historial del Mecánico (Solo mecánicos)
Route::get('/mecanico/historial', function () {
    return view('mecanico_historial'); 
})->middleware(['auth', 'rol:mecanico']);

// Ruta de Repuestos/Insumos (Solo mecánicos)
Route::get('/mecanico/insumos', function () {
    return view('mecanico_insumos'); 
})->middleware(['auth', 'rol:mecanico']);

// --- RUTA PARA CERRAR SESIÓN CORRECTAMENTE ---


Route::get('/salir', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login'); // Te manda de vuelta al login
})->name('logout');

Route::get('/crear-gerente', function () {
    \App\Models\User::create([
        'name' => 'Jose', 
        'email' => 'gerente@taller.com',
        'password' => bcrypt('123456'),
        'rol' => 'gerente'
    ]);
    return '¡Usuario Gerente creado con éxito!';
});
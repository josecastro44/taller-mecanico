<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $rol)
    {
        // 1. Si no ha iniciado sesión, al login
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. ¡LA LLAVE MAESTRA! Si el usuario es el gerente, déjalo pasar a TODOS LADOS
        if (Auth::user()->rol === 'gerente') {
            return $next($request);
        }

        // 3. Verificación normal para los demás (mecanico, administrador)
        if (Auth::user()->rol !== $rol) {
            abort(403, 'ACCESO DENEGADO. NO TIENES EL ROL NECESARIO PARA ENTRAR AQUÍ.');
        }

        return $next($request);
    }
}
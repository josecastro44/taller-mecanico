<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VentasController extends Controller
{
    /**
     * Store a new venta: valida y devuelve los datos (no persiste en BD).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket' => ['required', 'string', 'max:50'],
            'cliente' => ['required', 'string', 'max:255'],
            'articulo' => ['nullable', 'string', 'max:255'],
            'total' => ['required', 'numeric', 'min:0'],
            'metodo' => ['required', 'in:efectivo,tarjeta,transferencia'],
            'acciones' => ['nullable', 'string', 'max:1000'],
        ]);

        // No persistimos en la base de datos — devolvemos los datos validados.
        return response()->json([
            'success' => true,
            'venta' => $validated,
        ]);
    }
}

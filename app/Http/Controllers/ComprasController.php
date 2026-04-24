<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ComprasController extends Controller
{
    /**
     * Store a new compra: valida los campos según las reglas solicitadas.
     * - `fecha` no puede ser futura salvo si `estado` es 'pendiente' o 'en_transito'
     * - `orden` no puede repetirse si existe la tabla `compras`
     * - campos requeridos no deben estar vacíos
     */
    public function store(Request $request)
    {
        $estado = $request->input('estado');

        $dateRules = ['required', 'date'];
        if (!in_array($estado, ['pendiente', 'en_transito'])) {
            $dateRules[] = 'before_or_equal:today';
        }

        $rules = [
            'orden' => ['required', 'string', 'max:50'],
            'proveedor' => ['required', 'string', 'max:255'],
            'fecha' => $dateRules,
            'monto' => ['required', 'numeric', 'min:0'],
            'estado' => ['required', 'in:recibido,en_transito,pendiente'],
            'acciones' => ['nullable', 'string', 'max:1000'],
        ];

        $validated = $request->validate($rules);

        // Si la tabla existe, aseguramos unicidad de 'orden'
        if (Schema::hasTable('compras')) {
            $exists = DB::table('compras')->where('orden', $validated['orden'])->exists();
            if ($exists) {
                throw ValidationException::withMessages(['orden' => ['El número de orden ya existe.']]);
            }
        }

        // No persistimos por ahora, devolvemos los datos validados
        return response()->json([
            'success' => true,
            'compra' => $validated,
        ]);
    }
}

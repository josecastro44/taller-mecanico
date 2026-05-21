<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GastoOperativo;
use App\Models\CategoriaGasto;
use App\Services\ContabilidadService;
use Carbon\Carbon;

class GastoOperativoController extends Controller
{
    public function index()
    {
        $gastos = GastoOperativo::with('categoria')->orderBy('created_at', 'desc')->paginate(15);
        $categorias = CategoriaGasto::activas()->get();

        // KPIs
        $inicioMes = Carbon::now()->startOfMonth();
        $gastosMes = GastoOperativo::where('created_at', '>=', $inicioMes)->sum('monto');
        $gastosPendientes = GastoOperativo::pendientes()->count();
        $gastosVencidos = GastoOperativo::vencidos()->count();

        // Próximos vencimientos (5 días)
        $proximosVencimientos = GastoOperativo::where('estado', 'pendiente')
            ->whereBetween('prox_vencimiento', [Carbon::today(), Carbon::today()->addDays(5)])
            ->with('categoria')
            ->orderBy('prox_vencimiento')
            ->get();

        // Gastos por categoría (para gráfico)
        $gastosPorCategoria = GastoOperativo::where('created_at', '>=', $inicioMes)
            ->selectRaw('categoria_gasto_id, SUM(monto) as total')
            ->groupBy('categoria_gasto_id')
            ->with('categoria')
            ->get();

        return view('gastos', compact(
            'gastos', 'categorias', 'gastosMes', 'gastosPendientes',
            'gastosVencidos', 'proximosVencimientos', 'gastosPorCategoria'
        ));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'categoria_gasto_id' => 'required|exists:categorias_gasto,id',
            'descripcion'        => 'required|string|max:255',
            'monto'              => ['required', 'numeric', 'min:0.01', 'regex:/^\d+(\.\d{1,2})?$/'],
            'frecuencia'         => 'required|in:semanal,quincenal,mensual,unico',
            'prox_vencimiento'   => 'nullable|date',
            'observaciones'      => 'nullable|string|max:500',
        ]);

        $gasto = GastoOperativo::create([
            'categoria_gasto_id' => $request->categoria_gasto_id,
            'descripcion'        => $request->descripcion,
            'monto'              => round((float) $request->monto, 2),
            'frecuencia'         => $request->frecuencia,
            'prox_vencimiento'   => $request->prox_vencimiento,
            'estado'             => 'pendiente',
            'observaciones'      => $request->observaciones,
        ]);

        return back()->with('exito', '¡Gasto operativo "' . $gasto->descripcion . '" registrado exitosamente!');
    }

    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'categoria_gasto_id' => 'required|exists:categorias_gasto,id',
            'descripcion'        => 'required|string|max:255',
            'monto'              => ['required', 'numeric', 'min:0.01', 'regex:/^\d+(\.\d{1,2})?$/'],
            'frecuencia'         => 'required|in:semanal,quincenal,mensual,unico',
            'prox_vencimiento'   => 'nullable|date',
            'observaciones'      => 'nullable|string|max:500',
        ]);

        $gasto = GastoOperativo::findOrFail($id);
        $gasto->update([
            'categoria_gasto_id' => $request->categoria_gasto_id,
            'descripcion'        => $request->descripcion,
            'monto'              => round((float) $request->monto, 2),
            'frecuencia'         => $request->frecuencia,
            'prox_vencimiento'   => $request->prox_vencimiento,
            'observaciones'      => $request->observaciones,
        ]);

        return back()->with('exito', '¡Gasto actualizado correctamente!');
    }

    public function eliminar($id)
    {
        $gasto = GastoOperativo::findOrFail($id);
        $nombre = $gasto->descripcion;
        $gasto->delete();

        return back()->with('exito', '¡Gasto "' . $nombre . '" eliminado!');
    }

    public function marcarPagado(Request $request, $id)
    {
        $request->validate([
            'metodo_pago'     => 'required|string',
            'referencia_pago' => 'nullable|string|max:100',
        ]);

        $gasto = GastoOperativo::findOrFail($id);
        $gasto->estado = 'pagado';
        $gasto->fecha_pago = Carbon::today();
        $gasto->metodo_pago = $request->metodo_pago;
        $gasto->referencia_pago = $request->referencia_pago;

        // Calcular próximo vencimiento si es recurrente
        $proximoVencimiento = $gasto->calcularProximoVencimiento();

        $gasto->save();

        // Registrar asiento contable de egreso
        ContabilidadService::registrarEgreso(
            $gasto->monto,
            'Gasto Operativo: ' . $gasto->descripcion,
            $gasto->referencia_pago,
            'gasto_operativo',
            GastoOperativo::class,
            $gasto->id
        );

        return back()->with('exito', '¡Pago de $' . number_format($gasto->monto, 2) . ' registrado!');
    }

    public function guardarCategoria(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:categorias_gasto,nombre',
            'icono'  => 'nullable|string|max:50',
            'color'  => 'nullable|string|max:10',
        ]);

        CategoriaGasto::create([
            'nombre' => $request->nombre,
            'icono'  => $request->icono ?? 'ph ph-receipt',
            'color'  => $request->color ?? '#728495',
            'estado' => 'activo'
        ]);

        return back()->with('exito', '¡Categoría "' . $request->nombre . '" creada exitosamente!');
    }
}

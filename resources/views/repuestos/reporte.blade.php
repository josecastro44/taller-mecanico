<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Existencias - {{ date('d/m/Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body class="bg-white p-8">

    {{-- Botones de acción (No se imprimen) --}}
    <div class="no-print mb-8 flex gap-4">
        <button onclick="window.print()" class="bg-[#263A47] text-white px-6 py-2 rounded-lg font-bold">Imprimir / Guardar PDF</button>
        <button onclick="window.close()" class="text-gray-500 font-bold">Cerrar</button>
    </div>

    {{-- Encabezado del Reporte --}}
    <div class="flex justify-between items-center border-b-2 border-[#263A47] pb-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#263A47] italic uppercase tracking-tighter">AutoSys - Inventario</h1>
            <p class="text-sm text-gray-500">Reporte Maestro de Existencias</p>
        </div>
        <div class="text-right">
            <p class="font-bold text-[#263A47]">Fecha: {{ date('d/m/Y') }}</p>
            <p class="text-xs text-gray-500">Barquisimeto, Venezuela</p>
        </div>
    </div>

    {{-- Tabla de Datos --}}
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 border-b border-gray-300">
                <th class="py-3 px-2 text-xs font-black uppercase text-[#263A47]">SKU</th>
                <th class="py-3 px-2 text-xs font-black uppercase text-[#263A47]">Descripción del Repuesto</th>
                <th class="py-3 px-2 text-center text-xs font-black uppercase text-[#263A47]">Stock</th>
                <th class="py-3 px-2 text-right text-xs font-black uppercase text-[#263A47]">Costo Unit.</th>
                <th class="py-3 px-2 text-right text-xs font-black uppercase text-[#263A47]">Total Costo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($repuestos as $r)
            <tr class="border-b border-gray-200">
                <td class="py-3 px-2 text-sm">REP-{{ str_pad($r->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td class="py-3 px-2 text-sm font-bold">{{ $r->nombre }}</td>
                <td class="py-3 px-2 text-center text-sm {{ $r->stock <= 5 ? 'text-red-600 font-black' : '' }}">{{ $r->stock }}</td>
                <td class="py-3 px-2 text-right text-sm">$ {{ number_format($r->costo_adquisicion, 2) }}</td>
                <td class="py-3 px-2 text-right text-sm font-bold">$ {{ number_format($r->costo_adquisicion * $r->stock, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-gray-50 font-black">
                <td colspan="4" class="py-4 px-2 text-right uppercase text-xs">Valor Total del Inventario:</td>
                <td class="py-4 px-2 text-right text-lg text-[#263A47]">$ {{ number_format($repuestos->sum(fn($r) => $r->costo_adquisicion * $r->stock), 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-12 text-center text-[10px] text-gray-400">
        Este documento es un reporte generado automáticamente por el sistema AutoSys.
    </div>

</body>
</html>

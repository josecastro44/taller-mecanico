@extends('layouts.app')

@section('contenido')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ENCABEZADO Y ACCIONES --}}
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-[#263A47] sm:text-3xl sm:truncate italic tracking-tight">
                Sistema de Inventario
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-[#728495]">
                    <i class="ph ph-briefcase-metal mr-1.5 text-xl"></i>
                    Repuestos e Insumos
                </div>
                <div class="mt-2 flex items-center text-sm text-[#728495]">
                    <i class="ph ph-map-pin mr-1.5 text-xl"></i>
                    Almacén Central
                </div>
            </div>
        </div>

        {{-- Grupo de Botones Alineados --}}
        <div class="mt-5 flex lg:mt-0 lg:ml-4 gap-3 items-center">
            {{-- Botón de Reporte --}}
            <a href="{{ route('repuestos.reporte') }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-[#98A9BE]/30 rounded-lg shadow-sm text-sm font-bold text-[#263A47] bg-white hover:bg-gray-50 transition-all">
                <i class="ph ph-printer mr-2 text-lg"></i>
                Imprimir Reporte
            </a>

            {{-- Botón de Nuevo Repuesto --}}
            <button type="button" onclick="toggleForm()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-[#263A47] hover:bg-[#3d5a6f] focus:outline-none transition-all">
                <i class="ph ph-plus-circle mr-2 text-lg"></i>
                Nuevo Repuesto
            </button>
        </div>
    </div>

    {{-- TARJETAS DE ESTADÍSTICAS --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#98A9BE]/30 p-5">
            <dt class="text-sm font-medium text-[#728495] truncate">Total Productos</dt>
            <dd class="mt-1 text-3xl font-semibold text-[#263A47]">{{ $repuestos->count() }}</dd>
        </div>
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#98A9BE]/30 p-5">
            <dt class="text-sm font-medium text-[#728495] truncate">Valor del Inventario</dt>
            <dd class="mt-1 text-3xl font-semibold text-green-600">$ {{ number_format($repuestos->sum(fn($r) => $r->costo_adquisicion * $r->stock), 2) }}</dd>
        </div>
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-[#98A9BE]/30 p-5 border-l-4 border-l-red-500">
            <dt class="text-sm font-medium text-[#728495] truncate">Alertas de Stock Bajo</dt>
            <dd class="mt-1 text-3xl font-semibold text-red-600">{{ $repuestos->where('stock', '<=', 5)->count() }}</dd>
        </div>
    </div>

    {{-- FORMULARIO DE REGISTRO (OCULTO POR DEFECTO) --}}
    <div id="panel-registro" class="hidden mb-8 transition-all duration-500">
        <div class="bg-[#263A47] rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white uppercase tracking-widest text-sm">Formulario de Entrada</h3>
                <button onclick="toggleForm()" class="text-white/50 hover:text-white"><i class="ph ph-x text-xl"></i></button>
            </div>
            <div class="p-6 bg-white">
                <form action="{{ route('repuestos.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs font-black text-[#263A47] uppercase mb-1">Descripción de la Pieza</label>
                            <input type="text" name="nombre" class="w-full bg-gray-50 border border-[#B4C5D8] text-[#263A47] text-sm rounded-lg p-2.5 focus:ring-[#263A47] focus:border-[#263A47]" placeholder="Ej: Kit de Embrague LUK" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-[#263A47] uppercase mb-1">Costo Compra ($)</label>
                            <input type="number" step="0.01" name="costo_adquisicion" class="w-full bg-gray-50 border border-[#B4C5D8] text-[#263A47] text-sm rounded-lg p-2.5" placeholder="0.00" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-[#263A47] uppercase mb-1">Precio Venta ($)</label>
                            <input type="number" step="0.01" name="precio_venta" class="w-full bg-gray-50 border border-[#B4C5D8] text-[#263A47] text-sm rounded-lg p-2.5" placeholder="0.00" required>
                        </div>
                        <div class="md:col-start-4">
                            <label class="block text-xs font-black text-[#263A47] uppercase mb-1">Stock Inicial</label>
                            <input type="number" name="stock" class="w-full bg-gray-50 border border-[#B4C5D8] text-[#263A47] text-sm rounded-lg p-2.5" placeholder="0" required>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="toggleForm()" class="px-5 py-2 text-sm font-bold text-[#728495] hover:text-[#263A47]">Cancelar</button>
                        <button type="submit" class="bg-[#263A47] text-white px-8 py-2 rounded-lg font-bold text-sm shadow-lg hover:bg-black transition-all">
                            Confirmar Registro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- TABLA DE PRODUCTOS CON BUSCADOR --}}
    <div class="bg-white shadow-xl rounded-2xl border border-[#98A9BE]/30 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-[#263A47]">Listado Maestro</h3>
            <div class="relative w-64">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <i class="ph ph-magnifying-glass text-[#728495]"></i>
                </span>
                <input type="text" id="buscadorRepuestos" onkeyup="filtrarTabla()" class="block w-full pl-10 pr-3 py-2 border border-[#B4C5D8] rounded-md leading-5 bg-gray-50 text-sm placeholder-[#728495] focus:outline-none focus:ring-1 focus:ring-[#263A47]" placeholder="Buscar por nombre o SKU...">
            </div>
        </div>
        <table class="min-w-full divide-y divide-gray-200" id="tablaMaestra">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-black text-[#728495] uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-3 text-center text-xs font-black text-[#728495] uppercase tracking-wider">Estado Stock</th>
                    <th class="px-6 py-3 text-right text-xs font-black text-[#728495] uppercase tracking-wider">Costo Adq.</th>
                    <th class="px-6 py-3 text-right text-xs font-black text-[#728495] uppercase tracking-wider">Precio PVP</th>
                    <th class="px-6 py-3 text-center text-xs font-black text-[#728495] uppercase tracking-wider">Margen</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100" id="cuerpoTabla">
                @forelse($repuestos as $repuesto)
                <tr class="hover:bg-blue-50/30 transition-colors fila-repuesto">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-[#263A47] uppercase nombre-producto">{{ $repuesto->nombre }}</div>
                        <div class="text-xs text-[#728495] sku-producto">SKU: REP-{{ str_pad($repuesto->id, 4, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $repuesto->stock <= 5 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $repuesto->stock }} unidades
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-[#728495]">
                        $ {{ number_format($repuesto->costo_adquisicion, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-black text-[#263A47]">
                        $ {{ number_format($repuesto->precio_venta, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-green-600">
                        {{ number_format((($repuesto->precio_venta - $repuesto->costo_adquisicion) / $repuesto->precio_venta) * 100, 1) }}%
                    </td>
                </tr>
                @empty
                <tr id="fila-vacia">
                    <td colspan="5" class="px-6 py-12 text-center text-[#728495] italic">Base de datos de inventario vacía.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleForm() {
        const panel = document.getElementById('panel-registro');
        panel.classList.toggle('hidden');
    }

    function filtrarTabla() {
        const input = document.getElementById("buscadorRepuestos");
        const filtro = input.value.toUpperCase();
        const filas = document.getElementsByClassName("fila-repuesto");

        for (let i = 0; i < filas.length; i++) {
            const nombre = filas[i].querySelector(".nombre-producto").innerText;
            const sku = filas[i].querySelector(".sku-producto").innerText;

            if (nombre.toUpperCase().includes(filtro) || sku.toUpperCase().includes(filtro)) {
                filas[i].style.display = "";
            } else {
                filas[i].style.display = "none";
            }
        }
    }
</script>
@endsection

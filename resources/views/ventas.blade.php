@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Gestión de Ventas (Mostrador)</h2>
            <p class="text-[#728495]">Registro de ventas directas de repuestos e insumos al detal</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('ventas.reporte') }}" target="_blank" class="bg-white border-2 border-[#263A47] text-[#263A47] px-4 py-2.5 rounded-lg hover:bg-gray-50 shadow-sm transition-all flex items-center gap-2 font-bold">
                <i class="ph ph-files text-xl"></i> Cierre de Ventas
            </a>
            <button onclick="abrirModalVenta()" class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
                <i class="ph ph-shopping-cart text-xl"></i> Nueva Venta Rápida
            </button>
        </div>
    </div>

    @if(session('exito'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
            <p class="font-bold">¡Excelente!</p>
            <p>{{ session('exito') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm">
            <p class="font-bold">Error de Inventario</p>
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    {{-- DASHBOARD DINÁMICO --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-l-4 border-l-green-500 flex items-center justify-between">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Ventas del Día</p>
                <p class="text-3xl font-bold text-[#263A47]">$ {{ number_format($ventasDelDia, 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="ph ph-currency-dollar text-2xl text-green-500"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-l-4 border-l-[#4A5B6A] flex items-center justify-between">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Artículos Vendidos Hoy</p>
                <p class="text-3xl font-bold text-[#263A47]">{{ $articulosVendidos }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-[#B4C5D8]/30 flex items-center justify-center">
                <i class="ph ph-package text-2xl text-[#4A5B6A]"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-l-4 border-l-[#728495] flex items-center justify-between">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Ticket Promedio</p>
                <p class="text-3xl font-bold text-[#263A47]">$ {{ number_format($ticketPromedio, 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-[#B4C5D8]/30 flex items-center justify-center">
                <i class="ph ph-receipt text-2xl text-[#4A5B6A]"></i>
            </div>
        </div>
    </div>

    {{-- TABLA Y BUSCADOR --}}
    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-[#B4C5D8] bg-[#B4C5D8]/10 flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="text-lg font-bold text-[#263A47]">Historial de Ventas</h3>
            <form action="{{ route('ventas') }}" method="GET" class="relative w-full md:w-64">
                <i class="ph ph-magnifying-glass absolute left-3 top-2 text-[#728495]"></i>
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar ticket..." class="w-full pl-9 pr-4 py-1.5 border border-[#B4C5D8] rounded-md text-sm focus:outline-none focus:border-[#4A5B6A]">
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">N° Ticket</th>
                        <th class="px-6 py-4 font-bold">Cliente</th>
                        <th class="px-6 py-4 font-bold text-center">Artículos</th>
                        <th class="px-6 py-4 font-bold text-right">Total</th>
                        <th class="px-6 py-4 font-bold text-center">Método</th>
                        <th class="px-6 py-4 font-bold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    @forelse($ventas as $venta)
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold text-[#4A5B6A]">{{ $venta->numero_ticket }}</p>
                            <p class="text-xs text-[#728495]">{{ $venta->created_at->format('d/m/Y h:i A') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold">{{ $venta->cliente }}</p>
                            <p class="text-xs text-[#728495]">CI: {{ $venta->cedula ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">{{ $venta->detalles_count }}</td>
                        <td class="px-6 py-4 text-right font-bold text-green-700">$ {{ number_format($venta->total, 2) }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">{{ $venta->metodo_pago }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('ventas.imprimir', $venta->id) }}" target="_blank" class="text-[#4A5B6A] hover:text-[#263A47] bg-[#B4C5D8]/30 p-2 rounded-lg transition inline-block" title="Imprimir Ticket">
                                <i class="ph ph-printer text-xl"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-[#728495]">No hay ventas registradas aún.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ventas->hasPages())
            <div class="px-6 py-4 border-t border-[#B4C5D8]">
                {{ $ventas->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL NUEVA VENTA (RÁPIDA) CON DATOS COMPLETOS --}}
    <div id="modal-venta" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10">
                <h3 class="text-lg font-bold text-[#263A47]">Registrar Venta Rápida</h3>
                <button onclick="cerrarModalVenta()" class="text-[#728495] hover:text-red-500"><i class="ph ph-x text-2xl"></i></button>
            </div>
            <form action="{{ route('ventas.guardar') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    {{-- Fila 1: Cliente --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Nombre del Cliente *</label>
                        <input type="text" name="cliente" required placeholder="Ej. Juan Pérez" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                    </div>
                    
                    {{-- Fila 2: CI y Teléfono --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Cédula / RIF *</label>
                            <input type="text" name="cedula" required placeholder="V-12345678" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Teléfono *</label>
                            <input type="text" name="telefono" required placeholder="0414-0000000" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                        </div>
                    </div>

                    {{-- Fila 3: Producto --}}
                    <div class="pt-2 border-t border-gray-200">
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Seleccionar Repuesto *</label>
                        <select name="repuesto_id" required class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                            <option value="">Seleccione un producto en stock...</option>
                            @foreach($repuestos as $repuesto)
                               <option value="{{ $repuesto->id }}">{{ $repuesto->nombre }} (${{ number_format($repuesto->precio ?? $repuesto->precio_venta ?? 0, 2) }}) - Stock: {{ $repuesto->stock }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Fila 4: Cantidad y Pago --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Cantidad *</label>
                            <input type="number" name="cantidad" required min="1" value="1" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Método de Pago *</label>
                            <select name="metodo_pago" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                                <option value="Efectivo">Efectivo ($)</option>
                                <option value="Punto de Venta">Punto de Venta (Bs)</option>
                                <option value="Pago Móvil">Pago Móvil</option>
                                <option value="Zelle">Zelle</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="cerrarModalVenta()" class="px-5 py-2 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#B4C5D8]/20">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-[#263A47] text-white font-semibold rounded-lg hover:bg-[#4A5B6A]">Procesar Pago</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalVenta() { document.getElementById('modal-venta').classList.remove('hidden'); }
        function cerrarModalVenta() { document.getElementById('modal-venta').classList.add('hidden'); }
    </script>
@endsection
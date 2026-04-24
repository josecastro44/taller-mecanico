@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Órdenes de Compra (Reabastecimiento)</h2>
            <p class="text-[#728495]">Ingreso de mercancía e insumos al inventario del taller</p>
        </div>
        <button onclick="abrirModalCompra()" class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
            <i class="ph ph-bag text-xl"></i> Nueva Compra
        </button>
    </div>

    @if(session('exito'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
            <p class="font-bold">¡Excelente!</p>
            <p>{{ session('exito') }}</p>
        </div>
    @endif

    {{-- TARJETAS DINÁMICAS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-[#263A47]">
            <p class="text-sm text-[#728495] font-medium mb-1">Inversión del Mes</p>
            <p class="text-3xl font-bold text-[#263A47]">$ {{ number_format($inversionMes, 2) }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-yellow-500">
            <p class="text-sm text-[#728495] font-medium mb-1">Pedidos en Tránsito</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $pedidosTransito }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-red-500">
            <p class="text-sm text-[#728495] font-medium mb-1">Cuentas por Pagar</p>
            <p class="text-3xl font-bold text-red-600">$ {{ number_format($cuentasPorPagar, 2) }}</p>
        </div>
    </div>

    {{-- TABLA DE COMPRAS --}}
    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#B4C5D8]/20 border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">N° Orden</th>
                        <th class="px-6 py-4 font-bold">Proveedor</th>
                        <th class="px-6 py-4 font-bold">Fecha</th>
                        <th class="px-6 py-4 font-bold text-right">Monto Total</th>
                        <th class="px-6 py-4 font-bold text-center">Estado</th>
                        <th class="px-6 py-4 font-bold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    @forelse($compras as $compra)
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-bold">{{ $compra->numero_orden }}</td>
                        <td class="px-6 py-4">{{ $compra->proveedor->nombre }}</td>
                        <td class="px-6 py-4 text-[#728495]">{{ $compra->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-right font-bold text-red-600">$ {{ number_format($compra->total, 2) }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($compra->estado === 'Recibido')
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Recibido</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">En Tránsito</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center flex justify-center gap-2">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] transition-colors" title="Ver Detalles"><i class="ph ph-eye text-xl"></i></button>
                            
                            @if($compra->estado === 'En Tránsito')
                                <form action="{{ route('compras.recibir', $compra->id) }}" method="POST" onsubmit="return confirm('¿Confirmas que la mercancía llegó al taller? Esto sumará el stock al inventario.');">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800 transition-colors" title="Marcar Recibido">
                                        <i class="ph ph-check-circle text-xl"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-[#728495]">No hay órdenes de compra registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($compras->hasPages())
            <div class="px-6 py-4 border-t border-[#B4C5D8]">
                {{ $compras->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL NUEVA COMPRA --}}
    <div id="modal-compra" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10">
                <h3 class="text-lg font-bold text-[#263A47]">Registrar Nueva Compra</h3>
                <button onclick="cerrarModalCompra()" class="text-[#728495] hover:text-red-500"><i class="ph ph-x text-2xl"></i></button>
            </div>
            <form action="{{ route('compras.guardar') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Nombre del Proveedor *</label>
                        <input type="text" name="proveedor" required placeholder="Ej. Autopartes Venezuela C.A." class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Repuesto a Comprar *</label>
                        <select name="repuesto_id" required class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                            <option value="">Seleccione el repuesto...</option>
                            @foreach($repuestos as $repuesto)
                                <option value="{{ $repuesto->id }}">{{ $repuesto->nombre }} (Stock actual: {{ $repuesto->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Cantidad *</label>
                            <input type="number" name="cantidad" required min="1" value="1" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Costo Unitario ($) *</label>
                            <input type="number" name="costo_unitario" step="0.01" required placeholder="0.00" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Estado de la Orden *</label>
                        <select name="estado" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                            <option value="En Tránsito">En Tránsito (Aún no llega)</option>
                            <option value="Recibido">Recibido (Sumar al stock ya)</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="cerrarModalCompra()" class="px-5 py-2 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#B4C5D8]/20">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-[#263A47] text-white font-semibold rounded-lg hover:bg-[#4A5B6A]">Generar Orden</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalCompra() { document.getElementById('modal-compra').classList.remove('hidden'); }
        function cerrarModalCompra() { document.getElementById('modal-compra').classList.add('hidden'); }
    </script>
@endsection
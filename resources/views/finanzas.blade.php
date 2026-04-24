@extends('layouts.app')

@section('contenido')

    {{-- CABECERA CON TÍTULO Y BOTONES ALINEADOS --}}
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Facturación y Reportes Financieros</h2>
            <p class="text-[#728495]">Control de ingresos, egresos operativos, impuestos (IVA/IGTF) y utilidades</p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('finanzas.libro') }}" target="_blank" class="bg-white border-2 border-[#263A47] text-[#263A47] px-4 py-2.5 rounded-lg hover:bg-gray-50 shadow-sm transition-all flex items-center gap-2 font-bold">
                <i class="ph ph-files text-xl"></i> Libro de Ventas
            </a>
            <button onclick="abrirModalFactura()" class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
                <i class="ph ph-receipt text-xl"></i> Generar Factura
            </button>
        </div>
    </div>

    @if(session('exito'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
            <p class="font-bold">¡Excelente!</p>
            <p>{{ session('exito') }}</p>
        </div>
    @endif

    {{-- TARJETAS DINÁMICAS (MATEMÁTICA CONTABLE) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-green-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Ingresos Brutos (Mes)</p>
                <p class="text-3xl font-bold text-[#263A47]">$ {{ number_format($ingresosBrutos, 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="ph ph-trend-up text-2xl text-green-500"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-red-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Egresos Operativos</p>
                <p class="text-3xl font-bold text-[#263A47]">$ {{ number_format($egresosTotales, 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center">
                <i class="ph ph-trend-down text-2xl text-red-500"></i>
            </div>
        </div>

        {{-- La tarjeta de utilidad cambia de color si estás en pérdida --}}
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 {{ $utilidadNeta >= 0 ? 'border-l-blue-600' : 'border-l-red-600' }}">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Utilidad Neta (Ganancia Real)</p>
                <p class="text-3xl font-bold {{ $utilidadNeta >= 0 ? 'text-[#263A47]' : 'text-red-600' }}">
                    $ {{ number_format($utilidadNeta, 2) }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-lg {{ $utilidadNeta >= 0 ? 'bg-blue-50' : 'bg-red-50' }} flex items-center justify-center">
                <i class="ph ph-coins text-2xl {{ $utilidadNeta >= 0 ? 'text-blue-600' : 'text-red-600' }}"></i>
            </div>
        </div>
    </div>

    {{-- TABLA DE HISTORIAL DE FACTURACIÓN --}}
    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm flex flex-col">
        <div class="p-5 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10 rounded-t-xl">
            <h3 class="text-lg font-bold text-[#263A47]">Libro de Ventas (Historial de Facturación)</h3>
        </div>
        <div class="overflow-x-auto p-5">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-sm uppercase tracking-wider">
                        <th class="pb-3 font-bold">N° Factura / Ref.</th>
                        <th class="pb-3 font-bold text-right">Repuestos</th>
                        <th class="pb-3 font-bold text-right">Mano Obra</th>
                        <th class="pb-3 font-bold text-right">IVA / IGTF</th>
                        <th class="pb-3 font-bold text-right">Total Facturado</th>
                        <th class="pb-3 font-bold text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    @forelse($facturas as $factura)
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="py-4">
                            <p class="font-bold">{{ $factura->numero_factura }}</p>
                            <p class="text-xs text-[#728495]">{{ $factura->referencia }}</p>
                        </td>
                        <td class="py-4 text-right font-medium">$ {{ number_format($factura->subtotal_repuestos, 2) }}</td>
                        <td class="py-4 text-right font-medium">$ {{ number_format($factura->subtotal_mano_obra, 2) }}</td>
                        <td class="py-4 text-right font-medium text-red-500">
                            $ {{ number_format($factura->monto_iva + $factura->monto_igtf, 2) }}
                        </td>
                        <td class="py-4 text-right font-bold text-lg text-[#263A47]">$ {{ number_format($factura->total_facturado, 2) }}</td>
                        <td class="py-4 text-center">
                            <a href="{{ route('finanzas.imprimir', $factura->id) }}" target="_blank" class="text-[#4A5B6A] hover:text-[#263A47] bg-[#B4C5D8]/30 p-2 rounded-lg transition inline-block" title="Imprimir Comprobante">
                                <i class="ph ph-printer text-xl"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-[#728495]">No hay facturas emitidas este mes.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($facturas->hasPages())
            <div class="px-6 py-4 border-t border-[#B4C5D8]">
                {{ $facturas->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL GENERAR FACTURA --}}
    <div id="modal-factura" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10">
                <h3 class="text-lg font-bold text-[#263A47]">Emitir Factura Fiscal</h3>
                <button onclick="cerrarModalFactura()" class="text-[#728495] hover:text-red-500"><i class="ph ph-x text-2xl"></i></button>
            </div>
            <form action="{{ route('finanzas.guardar') }}" method="POST" class="p-6">
                @csrf
                {{-- Campo oculto para saber qué carro estamos entregando --}}
                <input type="hidden" name="orden_id" value="{{ session('orden_id') }}">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Referencia (Ej. Orden #0054) *</label>
                        <input type="text" name="referencia" value="{{ session('referencia') }}" required placeholder="N° Orden o Detalle" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Subtotal Repuestos ($) *</label>
                            <input type="number" step="0.01" name="subtotal_repuestos" value="{{ session('subtotal_repuestos', '0.00') }}" required min="0" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Subtotal Mano de Obra ($) *</label>
                            <input type="number" step="0.01" name="subtotal_mano_obra" value="{{ session('subtotal_mano_obra', '0.00') }}" required min="0" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                        </div>
                    </div>
                    
                    {{-- SECCIÓN DE IMPUESTOS --}}
                    <div class="mt-4 p-4 bg-[#B4C5D8]/10 rounded-lg border border-[#B4C5D8]">
                        <p class="text-sm font-bold text-[#4A5B6A] mb-2">Cálculo de Impuestos</p>
                        <div class="flex items-center gap-2 mb-2">
                            <i class="ph ph-check-circle text-green-500 text-lg"></i>
                            <span class="text-sm text-[#263A47]">IVA (16%) se calculará automáticamente.</span>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="aplica_igtf" value="1" class="w-4 h-4 text-[#263A47] border-[#B4C5D8] rounded focus:ring-[#263A47]">
                            <span class="text-sm font-medium text-[#263A47]">Aplicar IGTF (3%) por pago en divisas</span>
                        </label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="cerrarModalFactura()" class="px-5 py-2 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#B4C5D8]/20">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-[#263A47] text-white font-semibold rounded-lg hover:bg-[#4A5B6A]">Emitir Factura</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalFactura() { document.getElementById('modal-factura').classList.remove('hidden'); }
        function cerrarModalFactura() { document.getElementById('modal-factura').classList.add('hidden'); }

        // Si venimos del Monitor de Taller, abrir el modal automáticamente
        @if(session('abrir_modal'))
            window.onload = function() {
                abrirModalFactura();
            };
        @endif
    </script>

@endsection
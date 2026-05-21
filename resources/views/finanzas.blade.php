@extends('layouts.app')

@section('contenido')

    {{-- CABECERA CON TÍTULO, BCV Y BOTONES --}}
    <div class="mb-6 flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Facturación y Reportes Financieros</h2>
            <p class="text-[#728495] text-sm">Control de ingresos, egresos operativos, impuestos (IVA/IGTF) y utilidades</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            {{-- Widget de Tasa BCV --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl px-4 py-2.5 flex items-center gap-3 shadow-sm">
                <div>
                    <p class="text-[10px] text-blue-500 font-bold uppercase tracking-wider">Tasa BCV</p>
                    <p class="text-lg font-black text-blue-700 tabular-nums" id="tasa-bcv-display">
                        @if($tasaBcv['error'])
                            Sin datos
                        @else
                            Bs. {{ number_format($tasaBcv['precio'], 2) }}
                        @endif
                    </p>
                </div>
                <button onclick="refrescarTasaBcv()" class="btn-scale w-8 h-8 bg-blue-100 hover:bg-blue-200 rounded-lg flex items-center justify-center transition" title="Refrescar tasa">
                    <i class="ph ph-arrows-clockwise text-blue-600" id="icono-refrescar-bcv"></i>
                </button>
            </div>

            <a href="{{ route('finanzas.libro') }}" target="_blank" class="btn-scale bg-white border-2 border-[#263A47] text-[#263A47] px-4 py-2.5 rounded-lg hover:bg-gray-50 shadow-sm transition-all flex items-center gap-2 font-bold text-sm">
                <i class="ph ph-files text-lg"></i> Libro de Ventas
            </a>
            <button onclick="abrirModalFactura()" class="btn-scale bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all flex items-center gap-2 font-semibold text-sm">
                <i class="ph ph-receipt text-lg"></i> Generar Factura
            </button>
        </div>
    </div>

    @if(session('exito'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm flex items-center gap-3">
            <i class="ph ph-check-circle text-2xl text-green-500"></i>
            <div>
                <p class="font-bold text-sm">¡Excelente!</p>
                <p class="text-sm">{{ session('exito') }}</p>
            </div>
        </div>
    @endif

    {{-- TARJETAS DINÁMICAS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 border-l-green-500">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1">Ingresos Brutos (Mes)</p>
                <p class="text-2xl font-black text-[#263A47]">$ {{ number_format($ingresosBrutos, 2) }}</p>
                <p class="text-[11px] text-[#98A9BE] mt-0.5">Bs. {{ number_format($ingresosBrutosBs, 2) }} (Histórico)</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center">
                <i class="ph ph-trend-up text-xl text-green-500"></i>
            </div>
        </div>

        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 border-l-red-500">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1">Egresos Operativos</p>
                <p class="text-2xl font-black text-[#263A47]">$ {{ number_format($egresosTotales, 2) }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center">
                <i class="ph ph-trend-down text-xl text-red-500"></i>
            </div>
        </div>

        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 {{ $utilidadNeta >= 0 ? 'border-l-blue-600' : 'border-l-red-600' }}">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1">Utilidad Neta</p>
                <p class="text-2xl font-black {{ $utilidadNeta >= 0 ? 'text-[#263A47]' : 'text-red-600' }}">
                    $ {{ number_format($utilidadNeta, 2) }}
                </p>
            </div>
            <div class="w-11 h-11 rounded-xl {{ $utilidadNeta >= 0 ? 'bg-blue-50' : 'bg-red-50' }} flex items-center justify-center">
                <i class="ph ph-coins text-xl {{ $utilidadNeta >= 0 ? 'text-blue-600' : 'text-red-600' }}"></i>
            </div>
        </div>

        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 border-l-amber-500">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1">Saldo Pendiente</p>
                <p class="text-2xl font-black text-amber-600">$ {{ number_format($totalPendiente, 2) }}</p>
                <p class="text-[11px] text-[#98A9BE] mt-0.5">{{ $facturasPendientes }} factura(s)</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class="ph ph-hourglass-medium text-xl text-amber-500"></i>
            </div>
        </div>
    </div>

    {{-- TABLA DE HISTORIAL DE FACTURACIÓN --}}
    <div class="bg-white rounded-xl border border-[#98A9BE]/30 shadow-sm flex flex-col">
        <div class="p-4 md:p-5 border-b border-[#B4C5D8]/40 flex justify-between items-center bg-[#F8FAFB] rounded-t-xl">
            <h3 class="text-base font-bold text-[#263A47] flex items-center gap-2">
                <i class="ph ph-book-open text-[#4A5B6A]"></i> Libro de Ventas (Historial de Facturación)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#B4C5D8]/40 text-[#4A5B6A] text-[11px] uppercase tracking-wider bg-[#F8FAFB]">
                        <th class="px-4 md:px-5 py-3 font-bold">N° Factura / Ref.</th>
                        <th class="px-4 md:px-5 py-3 font-bold text-right">Repuestos</th>
                        <th class="px-4 md:px-5 py-3 font-bold text-right">Mano Obra</th>
                        <th class="px-4 md:px-5 py-3 font-bold text-right">Total</th>
                        <th class="px-4 md:px-5 py-3 font-bold text-center">Estado Pago</th>
                        <th class="px-4 md:px-5 py-3 font-bold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    @forelse($facturas as $factura)
                    <tr class="border-b border-[#B4C5D8]/20 hover:bg-[#F1F4F8]/50 transition">
                        <td class="px-4 md:px-5 py-4">
                            <p class="font-bold">{{ $factura->numero_factura }}</p>
                            <p class="text-[11px] text-[#728495]">{{ $factura->referencia }}</p>
                        </td>
                        <td class="px-4 md:px-5 py-4 text-right font-medium tabular-nums">$ {{ number_format($factura->subtotal_repuestos, 2) }}</td>
                        <td class="px-4 md:px-5 py-4 text-right font-medium tabular-nums">$ {{ number_format($factura->subtotal_mano_obra, 2) }}</td>
                        <td class="px-4 md:px-5 py-4 text-right font-bold text-base text-[#263A47] tabular-nums">$ {{ number_format($factura->total_facturado, 2) }}</td>
                        
                        {{-- Estado de pago con badge --}}
                        <td class="px-4 md:px-5 py-4 text-center">
                            @if($factura->estado_pago === 'Pagado')
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-[11px] font-bold border border-green-200">
                                    <i class="ph ph-check-circle"></i> Pagado
                                </span>
                            @elseif($factura->estado_pago === 'Parcial')
                                <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full text-[11px] font-bold border border-amber-200">
                                    <i class="ph ph-clock"></i> Parcial
                                </span>
                                <p class="text-[10px] text-[#98A9BE] mt-0.5">Debe: ${{ number_format($factura->saldo_pendiente, 2) }}</p>
                            @else
                                <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-[11px] font-bold border border-red-200">
                                    <i class="ph ph-warning"></i> Pendiente
                                </span>
                            @endif
                        </td>
                        
                        {{-- Acciones --}}
                        <td class="px-4 md:px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($factura->estado_pago !== 'Pagado')
                                    <button onclick="abrirModalPago({{ $factura->id }}, '{{ $factura->numero_factura }}', {{ $factura->saldo_pendiente > 0 ? $factura->saldo_pendiente : $factura->total_facturado }})" 
                                        class="btn-scale bg-green-600 text-white px-3 py-1.5 rounded-lg text-[11px] font-bold hover:bg-green-700 transition inline-flex items-center gap-1" title="Registrar pago">
                                        <i class="ph ph-money text-base"></i> Pagar
                                    </button>
                                @endif
                                <a href="{{ route('finanzas.imprimir', $factura->id) }}" target="_blank" class="btn-scale text-[#4A5B6A] hover:text-[#263A47] bg-[#F1F4F8] p-2 rounded-lg transition" title="Imprimir">
                                    <i class="ph ph-printer text-lg"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-[#728495]">
                            <i class="ph ph-receipt text-4xl block mb-2 text-[#B4C5D8]"></i>
                            No hay facturas emitidas este mes.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($facturas->hasPages())
            <div class="px-5 py-3 border-t border-[#B4C5D8]/30">
                {{ $facturas->links() }}
            </div>
        @endif
    </div>

    {{-- ========================================== --}}
    {{-- MODAL GENERAR FACTURA (con calculadora) --}}
    {{-- ========================================== --}}
    <div id="modal-factura" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col max-h-[90vh] overflow-hidden animate-fade-in">
            <div class="px-6 py-4 border-b border-[#B4C5D8]/40 flex justify-between items-center bg-[#F8FAFB] shrink-0">
                <h3 class="text-lg font-bold text-[#263A47] flex items-center gap-2">
                    <i class="ph ph-receipt text-[#4A5B6A]"></i> Emitir Factura Fiscal
                </h3>
                <button onclick="cerrarModalFactura()" class="text-[#728495] hover:text-red-500 transition p-1"><i class="ph ph-x text-xl"></i></button>
            </div>
            <form action="{{ route('finanzas.guardar') }}" method="POST" class="p-6 overflow-y-auto overflow-x-hidden">
                @csrf
                <input type="hidden" name="orden_id" value="{{ session('orden_id') }}">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Referencia *</label>
                        <input type="text" name="referencia" value="{{ session('referencia') }}" required placeholder="N° Orden o Detalle" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] focus:ring-1 focus:ring-[#263A47]/20 text-sm bg-[#F8FAFB]">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Repuestos ($) *</label>
                            <input type="number" step="0.01" name="subtotal_repuestos" id="calc-repuestos" value="{{ session('subtotal_repuestos', '0.00') }}" required min="0" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] text-sm bg-[#F8FAFB] tabular-nums" oninput="calcularTotal()">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Mano de Obra ($) *</label>
                            <input type="number" step="0.01" name="subtotal_mano_obra" id="calc-mano-obra" value="{{ session('subtotal_mano_obra', '0.00') }}" required min="0" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] text-sm bg-[#F8FAFB] tabular-nums" oninput="calcularTotal()">
                        </div>
                    </div>
                    
                    {{-- Calculadora en tiempo real --}}
                    <div class="bg-gradient-to-br from-[#F1F4F8] to-[#E8ECF1] rounded-xl p-4 border border-[#B4C5D8]/50 space-y-2">
                        <p class="text-[10px] font-bold text-[#4A5B6A] uppercase tracking-wider mb-2">Vista Previa del Cálculo</p>
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-[#728495]">Base Imponible:</span>
                            <span class="font-bold text-[#263A47] tabular-nums" id="calc-base">$ 0.00</span>
                        </div>
                        
                        <div class="border-t border-[#B4C5D8]/50 pt-2 mt-2">
                            <div class="flex justify-between">
                                <span class="text-sm font-bold text-[#263A47]">TOTAL USD:</span>
                                <span class="text-xl font-black text-green-600 tabular-nums" id="calc-total-usd">$ 0.00</span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-xs font-bold text-blue-600">TOTAL Bs.:</span>
                                <span class="text-sm font-bold text-blue-600 tabular-nums" id="calc-total-bs">Bs. 0.00</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="cerrarModalFactura()" class="btn-scale px-5 py-2.5 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#F1F4F8] transition text-sm">Cancelar</button>
                    <button type="submit" class="btn-scale px-5 py-2.5 bg-[#263A47] text-white font-semibold rounded-lg hover:bg-[#4A5B6A] transition text-sm flex items-center gap-2">
                        <i class="ph ph-check-circle"></i> Emitir Factura
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL REGISTRAR PAGO/ABONO --}}
    {{-- ========================================== --}}
    <div id="modal-pago" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md flex flex-col max-h-[90vh] overflow-hidden animate-fade-in">
            <div class="px-6 py-4 border-b border-[#B4C5D8]/40 flex justify-between items-center bg-[#F8FAFB] shrink-0">
                <h3 class="text-lg font-bold text-[#263A47] flex items-center gap-2">
                    <i class="ph ph-money text-green-600"></i> Registrar Pago
                </h3>
                <button onclick="cerrarModalPago()" class="text-[#728495] hover:text-red-500 transition p-1"><i class="ph ph-x text-xl"></i></button>
            </div>
            <form action="{{ route('finanzas.pago') }}" method="POST" class="p-6 overflow-y-auto overflow-x-hidden">
                @csrf
                <input type="hidden" name="factura_id" id="pago-factura-id">
                
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-blue-500 font-bold">Factura</p>
                            <p class="text-sm font-bold text-blue-700" id="pago-factura-num">—</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-blue-500 font-bold">Saldo Pendiente</p>
                            <p class="text-lg font-black text-blue-700" id="pago-saldo-display">$ 0.00</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Monto a Pagar ($) *</label>
                        <input type="number" step="0.01" name="monto" id="pago-monto" required min="0.01" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-green-500 text-sm bg-[#F8FAFB] tabular-nums" oninput="actualizarPagoBs()">
                        <p class="text-xs text-blue-600 mt-1 font-semibold" id="pago-monto-bs">Bs. 0.00</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Método de Pago *</label>
                        <select name="metodo_pago" required class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] text-sm bg-[#F8FAFB]">
                            <option value="">Seleccionar...</option>
                            <option value="Efectivo USD">💵 Efectivo (USD)</option>
                            <option value="Efectivo Bs">💴 Efectivo (Bs.)</option>
                            <option value="Transferencia">🏦 Transferencia Bancaria</option>
                            <option value="Pago Móvil">📱 Pago Móvil</option>
                            <option value="Punto de Venta">💳 Punto de Venta</option>
                            <option value="Zelle">💱 Zelle</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Referencia (opcional)</label>
                        <input type="text" name="referencia_pago" placeholder="N° de referencia bancaria" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] text-sm bg-[#F8FAFB]">
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="cerrarModalPago()" class="btn-scale px-5 py-2.5 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#F1F4F8] transition text-sm">Cancelar</button>
                    <button type="submit" class="btn-scale px-5 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition text-sm flex items-center gap-2">
                        <i class="ph ph-check-circle"></i> Confirmar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- SCRIPTS --}}
    {{-- ========================================== --}}
    <script>
        // Tasa BCV cargada desde PHP
        let tasaBcv = {{ $tasaBcv['precio'] ?? 0 }};

        // ============================================
        // CALCULADORA EN TIEMPO REAL (Modal Factura)
        // ============================================
        function calcularTotal() {
            const repuestos = parseFloat(document.getElementById('calc-repuestos').value) || 0;
            const manoObra = parseFloat(document.getElementById('calc-mano-obra').value) || 0;
            const base = repuestos + manoObra;
            const total = base;

            document.getElementById('calc-base').textContent = '$ ' + base.toFixed(2);
            document.getElementById('calc-total-usd').textContent = '$ ' + total.toFixed(2);
            document.getElementById('calc-total-bs').textContent = 'Bs. ' + (total * tasaBcv).toFixed(2);
        }
        // Calcular al cargar si hay valores pre-llenados
        document.addEventListener('DOMContentLoaded', calcularTotal);

        // ============================================
        // MODAL FACTURA
        // ============================================
        function abrirModalFactura() { 
            document.getElementById('modal-factura').classList.remove('hidden');
            calcularTotal();
        }
        function cerrarModalFactura() { document.getElementById('modal-factura').classList.add('hidden'); }

        @if(session('abrir_modal'))
            window.onload = function() { abrirModalFactura(); };
        @endif

        // ============================================
        // MODAL DE PAGO
        // ============================================
        function abrirModalPago(facturaId, numFactura, saldo) {
            document.getElementById('pago-factura-id').value = facturaId;
            document.getElementById('pago-factura-num').textContent = numFactura;
            document.getElementById('pago-saldo-display').textContent = '$ ' + saldo.toFixed(2);
            document.getElementById('pago-monto').value = saldo.toFixed(2);
            document.getElementById('pago-monto').max = saldo;
            document.getElementById('modal-pago').classList.remove('hidden');
            actualizarPagoBs();
        }
        function cerrarModalPago() { document.getElementById('modal-pago').classList.add('hidden'); }

        function actualizarPagoBs() {
            const monto = parseFloat(document.getElementById('pago-monto').value) || 0;
            document.getElementById('pago-monto-bs').textContent = 'Bs. ' + (monto * tasaBcv).toFixed(2);
        }

        // ============================================
        // REFRESCAR TASA BCV
        // ============================================
        function refrescarTasaBcv() {
            const icono = document.getElementById('icono-refrescar-bcv');
            icono.classList.add('animate-spin');
            
            fetch('/api/bcv/refrescar', { method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content} })
                .then(r => r.json())
                .then(data => {
                    icono.classList.remove('animate-spin');
                    if (!data.error) {
                        tasaBcv = data.precio;
                        document.getElementById('tasa-bcv-display').textContent = 'Bs. ' + data.precio.toFixed(2);
                        calcularTotal();
                        if (typeof mostrarToast === 'function') mostrarToast('Tasa BCV actualizada: Bs. ' + data.precio.toFixed(2), 'info');
                    } else {
                        if (typeof mostrarToast === 'function') mostrarToast('No se pudo obtener la tasa del BCV', 'error');
                    }
                })
                .catch(() => {
                    icono.classList.remove('animate-spin');
                    if (typeof mostrarToast === 'function') mostrarToast('Error de conexión con el BCV', 'error');
                });
        }
    </script>

@endsection
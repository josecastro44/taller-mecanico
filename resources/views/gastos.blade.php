@extends('layouts.app')

@section('contenido')

    {{-- CABECERA --}}
    <div class="mb-6 flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Gastos Operativos</h2>
            <p class="text-[#728495] text-sm">Gestión de gastos recurrentes: servicios públicos, alquiler, mantenimiento y más</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('contabilidad.gastos.pdf') }}" target="_blank" class="btn-scale bg-white text-[#263A47] border border-[#263A47] px-4 py-2.5 rounded-lg hover:bg-[#F1F4F8] shadow-sm transition-all flex items-center gap-2 font-semibold text-sm">
                <i class="ph ph-file-pdf text-lg text-red-500"></i> Reporte PDF
            </a>
            <button onclick="abrirModalCategoria()" class="btn-scale bg-white text-[#263A47] border border-[#263A47] px-4 py-2.5 rounded-lg hover:bg-[#F1F4F8] shadow-sm transition-all flex items-center gap-2 font-semibold text-sm">
                <i class="ph ph-tag-chevron text-lg text-blue-500"></i> Nueva Categoría
            </button>
            <button onclick="abrirModalGasto()" class="btn-scale bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all flex items-center gap-2 font-semibold text-sm">
                <i class="ph ph-plus-circle text-lg"></i> Registrar Gasto
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

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm">
            @foreach($errors->all() as $error)
                <p class="text-sm">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- TARJETAS KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 border-l-red-500">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1">Gastos del Mes</p>
                <p class="text-2xl font-black text-[#263A47]">$ {{ number_format($gastosMes, 2) }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center">
                <i class="ph ph-money text-xl text-red-500"></i>
            </div>
        </div>

        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 border-l-amber-500">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1">Pendientes de Pago</p>
                <p class="text-2xl font-black text-amber-600">{{ $gastosPendientes }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class="ph ph-hourglass-medium text-xl text-amber-500"></i>
            </div>
        </div>

        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 {{ $gastosVencidos > 0 ? 'border-l-red-600' : 'border-l-green-500' }}">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1">Vencidos</p>
                <p class="text-2xl font-black {{ $gastosVencidos > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $gastosVencidos }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl {{ $gastosVencidos > 0 ? 'bg-red-50' : 'bg-green-50' }} flex items-center justify-center">
                <i class="ph ph-{{ $gastosVencidos > 0 ? 'warning' : 'check-circle' }} text-xl {{ $gastosVencidos > 0 ? 'text-red-600' : 'text-green-500' }}"></i>
            </div>
        </div>

        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 border-l-blue-500">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1">Categorías Activas</p>
                <p class="text-2xl font-black text-[#263A47]">{{ $categorias->count() }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="ph ph-tag text-xl text-blue-500"></i>
            </div>
        </div>
    </div>

    {{-- ALERTAS DE PRÓXIMOS VENCIMIENTOS --}}
    @if($proximosVencimientos->count() > 0)
        <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4">
            <h4 class="font-bold text-amber-700 text-sm flex items-center gap-2 mb-3">
                <i class="ph ph-bell-ringing text-lg"></i> Próximos Vencimientos (5 días)
            </h4>
            <div class="flex flex-wrap gap-3">
                @foreach($proximosVencimientos as $prox)
                    <div class="bg-white border border-amber-200 rounded-lg px-3 py-2 text-sm flex items-center gap-2 shadow-sm">
                        <i class="{{ $prox->categoria->icono ?? 'ph ph-receipt' }} text-lg" style="color: {{ $prox->categoria->color ?? '#728495' }}"></i>
                        <div>
                            <p class="font-bold text-[#263A47]">{{ $prox->descripcion }}</p>
                            <p class="text-[11px] text-[#728495]">${{ number_format($prox->monto, 2) }} — Vence: {{ $prox->prox_vencimiento->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- TABLA DE GASTOS --}}
    <div class="bg-white rounded-xl border border-[#98A9BE]/30 shadow-sm flex flex-col">
        <div class="p-4 md:p-5 border-b border-[#B4C5D8]/40 flex justify-between items-center bg-[#F8FAFB] rounded-t-xl">
            <h3 class="text-base font-bold text-[#263A47] flex items-center gap-2">
                <i class="ph ph-list-checks text-[#4A5B6A]"></i> Registro de Gastos
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#B4C5D8]/40 text-[#4A5B6A] text-[11px] uppercase tracking-wider bg-[#F8FAFB]">
                        <th class="px-4 md:px-5 py-3 font-bold">Categoría</th>
                        <th class="px-4 md:px-5 py-3 font-bold">Descripción</th>
                        <th class="px-4 md:px-5 py-3 font-bold text-right">Monto</th>
                        <th class="px-4 md:px-5 py-3 font-bold text-center">Frecuencia</th>
                        <th class="px-4 md:px-5 py-3 font-bold text-center">Vencimiento</th>
                        <th class="px-4 md:px-5 py-3 font-bold text-center">Estado</th>
                        <th class="px-4 md:px-5 py-3 font-bold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    @forelse($gastos as $gasto)
                    <tr class="border-b border-[#B4C5D8]/20 hover:bg-[#F1F4F8]/50 transition">
                        <td class="px-4 md:px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: {{ $gasto->categoria->color ?? '#728495' }}15;">
                                    <i class="{{ $gasto->categoria->icono ?? 'ph ph-receipt' }}" style="color: {{ $gasto->categoria->color ?? '#728495' }}"></i>
                                </div>
                                <span class="font-semibold text-sm">{{ $gasto->categoria->nombre ?? 'Sin categoría' }}</span>
                            </div>
                        </td>
                        <td class="px-4 md:px-5 py-4">
                            <p class="font-medium">{{ $gasto->descripcion }}</p>
                            @if($gasto->observaciones)
                                <p class="text-[11px] text-[#98A9BE]">{{ Str::limit($gasto->observaciones, 40) }}</p>
                            @endif
                        </td>
                        <td class="px-4 md:px-5 py-4 text-right font-bold tabular-nums">$ {{ number_format($gasto->monto, 2) }}</td>
                        <td class="px-4 md:px-5 py-4 text-center">
                            <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full text-[11px] font-bold border border-blue-200">
                                {{ ucfirst($gasto->frecuencia) }}
                            </span>
                        </td>
                        <td class="px-4 md:px-5 py-4 text-center text-sm tabular-nums">
                            @if($gasto->prox_vencimiento)
                                <span class="{{ $gasto->estaVencido() ? 'text-red-600 font-bold' : 'text-[#728495]' }}">
                                    {{ $gasto->prox_vencimiento->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-[#98A9BE]">—</span>
                            @endif
                        </td>
                        <td class="px-4 md:px-5 py-4 text-center">
                            @if($gasto->estado === 'pagado')
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-[11px] font-bold border border-green-200">
                                    <i class="ph ph-check-circle"></i> Pagado
                                </span>
                            @elseif($gasto->estaVencido())
                                <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-[11px] font-bold border border-red-200 pulse-soft">
                                    <i class="ph ph-warning"></i> Vencido
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full text-[11px] font-bold border border-amber-200">
                                    <i class="ph ph-clock"></i> Pendiente
                                </span>
                            @endif
                        </td>
                        <td class="px-4 md:px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($gasto->estado !== 'pagado')
                                    <button onclick="abrirModalPagarGasto({{ $gasto->id }}, '{{ $gasto->descripcion }}', {{ $gasto->monto }})"
                                        class="btn-scale bg-green-600 text-white px-3 py-1.5 rounded-lg text-[11px] font-bold hover:bg-green-700 transition inline-flex items-center gap-1" title="Marcar como pagado">
                                        <i class="ph ph-money text-base"></i> Pagar
                                    </button>
                                @endif
                                <button onclick="abrirModalEditarGasto({{ json_encode($gasto) }})"
                                    class="btn-scale text-[#4A5B6A] hover:text-[#263A47] bg-[#F1F4F8] p-2 rounded-lg transition" title="Editar">
                                    <i class="ph ph-pencil-simple text-lg"></i>
                                </button>
                                <form action="{{ route('gastos.eliminar', $gasto->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este gasto?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-scale text-red-400 hover:text-red-600 bg-red-50 p-2 rounded-lg transition" title="Eliminar">
                                        <i class="ph ph-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-[#728495]">
                            <i class="ph ph-receipt text-4xl block mb-2 text-[#B4C5D8]"></i>
                            No hay gastos registrados. ¡Haz clic en "Registrar Gasto" para comenzar!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($gastos->hasPages())
            <div class="px-5 py-3 border-t border-[#B4C5D8]/30">
                {{ $gastos->links() }}
            </div>
        @endif
    </div>

    {{-- ========================================== --}}
    {{-- MODAL CREAR/EDITAR GASTO --}}
    {{-- ========================================== --}}
    <div id="modal-gasto" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col max-h-[90vh] overflow-hidden animate-fade-in">
            <div class="px-6 py-4 border-b border-[#B4C5D8]/40 flex justify-between items-center bg-[#F8FAFB] shrink-0">
                <h3 class="text-lg font-bold text-[#263A47] flex items-center gap-2" id="modal-gasto-titulo">
                    <i class="ph ph-receipt text-[#4A5B6A]"></i> Registrar Gasto Operativo
                </h3>
                <button onclick="cerrarModalGasto()" class="text-[#728495] hover:text-red-500 transition p-1"><i class="ph ph-x text-xl"></i></button>
            </div>
            <form id="form-gasto" action="{{ route('gastos.guardar') }}" method="POST" class="p-6 overflow-y-auto overflow-x-hidden">
                @csrf
                <div id="form-gasto-method"></div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Categoría *</label>
                        <select name="categoria_gasto_id" id="gasto-categoria" required class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] text-sm bg-[#F8FAFB]">
                            <option value="">Seleccionar categoría...</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Descripción *</label>
                        <input type="text" name="descripcion" id="gasto-descripcion" required placeholder="Ej: Factura de electricidad del local"
                            class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] text-sm bg-[#F8FAFB]">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Monto ($) *</label>
                            <input type="number" step="0.01" name="monto" id="gasto-monto" required min="0.01"
                                class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] text-sm bg-[#F8FAFB] tabular-nums">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Frecuencia *</label>
                            <select name="frecuencia" id="gasto-frecuencia" required class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] text-sm bg-[#F8FAFB]">
                                <option value="mensual">Mensual</option>
                                <option value="quincenal">Quincenal</option>
                                <option value="semanal">Semanal</option>
                                <option value="unico">Pago Único</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Fecha de Vencimiento</label>
                        <input type="date" name="prox_vencimiento" id="gasto-vencimiento"
                            class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] text-sm bg-[#F8FAFB]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Observaciones</label>
                        <textarea name="observaciones" id="gasto-observaciones" rows="2"
                            class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] text-sm bg-[#F8FAFB] resize-none"
                            placeholder="Notas adicionales..."></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="cerrarModalGasto()" class="btn-scale px-5 py-2.5 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#F1F4F8] transition text-sm">Cancelar</button>
                    <button type="submit" class="btn-scale px-5 py-2.5 bg-[#263A47] text-white font-semibold rounded-lg hover:bg-[#4A5B6A] transition text-sm flex items-center gap-2">
                        <i class="ph ph-check-circle"></i> <span id="btn-gasto-texto">Guardar Gasto</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL PAGAR GASTO --}}
    {{-- ========================================== --}}
    <div id="modal-pagar-gasto" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md flex flex-col max-h-[90vh] overflow-hidden animate-fade-in">
            <div class="px-6 py-4 border-b border-[#B4C5D8]/40 flex justify-between items-center bg-[#F8FAFB] shrink-0">
                <h3 class="text-lg font-bold text-[#263A47] flex items-center gap-2">
                    <i class="ph ph-money text-green-600"></i> Registrar Pago de Gasto
                </h3>
                <button onclick="cerrarModalPagarGasto()" class="text-[#728495] hover:text-red-500 transition p-1"><i class="ph ph-x text-xl"></i></button>
            </div>
            <form id="form-pagar-gasto" method="POST" class="p-6 overflow-y-auto overflow-x-hidden">
                @csrf
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-blue-500 font-bold">Gasto</p>
                            <p class="text-sm font-bold text-blue-700" id="pagar-gasto-nombre">—</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-blue-500 font-bold">Monto</p>
                            <p class="text-lg font-black text-blue-700" id="pagar-gasto-monto">$ 0.00</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Método de Pago *</label>
                        <select name="metodo_pago" required class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] text-sm bg-[#F8FAFB]">
                            <option value="">Seleccionar...</option>
                            <option value="Efectivo USD">💵 Efectivo (USD)</option>
                            <option value="Efectivo Bs">💴 Efectivo (Bs.)</option>
                            <option value="Transferencia">🏦 Transferencia Bancaria</option>
                            <option value="Pago Móvil">📱 Pago Móvil</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Referencia (opcional)</label>
                        <input type="text" name="referencia_pago" placeholder="N° de referencia bancaria"
                            class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] text-sm bg-[#F8FAFB]">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="cerrarModalPagarGasto()" class="btn-scale px-5 py-2.5 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#F1F4F8] transition text-sm">Cancelar</button>
                    <button type="submit" class="btn-scale px-5 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition text-sm flex items-center gap-2">
                        <i class="ph ph-check-circle"></i> Confirmar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL CREAR CATEGORÍA --}}
    {{-- ========================================== --}}
    <div id="modal-categoria" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm flex flex-col max-h-[90vh] overflow-hidden animate-fade-in">
            <div class="px-6 py-4 border-b border-[#B4C5D8]/40 flex justify-between items-center bg-[#F8FAFB] shrink-0">
                <h3 class="text-lg font-bold text-[#263A47] flex items-center gap-2">
                    <i class="ph ph-tag text-[#4A5B6A]"></i> Nueva Categoría
                </h3>
                <button onclick="cerrarModalCategoria()" class="text-[#728495] hover:text-red-500 transition p-1"><i class="ph ph-x text-xl"></i></button>
            </div>
            <form action="{{ route('gastos.categoria.guardar') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Nombre de Categoría *</label>
                        <input type="text" name="nombre" required placeholder="Ej: Mantenimiento"
                            class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] text-sm bg-[#F8FAFB]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Icono (Clase Phosphor)</label>
                        <input type="text" name="icono" placeholder="Ej: ph ph-wrench" value="ph ph-receipt"
                            class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] text-sm bg-[#F8FAFB]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Color (Hex)</label>
                        <input type="color" name="color" value="#728495"
                            class="w-full h-10 border border-[#B4C5D8] rounded-lg px-2 outline-none focus:border-[#263A47] bg-[#F8FAFB]">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="cerrarModalCategoria()" class="btn-scale px-5 py-2.5 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#F1F4F8] transition text-sm">Cancelar</button>
                    <button type="submit" class="btn-scale px-5 py-2.5 bg-[#263A47] text-white font-semibold rounded-lg hover:bg-[#4A5B6A] transition text-sm flex items-center gap-2">
                        <i class="ph ph-check-circle"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        // Modal Crear Gasto
        function abrirModalGasto() {
            document.getElementById('form-gasto').action = '{{ route("gastos.guardar") }}';
            document.getElementById('form-gasto-method').innerHTML = '';
            document.getElementById('modal-gasto-titulo').innerHTML = '<i class="ph ph-receipt text-[#4A5B6A]"></i> Registrar Gasto Operativo';
            document.getElementById('btn-gasto-texto').textContent = 'Guardar Gasto';
            document.getElementById('gasto-categoria').value = '';
            document.getElementById('gasto-descripcion').value = '';
            document.getElementById('gasto-monto').value = '';
            document.getElementById('gasto-frecuencia').value = 'mensual';
            document.getElementById('gasto-vencimiento').value = '';
            document.getElementById('gasto-observaciones').value = '';
            document.getElementById('modal-gasto').classList.remove('hidden');
        }

        // Modal Editar Gasto
        function abrirModalEditarGasto(gasto) {
            document.getElementById('form-gasto').action = '/gastos/' + gasto.id;
            document.getElementById('form-gasto-method').innerHTML = '@method("PUT")';
            document.getElementById('modal-gasto-titulo').innerHTML = '<i class="ph ph-pencil-simple text-[#4A5B6A]"></i> Editar Gasto';
            document.getElementById('btn-gasto-texto').textContent = 'Actualizar Gasto';
            document.getElementById('gasto-categoria').value = gasto.categoria_gasto_id;
            document.getElementById('gasto-descripcion').value = gasto.descripcion;
            document.getElementById('gasto-monto').value = gasto.monto;
            document.getElementById('gasto-frecuencia').value = gasto.frecuencia;
            document.getElementById('gasto-vencimiento').value = gasto.prox_vencimiento ? gasto.prox_vencimiento.substring(0, 10) : '';
            document.getElementById('gasto-observaciones').value = gasto.observaciones || '';
            document.getElementById('modal-gasto').classList.remove('hidden');
        }

        function cerrarModalGasto() { document.getElementById('modal-gasto').classList.add('hidden'); }

        // Modal Categoría
        function abrirModalCategoria() { document.getElementById('modal-categoria').classList.remove('hidden'); }
        function cerrarModalCategoria() { document.getElementById('modal-categoria').classList.add('hidden'); }

        // Modal Pagar Gasto
        function abrirModalPagarGasto(id, nombre, monto) {
            document.getElementById('form-pagar-gasto').action = '/gastos/' + id + '/pagar';
            document.getElementById('pagar-gasto-nombre').textContent = nombre;
            document.getElementById('pagar-gasto-monto').textContent = '$ ' + monto.toFixed(2);
            document.getElementById('modal-pagar-gasto').classList.remove('hidden');
        }

        function cerrarModalPagarGasto() { document.getElementById('modal-pagar-gasto').classList.add('hidden'); }
    </script>

@endsection

@extends('layouts.app')

@section('contenido')

    {{-- CABECERA --}}
    <div class="mb-6 flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Libro Diario y Contabilidad</h2>
            <p class="text-[#728495] text-sm">Registro cronológico de todos los movimientos financieros del taller</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('contabilidad.libro.pdf', ['desde' => $desde->format('Y-m-d'), 'hasta' => $hasta->format('Y-m-d')]) }}" target="_blank"
                class="btn-scale bg-white border-2 border-[#263A47] text-[#263A47] px-4 py-2.5 rounded-lg hover:bg-gray-50 shadow-sm transition-all flex items-center gap-2 font-bold text-sm">
                <i class="ph ph-file-pdf text-lg"></i> Libro Diario PDF
            </a>
            <a href="{{ route('contabilidad.gastos.pdf', ['desde' => $desde->format('Y-m-d'), 'hasta' => $hasta->format('Y-m-d')]) }}" target="_blank"
                class="btn-scale bg-white border-2 border-red-500 text-red-600 px-4 py-2.5 rounded-lg hover:bg-red-50 shadow-sm transition-all flex items-center gap-2 font-bold text-sm">
                <i class="ph ph-file-pdf text-lg"></i> Gastos PDF
            </a>
            <a href="{{ route('contabilidad.balance.pdf', ['desde' => $desde->format('Y-m-d'), 'hasta' => $hasta->format('Y-m-d')]) }}" target="_blank"
                class="btn-scale bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all flex items-center gap-2 font-semibold text-sm">
                <i class="ph ph-chart-bar text-lg"></i> Balance PDF
            </a>
        </div>
    </div>

    {{-- FILTRO DE FECHAS --}}
    <div class="mb-6 bg-white rounded-xl border border-[#98A9BE]/30 p-4 shadow-sm">
        <form method="GET" action="{{ route('contabilidad') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Desde</label>
                <input type="date" name="desde" value="{{ $desde->format('Y-m-d') }}"
                    class="border border-[#B4C5D8] rounded-lg px-4 py-2 text-sm outline-none focus:border-[#263A47] bg-[#F8FAFB]">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#4A5B6A] mb-1 uppercase tracking-wider">Hasta</label>
                <input type="date" name="hasta" value="{{ $hasta->format('Y-m-d') }}"
                    class="border border-[#B4C5D8] rounded-lg px-4 py-2 text-sm outline-none focus:border-[#263A47] bg-[#F8FAFB]">
            </div>
            <button type="submit" class="btn-scale bg-[#263A47] text-white px-5 py-2 rounded-lg hover:bg-[#4A5B6A] transition text-sm font-semibold flex items-center gap-2">
                <i class="ph ph-funnel text-base"></i> Filtrar
            </button>
        </form>
    </div>

    {{-- TARJETAS DE BALANCE --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 border-l-green-500">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1">Total Ingresos</p>
                <p class="text-2xl font-black text-green-600">$ {{ number_format($balance['ingresos'], 2) }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center">
                <i class="ph ph-trend-up text-xl text-green-500"></i>
            </div>
        </div>

        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 border-l-red-500">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1">Total Egresos</p>
                <p class="text-2xl font-black text-red-600">$ {{ number_format($balance['egresos'], 2) }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center">
                <i class="ph ph-trend-down text-xl text-red-500"></i>
            </div>
        </div>

        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 {{ $balance['utilidad_neta'] >= 0 ? 'border-l-blue-600' : 'border-l-red-600' }}">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1">Utilidad Neta</p>
                <p class="text-2xl font-black {{ $balance['utilidad_neta'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                    $ {{ number_format($balance['utilidad_neta'], 2) }}
                </p>
            </div>
            <div class="w-11 h-11 rounded-xl {{ $balance['utilidad_neta'] >= 0 ? 'bg-blue-50' : 'bg-red-50' }} flex items-center justify-center">
                <i class="ph ph-coins text-xl {{ $balance['utilidad_neta'] >= 0 ? 'text-blue-600' : 'text-red-600' }}"></i>
            </div>
        </div>

        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 border-l-indigo-500">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1">Saldo Acumulado</p>
                <p class="text-2xl font-black text-indigo-600">$ {{ number_format($saldoActual, 2) }}</p>
                <p class="text-[11px] text-[#98A9BE] mt-0.5">{{ $totalAsientos }} asientos</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center">
                <i class="ph ph-bank text-xl text-indigo-500"></i>
            </div>
        </div>
    </div>

    {{-- DESGLOSE POR CATEGORÍA --}}
    @if($desglose->count() > 0)
    <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Ingresos por categoría --}}
        <div class="bg-white rounded-xl border border-[#98A9BE]/30 shadow-sm p-5">
            <h3 class="text-sm font-bold text-[#263A47] mb-4 flex items-center gap-2">
                <i class="ph ph-arrow-circle-down text-green-500"></i> Desglose de Ingresos
            </h3>
            <div class="space-y-3">
                @foreach($desglose->where('tipo', 'ingreso') as $item)
                    @php
                        $pct = $balance['ingresos'] > 0 ? ($item->total / $balance['ingresos']) * 100 : 0;
                        $labels = ['facturacion' => 'Facturación', 'venta_mostrador' => 'Ventas Mostrador'];
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-[#4A5B6A]">{{ $labels[$item->categoria] ?? ucfirst(str_replace('_', ' ', $item->categoria)) }}</span>
                            <span class="font-bold tabular-nums">$ {{ number_format($item->total, 2) }}</span>
                        </div>
                        <div class="w-full bg-green-100 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
                @if($desglose->where('tipo', 'ingreso')->count() === 0)
                    <p class="text-sm text-[#98A9BE] text-center py-4">Sin ingresos en el período</p>
                @endif
            </div>
        </div>

        {{-- Egresos por categoría --}}
        <div class="bg-white rounded-xl border border-[#98A9BE]/30 shadow-sm p-5">
            <h3 class="text-sm font-bold text-[#263A47] mb-4 flex items-center gap-2">
                <i class="ph ph-arrow-circle-up text-red-500"></i> Desglose de Egresos
            </h3>
            <div class="space-y-3">
                @foreach($desglose->where('tipo', 'egreso') as $item)
                    @php
                        $pct = $balance['egresos'] > 0 ? ($item->total / $balance['egresos']) * 100 : 0;
                        $labels = ['compra_inventario' => 'Compras Inventario', 'gasto_operativo' => 'Gastos Operativos', 'nomina' => 'Nómina'];
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-[#4A5B6A]">{{ $labels[$item->categoria] ?? ucfirst(str_replace('_', ' ', $item->categoria)) }}</span>
                            <span class="font-bold tabular-nums">$ {{ number_format($item->total, 2) }}</span>
                        </div>
                        <div class="w-full bg-red-100 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
                @if($desglose->where('tipo', 'egreso')->count() === 0)
                    <p class="text-sm text-[#98A9BE] text-center py-4">Sin egresos en el período</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- TABLA LIBRO DIARIO --}}
    <div class="bg-white rounded-xl border border-[#98A9BE]/30 shadow-sm flex flex-col">
        <div class="p-4 md:p-5 border-b border-[#B4C5D8]/40 flex justify-between items-center bg-[#F8FAFB] rounded-t-xl">
            <h3 class="text-base font-bold text-[#263A47] flex items-center gap-2">
                <i class="ph ph-book-open text-[#4A5B6A]"></i> Libro Diario — Asientos Contables
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#B4C5D8]/40 text-[#4A5B6A] text-[11px] uppercase tracking-wider bg-[#F8FAFB]">
                        <th class="px-4 md:px-5 py-3 font-bold">Fecha</th>
                        <th class="px-4 md:px-5 py-3 font-bold">Tipo</th>
                        <th class="px-4 md:px-5 py-3 font-bold">Categoría</th>
                        <th class="px-4 md:px-5 py-3 font-bold">Concepto</th>
                        <th class="px-4 md:px-5 py-3 font-bold">Referencia</th>
                        <th class="px-4 md:px-5 py-3 font-bold text-right">Monto</th>
                        <th class="px-4 md:px-5 py-3 font-bold text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    @forelse($asientos as $asiento)
                    <tr class="border-b border-[#B4C5D8]/20 hover:bg-[#F1F4F8]/50 transition">
                        <td class="px-4 md:px-5 py-3 tabular-nums text-[#728495]">{{ $asiento->fecha->format('d/m/Y') }}</td>
                        <td class="px-4 md:px-5 py-3">
                            @if($asiento->tipo === 'ingreso')
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-[11px] font-bold border border-green-200">
                                    <i class="ph ph-arrow-down"></i> Ingreso
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-[11px] font-bold border border-red-200">
                                    <i class="ph ph-arrow-up"></i> Egreso
                                </span>
                            @endif
                        </td>
                        <td class="px-4 md:px-5 py-3">
                            @php
                                $catLabels = ['facturacion' => 'Facturación', 'venta_mostrador' => 'Venta Mostrador', 'compra_inventario' => 'Compra Inventario', 'gasto_operativo' => 'Gasto Operativo', 'nomina' => 'Nómina'];
                            @endphp
                            <span class="text-[#4A5B6A] font-medium">{{ $catLabels[$asiento->categoria] ?? ucfirst(str_replace('_', ' ', $asiento->categoria)) }}</span>
                        </td>
                        <td class="px-4 md:px-5 py-3 font-medium">{{ Str::limit($asiento->concepto, 50) }}</td>
                        <td class="px-4 md:px-5 py-3 text-[#728495]">{{ $asiento->referencia ?? '—' }}</td>
                        <td class="px-4 md:px-5 py-3 text-right font-bold tabular-nums {{ $asiento->tipo === 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $asiento->tipo === 'ingreso' ? '+' : '-' }} $ {{ number_format($asiento->monto, 2) }}
                        </td>
                        <td class="px-4 md:px-5 py-3 text-right font-bold tabular-nums text-[#263A47]">$ {{ number_format($asiento->saldo_acumulado, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-[#728495]">
                            <i class="ph ph-book-open text-4xl block mb-2 text-[#B4C5D8]"></i>
                            No hay asientos contables registrados en este período.<br>
                            <span class="text-xs">Los asientos se generan automáticamente al crear facturas, ventas, compras y gastos.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($asientos->hasPages())
            <div class="px-5 py-3 border-t border-[#B4C5D8]/30">
                {{ $asientos->links() }}
            </div>
        @endif
    </div>

@endsection

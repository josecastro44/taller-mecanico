@extends('layouts.app')

@section('contenido')
<div class="w-full">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-[#263A47]">Visión General del Negocio</h1>
            <p class="text-gray-500 mt-1">Hola, <span class="font-bold">{{ Auth::user()->name }}</span>. Aquí tienes el resumen de AutoSys hoy.</p>
        </div>
        <button class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold flex items-center gap-2 shadow-sm hover:bg-green-700 transition">
            <i class="ph ph-file-pdf text-xl"></i> Exportar Reporte
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-blue-500">
            <p class="text-sm text-[#728495] font-bold uppercase">Ingresos del Mes</p>
            <p class="text-3xl font-black text-[#263A47] mt-1">$ {{ number_format($ingresosMes, 2) }}</p>
            <p class="text-xs text-green-600 mt-2 font-bold"><i class="ph ph-trend-up"></i> Bs. {{ number_format($ingresosMesBs, 2) }} (Histórico)</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-purple-500">
            <p class="text-sm text-[#728495] font-bold uppercase">Órdenes Activas</p>
            <p class="text-3xl font-black text-[#263A47] mt-1">{{ $ordenesActivas }}</p>
            <p class="text-xs text-[#728495] mt-2">En el taller ahora mismo</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-amber-500">
            <p class="text-sm text-[#728495] font-bold uppercase">Inventario Bajo</p>
            <p class="text-3xl font-black text-amber-600 mt-1">{{ $inventarioBajo }}</p>
            <p class="text-xs text-amber-600 mt-2 font-bold {{ $inventarioBajo > 0 ? 'animate-pulse' : '' }}">Repuestos por agotar</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-[#4A5B6A]">
            <p class="text-sm text-[#728495] font-bold uppercase">Personal y Nómina</p>
            <p class="text-3xl font-black text-[#263A47] mt-1">{{ $empleadosActivos }}</p>
            <p class="text-xs text-[#728495] mt-2">Nómina Fija: $ {{ number_format($nominaMensual, 2) }}</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-red-50 p-5 rounded-xl border border-red-200 shadow-sm md:col-span-2">
            <p class="text-sm text-red-700 font-bold uppercase">Cuentas por Cobrar (Facturas Pendientes)</p>
            <p class="text-3xl font-black text-red-800 mt-1">$ {{ number_format($pendientesCobrar, 2) }}</p>
            <p class="text-xs text-red-600 mt-2">Capital en la calle</p>
        </div>
        <div class="bg-indigo-50 p-5 rounded-xl border border-indigo-200 shadow-sm md:col-span-2">
            <p class="text-sm text-indigo-700 font-bold uppercase">Mercancía en Tránsito (Compras)</p>
            <p class="text-3xl font-black text-indigo-800 mt-1">{{ $comprasPendientes }}</p>
            <p class="text-xs text-indigo-600 mt-2">Órdenes de compra por recibir</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
        {{-- Últimos Repuestos Solicitados --}}
        <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
            <div class="bg-[#F8FAFB] p-4 border-b border-[#B4C5D8]/50 flex justify-between items-center">
                <h3 class="font-bold text-[#263A47] flex items-center gap-2"><i class="ph ph-package text-xl text-blue-500"></i> Repuestos Usados Recientemente</h3>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    @forelse($solicitudesRepuestos as $solicitud)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="p-4">
                            <p class="font-bold text-[#263A47]">{{ $solicitud->mecanico }}</p>
                            <p class="text-xs text-gray-500">O.S. #00{{ $solicitud->orden_id }}</p>
                        </td>
                        <td class="p-4 font-bold text-[#4A5B6A]">
                            {{ $solicitud->repuesto }}
                            <span class="text-xs bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full ml-1">x{{ $solicitud->cantidad }}</span>
                        </td>
                        <td class="p-4 text-right text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($solicitud->created_at)->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-gray-400 font-medium">No hay registros recientes de repuestos usados.</td>
                    </tr>
                    @endforelse
                </table>
            </div>
        </div>

        {{-- Últimas Órdenes Activas --}}
        <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
            <div class="bg-[#F8FAFB] p-4 border-b border-[#B4C5D8]/50 flex justify-between items-center">
                <h3 class="font-bold text-[#263A47] flex items-center gap-2"><i class="ph ph-car-profile text-xl text-amber-500"></i> Últimos Ingresos a Taller</h3>
                <a href="{{ route('monitor') }}" class="text-sm text-blue-600 font-bold hover:underline">Ver monitor completo</a>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    @forelse($ultimasOrdenes as $orden)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="p-4">
                            <p class="font-bold text-[#263A47]">{{ $orden->vehiculo->marca ?? '' }} {{ $orden->vehiculo->modelo ?? '' }}</p>
                            <p class="text-xs font-mono text-gray-500">{{ $orden->vehiculo->placa ?? '' }}</p>
                        </td>
                        <td class="p-4">
                            @if($orden->estado == 'En Espera')
                                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-[11px] font-bold border border-gray-200"><i class="ph ph-clock"></i> En Espera</span>
                            @elseif($orden->estado == 'En Reparación')
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[11px] font-bold border border-amber-200"><i class="ph ph-wrench pulse-soft"></i> Reparando</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <span class="text-xs text-gray-500">{{ $orden->created_at->diffForHumans() }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-gray-400 font-medium">No hay vehículos en el taller ahora mismo.</td>
                    </tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
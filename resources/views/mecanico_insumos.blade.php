@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Repuestos Solicitados</h2>
            <p class="text-[#728495]">Haz seguimiento a las piezas y materiales que pediste a almacén.</p>
        </div>
        {{-- Te redirigí este botón al panel principal porque ahí está el Modal de pedir repuestos --}}
        <a href="{{ route('mecanico') }}" class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all font-medium flex items-center justify-center gap-2">
            <i class="ph ph-plus-circle text-xl"></i> Nueva Solicitud
        </a>
    </div>

    {{-- TARJETAS DE ESTADÍSTICAS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-amber-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">En Espera</p>
                <p class="text-3xl font-bold text-[#263A47]">{{ $enEspera }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center">
                <i class="ph ph-clock text-2xl text-amber-600"></i>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-green-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Entregados (Hoy)</p>
                <p class="text-3xl font-bold text-[#263A47]">{{ $entregadosHoy }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="ph ph-check-circle text-2xl text-green-600"></i>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-red-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Rechazados / Sin Stock</p>
                <p class="text-3xl font-bold text-[#263A47]">{{ $rechazados }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center">
                <i class="ph ph-x-circle text-2xl text-red-600"></i>
            </div>
        </div>
    </div>

    {{-- TABLA DE SOLICITUDES --}}
    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-[#B4C5D8] bg-[#B4C5D8]/10 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h3 class="text-lg font-bold text-[#263A47]">Historial de Solicitudes</h3>
            <div class="relative w-full sm:w-64">
                <i class="ph ph-magnifying-glass absolute left-3 top-2 text-[#728495] text-lg"></i>
                <input type="text" placeholder="Buscar repuesto..." class="w-full pl-9 pr-4 py-1.5 border border-[#B4C5D8] rounded focus:outline-none focus:border-[#4A5B6A] text-sm text-[#263A47] bg-white">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Fecha / Hora</th>
                        <th class="px-6 py-4 font-bold">Repuesto Solicitado</th>
                        <th class="px-6 py-4 font-bold text-center">O.S. Asignada</th>
                        <th class="px-6 py-4 font-bold text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    
                    @forelse($solicitudes as $solicitud)
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-medium text-[#728495]">
                            @if($solicitud->fecha->isToday())
                                Hoy, {{ $solicitud->fecha->format('h:i A') }}
                            @else
                                {{ $solicitud->fecha->format('d M Y, h:i A') }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-[#263A47]">{{ $solicitud->nombre }}</p>
                            <p class="text-xs font-medium text-[#98A9BE] mt-0.5">Cantidad: {{ $solicitud->cantidad }} Unidad(es)</p>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-[#4A5B6A] font-mono">
                            #00{{ $solicitud->orden_id }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($solicitud->estado == 'Entregado')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200 inline-flex items-center gap-1">
                                    <i class="ph ph-check"></i> Entregado
                                </span>
                            @else
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold border border-amber-200 inline-flex items-center gap-1">
                                    <i class="ph ph-clock"></i> Pendiente
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-[#728495] font-medium">
                            <i class="ph ph-package text-4xl mb-2 block"></i>
                            Aún no has solicitado repuestos a almacén.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

@endsection
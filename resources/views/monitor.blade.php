@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Monitor de Taller y Entregas</h2>
            <p class="text-[#728495]">Supervisa en tiempo real el estatus de todos los vehículos ingresados.</p>
        </div>
        <div class="relative w-full md:w-80">
            <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-[#728495] text-xl"></i>
            <input type="text" placeholder="Buscar placa o cliente..." class="w-full pl-10 pr-4 py-2 border border-[#B4C5D8] rounded-lg focus:outline-none focus:border-[#4A5B6A] text-[#263A47] bg-white shadow-sm">
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#B4C5D8]/10 border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">O.S. / Fecha</th>
                        <th class="px-6 py-4 font-bold">Vehículo</th>
                        <th class="px-6 py-4 font-bold">Mecanico Asignado</th>
                        <th class="px-6 py-4 font-bold text-center">Estatus</th>
                        <th class="px-6 py-4 font-bold text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    @forelse($ordenes as $orden)
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold text-[#263A47]">#00{{ $orden->id }}</p>
                            <p class="text-xs text-[#728495]">{{ $orden->created_at->format('d M, h:i A') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold">{{ $orden->vehiculo->marca }} {{ $orden->vehiculo->modelo }}</p>
                            <p class="text-xs font-mono font-bold text-[#98A9BE]">{{ $orden->vehiculo->placa }}</p>
                        </td>
                        <td class="px-6 py-4 font-medium text-[#4A5B6A]">
                            {{ $orden->mecanico ? $orden->mecanico->nombre : 'Sin asignar' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($orden->estado == 'En Espera')
                                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold border border-gray-200 inline-flex items-center gap-1"><i class="ph ph-clock"></i> En Espera</span>
                            @elseif($orden->estado == 'En Reparación')
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold border border-amber-200 inline-flex items-center gap-1"><i class="ph ph-wrench"></i> En Taller</span>
                            @elseif($orden->estado == 'Finalizado')
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold border border-blue-200 inline-flex items-center gap-1"><i class="ph ph-check-circle"></i> Listo p/ Entrega</span>
                            @elseif($orden->estado == 'Entregado')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200 inline-flex items-center gap-1"><i class="ph ph-car-profile"></i> Entregado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($orden->estado == 'Finalizado')
                                {{-- Te conecté el botón a finanzas --}}
                                <a href="{{ route('finanzas.preparar', $orden->id) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-green-700 transition flex items-center justify-center gap-1 mx-auto w-max shadow-sm">
                                    <i class="ph ph-currency-dollar text-lg"></i> Cobrar y Entregar
                                </a>
                            @else
                                <span class="text-xs text-[#98A9BE] font-medium italic">En proceso...</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-[#728495]">
                            <i class="ph ph-empty text-4xl mb-2 block"></i>
                            No hay vehículos en el taller actualmente.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#B4C5D8] flex items-center justify-between bg-gray-50">
            <span class="text-sm text-[#728495] font-medium">Total de órdenes activas: {{ $ordenes->count() }}</span>
        </div>
    </div>

@endsection
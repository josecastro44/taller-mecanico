@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Historial de Trabajos</h2>
            <p class="text-[#728495]">Registro de vehículos reparados y servicios completados.</p>
        </div>
        <div class="relative w-full md:w-80">
            <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-[#728495] text-xl"></i>
            <input type="text" placeholder="Buscar por placa o modelo..." class="w-full pl-10 pr-4 py-2 border border-[#B4C5D8] rounded-lg focus:outline-none focus:border-[#4A5B6A] text-[#263A47] bg-white shadow-sm">
        </div>
    </div>

    {{-- TARJETAS DE ESTADÍSTICAS REALES --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-green-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Terminados (Este Mes)</p>
                <p class="text-3xl font-bold text-[#263A47]">{{ $terminadosMes }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="ph ph-check-fat text-2xl text-green-600"></i>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-[#4A5B6A]">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Servicios Realizados</p>
                <p class="text-3xl font-bold text-[#263A47]">{{ $totalServicios }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-[#B4C5D8]/30 flex items-center justify-center">
                <i class="ph ph-wrench text-2xl text-[#4A5B6A]"></i>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-purple-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Total Histórico</p>
                <p class="text-3xl font-bold text-[#263A47]">{{ $historial->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center">
                <i class="ph ph-car-profile text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>

    {{-- TABLA DE HISTORIAL DINÁMICA --}}
    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-[#B4C5D8] bg-[#B4C5D8]/10 flex justify-between items-center">
            <h3 class="text-lg font-bold text-[#263A47]">Últimos Trabajos Entregados</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Fecha de Entrega</th>
                        <th class="px-6 py-4 font-bold">Vehículo / Placa</th>
                        <th class="px-6 py-4 font-bold">Servicio Realizado</th>
                        <th class="px-6 py-4 font-bold text-center">Estatus</th>
                        <th class="px-6 py-4 font-bold text-center border-l border-[#B4C5D8]/50">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    
                    @forelse($historial as $orden)
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-medium text-[#728495]">{{ $orden->updated_at->format('d/m/Y h:i A') }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold">{{ $orden->vehiculo->marca }} {{ $orden->vehiculo->modelo }}</p>
                            <p class="text-xs font-mono text-[#98A9BE] mt-0.5">{{ $orden->vehiculo->placa }}</p>
                        </td>
                        <td class="px-6 py-4 font-medium text-[#4A5B6A]">
                            {{-- Mostramos los servicios unidos por coma --}}
                            {{ $orden->servicios->pluck('descripcion')->join(', ') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">
                                <i class="ph ph-check mr-1"></i> {{ $orden->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            {{-- Pasamos el ID único de la orden al modal --}}
                            <button onclick="openModal('modal-historial-{{ $orden->id }}')" class="bg-[#F3F6F8] border border-[#B4C5D8] text-[#4A5B6A] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#E2E8F0] transition flex items-center justify-center gap-1 mx-auto">
                                <i class="ph ph-eye text-lg"></i> Ver Detalles
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-[#728495] font-medium">
                            <i class="ph ph-empty text-4xl mb-2 block"></i>
                            Aún no has finalizado ningún trabajo.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-[#B4C5D8] flex items-center justify-between bg-white">
            <span class="text-sm text-[#728495] font-medium">Total de registros: {{ $historial->count() }}</span>
        </div>
    </div>

    {{-- MODALES DINÁMICOS (Uno por cada orden) --}}
    @foreach($historial as $orden)
    <div id="modal-historial-{{ $orden->id }}" class="hidden fixed inset-0 bg-[#263A47]/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4" onclick="closeModal('modal-historial-{{ $orden->id }}')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col" onclick="event.stopPropagation()">
            
            <div class="bg-green-600 p-6 text-white flex justify-between items-start">
                <div>
                    <span class="bg-green-800/50 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-2 inline-block">Trabajo {{ $orden->estado }}</span>
                    <h3 class="text-2xl font-black">O.S. 00{{ $orden->id }}</h3>
                    <p class="text-green-100 text-sm mt-1">Finalizado el {{ $orden->updated_at->format('d/m/Y') }} a las {{ $orden->updated_at->format('h:i A') }}</p>
                </div>
                <button onclick="closeModal('modal-historial-{{ $orden->id }}')" class="text-green-200 hover:text-white transition focus:outline-none">
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <p class="text-xs text-[#98A9BE] uppercase font-bold mb-1">Vehículo</p>
                        <p class="font-bold text-[#263A47]">{{ $orden->vehiculo->marca }} {{ $orden->vehiculo->modelo }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <p class="text-xs text-[#98A9BE] uppercase font-bold mb-1">Placa</p>
                        <p class="font-bold text-[#263A47] font-mono">{{ $orden->vehiculo->placa }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider mb-2">Diagnóstico Inicial / Notas</p>
                    <div class="text-sm text-[#4A5B6A] bg-green-50 p-4 rounded-xl border border-green-200 leading-relaxed italic">
                        <i class="ph ph-check-circle text-green-600 mr-1 text-lg"></i>
                        "{{ $orden->diagnostico }}"
                    </div>
                </div>

                <div>
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider mb-2">Repuestos Utilizados</p>
                    <ul class="text-sm text-[#4A5B6A] bg-white rounded-xl border border-[#B4C5D8]/50 divide-y divide-[#B4C5D8]/30 max-h-32 overflow-y-auto">
                        @forelse($orden->repuestos as $repuesto)
                        <li class="p-3 flex justify-between items-center">
                            <span class="font-medium"><i class="ph ph-nut text-[#728495] mr-2"></i>{{ $repuesto->nombre }}</span>
                            <span class="font-bold text-[#263A47] bg-[#B4C5D8]/20 px-2 py-0.5 rounded">x{{ $repuesto->pivot->cantidad }}</span>
                        </li>
                        @empty
                        <li class="p-3 text-center text-[#98A9BE] italic">No se utilizaron repuestos.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border-t border-[#B4C5D8]/50 flex justify-end">
                <button onclick="closeModal('modal-historial-{{ $orden->id }}')" class="px-5 py-2 bg-white border border-[#B4C5D8] text-[#4A5B6A] font-bold hover:bg-gray-100 rounded-lg transition shadow-sm">
                    Cerrar Detalles
                </button>
            </div>
        </div>
    </div>
    @endforeach

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>

@endsection
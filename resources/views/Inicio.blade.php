@extends('layouts.app')

@section('contenido')
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-blue-600">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Total Activas</p>
                <p class="text-3xl font-bold text-[#263A47]">{{ $stats['total_activas'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="ph ph-car text-2xl text-blue-600"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-yellow-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">En Espera</p>
                <p class="text-3xl font-bold text-[#263A47]">{{ $stats['en_espera'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-yellow-50 flex items-center justify-center">
                <i class="ph ph-clock text-2xl text-yellow-600"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-amber-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">En Reparación</p>
                <p class="text-3xl font-bold text-[#263A47]">{{ $stats['en_reparacion'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center">
                <i class="ph ph-wrench text-2xl text-amber-600"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-green-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Terminados Hoy</p>
                <p class="text-3xl font-bold text-[#263A47]">{{ $stats['finalizados_hoy'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="ph ph-check-circle text-2xl text-green-600"></i>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm p-6 relative">
        
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-[#263A47]">Flujo de Trabajo del Taller</h3>
            <a href="{{ route('monitor') }}" class="text-sm bg-[#4A5B6A] text-white px-4 py-2 rounded hover:bg-[#263A47] transition">
                <i class="ph ph-monitor text-lg align-middle mr-1"></i> Abrir Monitor
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-[#B4C5D8] text-[#728495] text-sm">
                        <th class="pb-3 font-semibold">O.S. / Placa</th>
                        <th class="pb-3 font-semibold">Vehículo</th>
                        <th class="pb-3 font-semibold">Mecánico</th>
                        <th class="pb-3 font-semibold">Estatus</th>
                        <th class="pb-3 font-semibold text-right">Detalles</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    
                    @forelse($ordenes as $orden)
                        @if(in_array($orden->estado, ['En Espera', 'En Reparación', 'Finalizado']))
                        <tr class="border-b border-[#B4C5D8]/50 hover:bg-[#B4C5D8]/10 transition">
                            <td class="py-4 font-medium">
                                <p class="font-bold text-[#263A47]">#00{{ $orden->id }}</p>
                                <p class="text-xs text-[#728495]">{{ $orden->vehiculo->placa ?? '' }}</p>
                            </td>
                            <td class="py-4">{{ $orden->vehiculo->marca ?? '' }} {{ $orden->vehiculo->modelo ?? '' }}</td>
                            <td class="py-4">{{ $orden->mecanico->nombre ?? 'Sin Asignar' }}</td>
                            <td class="py-4">
                                @if($orden->estado == 'En Espera')
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-[11px] font-bold border border-gray-200 inline-flex items-center gap-1"><i class="ph ph-clock"></i> En Espera</span>
                                @elseif($orden->estado == 'En Reparación')
                                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[11px] font-bold border border-amber-200 inline-flex items-center gap-1"><i class="ph ph-wrench pulse-soft"></i> En Taller</span>
                                @elseif($orden->estado == 'Finalizado')
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[11px] font-bold border border-blue-200 inline-flex items-center gap-1"><i class="ph ph-check-circle"></i> Listo p/ Entrega</span>
                                @endif
                            </td>
                            <td class="py-4 text-right">
                                <button onclick="openModal('modal-os-{{ $orden->id }}')" class="inline-flex items-center gap-1 bg-[#F3F6F8] border border-[#B4C5D8] text-[#4A5B6A] px-3 py-1.5 rounded-md text-xs font-bold hover:bg-[#E2E8F0] transition focus:outline-none">
                                    <i class="ph ph-eye text-lg"></i> Ver Detalles
                                    <i class="ph ph-caret-right ml-1 text-[#98A9BE]"></i>
                                </button>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-[#728495] font-medium">No hay órdenes activas en el taller.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    {{-- Modales Dinámicos --}}
    @foreach($ordenes as $orden)
    @if(in_array($orden->estado, ['En Espera', 'En Reparación', 'Finalizado']))
    <div id="modal-os-{{ $orden->id }}" class="hidden fixed inset-0 bg-[#263A47]/40 backdrop-blur-sm z-[100] flex items-center justify-center p-4" onclick="closeModal('modal-os-{{ $orden->id }}')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]" onclick="event.stopPropagation()">
            
            <div class="flex items-center justify-between p-4 border-b border-[#B4C5D8]/50">
                <h3 class="text-lg font-bold text-[#263A47]">Detalles de la Orden #00{{ $orden->id }}</h3>
                <button onclick="closeModal('modal-os-{{ $orden->id }}')" class="text-[#728495] hover:text-red-500 transition focus:outline-none">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-5 overflow-y-auto">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-[#263A47] text-xl mb-1">{{ $orden->vehiculo->marca ?? '' }} {{ $orden->vehiculo->modelo ?? '' }}</h4>
                        <p class="text-sm text-[#728495]">Placa: {{ $orden->vehiculo->placa ?? '' }}</p>
                    </div>
                    <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-md font-bold uppercase tracking-wider border border-gray-300">{{ $orden->prioridad }}</span>
                </div>
                
                <div>
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider mb-2">Mecánico Asignado</p>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600"><i class="ph ph-user text-lg"></i></div>
                        <span class="text-sm text-[#263A47] font-semibold">{{ $orden->mecanico->nombre ?? 'Sin Asignar' }}</span>
                    </div>
                </div>
                
                <div>
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider mb-2">Diagnóstico Inicial</p>
                    <div class="text-sm text-[#4A5B6A] bg-[#F3F6F8] p-4 rounded-xl border border-[#B4C5D8]/50 leading-relaxed italic">
                        "{{ $orden->diagnostico }}"
                    </div>
                </div>

                <div>
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider mb-2">Servicios Solicitados</p>
                    <ul class="text-sm text-[#4A5B6A] bg-white rounded-xl border border-[#B4C5D8]/50 divide-y divide-[#B4C5D8]/30">
                        @forelse($orden->servicios as $servicio)
                        <li class="p-3 flex justify-between items-center hover:bg-[#F3F6F8] transition">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-wrench text-[#728495] text-lg"></i>
                                <span>{{ $servicio->descripcion }}</span>
                            </div>
                            <span class="font-bold text-green-600">${{ number_format($servicio->pivot->precio_cobrado ?? 0, 2) }}</span>
                        </li>
                        @empty
                        <li class="p-3 text-center text-sm text-gray-500">Sin servicios registrados</li>
                        @endforelse
                    </ul>
                </div>
                
                @if($orden->repuestos && count($orden->repuestos) > 0)
                <div>
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider mb-2">Repuestos Utilizados</p>
                    <ul class="text-sm text-[#4A5B6A] bg-white rounded-xl border border-[#B4C5D8]/50 divide-y divide-[#B4C5D8]/30">
                        @foreach($orden->repuestos as $repuesto)
                        <li class="p-3 flex justify-between items-center hover:bg-[#F3F6F8] transition">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-package text-[#728495] text-lg"></i>
                                <span>{{ $repuesto->nombre }}</span>
                            </div>
                            <span class="font-bold text-[#263A47] bg-[#B4C5D8]/20 px-2 py-0.5 rounded">x{{ $repuesto->pivot->cantidad }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div class="p-4 bg-[#F3F6F8] border-t border-[#B4C5D8]/50 flex justify-end gap-3 mt-auto">
                <button onclick="closeModal('modal-os-{{ $orden->id }}')" class="px-4 py-2 text-[#4A5B6A] text-sm font-bold hover:bg-[#E2E8F0] rounded-lg transition">Cerrar</button>
            </div>
        </div>
    </div>
    @endif
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
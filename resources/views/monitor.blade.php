@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47] flex items-center gap-2">
                <i class="ph ph-monitor-play text-[#4A5B6A]"></i> Monitor de Taller y Entregas
            </h2>
            <p class="text-sm text-[#728495]">Supervisa en tiempo real el estatus de todos los vehículos ingresados.</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Búsqueda funcional --}}
            <div class="relative w-full md:w-72">
                <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-[#728495]"></i>
                <input type="text" id="buscar-monitor" placeholder="Buscar placa o mecánico..." 
                    class="w-full pl-9 pr-4 py-2.5 border border-[#B4C5D8] rounded-lg focus:outline-none focus:border-[#4A5B6A] focus:ring-1 focus:ring-[#4A5B6A]/20 text-sm text-[#263A47] bg-white shadow-sm"
                    onkeyup="buscarMonitor()">
            </div>
            {{-- Indicador de en vivo --}}
            <div class="flex items-center gap-1.5 bg-green-50 px-3 py-2 rounded-lg border border-green-200 flex-shrink-0">
                <span class="status-dot active bg-green-500"></span>
                <span class="text-xs font-bold text-green-700">En vivo</span>
            </div>
        </div>
    </div>

    {{-- Resumen rápido --}}
    <div class="grid grid-cols-4 gap-3 mb-6">
        <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-center">
            <p class="text-xs text-[#728495] font-semibold">En Espera</p>
            <p class="text-2xl font-black text-gray-600">{{ $ordenes->where('estado', 'En Espera')->count() }}</p>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-center">
            <p class="text-xs text-amber-600 font-semibold">En Taller</p>
            <p class="text-2xl font-black text-amber-700">{{ $ordenes->where('estado', 'En Reparación')->count() }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-center">
            <p class="text-xs text-blue-600 font-semibold">Listos</p>
            <p class="text-2xl font-black text-blue-700">{{ $ordenes->where('estado', 'Finalizado')->count() }}</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-center">
            <p class="text-xs text-green-600 font-semibold">Entregados</p>
            <p class="text-2xl font-black text-green-700">{{ $ordenes->where('estado', 'Entregado')->count() }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/30 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFB] border-b-2 border-[#B4C5D8]/50 text-[#4A5B6A] text-[11px] uppercase tracking-wider">
                        <th class="px-5 py-3.5 font-bold">O.S. / Fecha</th>
                        <th class="px-5 py-3.5 font-bold">Vehículo</th>
                        <th class="px-5 py-3.5 font-bold">Mecánico</th>
                        <th class="px-5 py-3.5 font-bold">Progreso</th>
                        <th class="px-5 py-3.5 font-bold text-center">Estatus</th>
                        <th class="px-5 py-3.5 font-bold text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]" id="monitor-tbody">
                    @forelse($ordenes as $orden)
                    <tr class="border-b border-[#B4C5D8]/20 hover:bg-[#F1F4F8]/50 transition monitor-row"
                        data-placa="{{ $orden->vehiculo->placa ?? '' }}"
                        data-mecanico="{{ $orden->mecanico->nombre ?? '' }}">
                        <td class="px-5 py-4">
                            <p class="font-bold text-[#263A47]">#00{{ $orden->id }}</p>
                            <p class="text-[11px] text-[#728495]">{{ $orden->created_at->format('d M, h:i A') }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-bold">{{ $orden->vehiculo->marca ?? '' }} {{ $orden->vehiculo->modelo ?? '' }}</p>
                            <p class="text-xs font-mono font-bold text-[#98A9BE]">{{ $orden->vehiculo->placa ?? '' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-[#4A5B6A]/10 flex items-center justify-center">
                                    <i class="ph ph-user text-xs text-[#4A5B6A]"></i>
                                </div>
                                <span class="font-medium text-[#4A5B6A]">{{ $orden->mecanico ? $orden->mecanico->nombre : 'Sin asignar' }}</span>
                            </div>
                        </td>

                        {{-- Timeline visual compacto --}}
                        <td class="px-5 py-4">
                            @php $paso = $orden->pasoTimeline(); @endphp
                            <div class="flex items-center gap-1">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] {{ $paso >= 1 ? 'bg-[#263A47] text-white' : 'bg-gray-200 text-gray-400' }}">
                                    <i class="ph ph-car-profile"></i>
                                </div>
                                <div class="flex-1 h-0.5 {{ $paso >= 2 ? 'bg-amber-400' : 'bg-gray-200' }}"></div>
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] {{ $paso >= 2 ? 'bg-amber-400 text-white' : 'bg-gray-200 text-gray-400' }}">
                                    <i class="ph ph-wrench"></i>
                                </div>
                                <div class="flex-1 h-0.5 {{ $paso >= 3 ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] {{ $paso >= 3 ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                    <i class="ph ph-check"></i>
                                </div>
                                <div class="flex-1 h-0.5 {{ $paso >= 4 ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] {{ $paso >= 4 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                    <i class="ph ph-flag-checkered"></i>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center">
                            @if($orden->estado == 'En Espera')
                                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-[11px] font-bold border border-gray-200 inline-flex items-center gap-1"><i class="ph ph-clock"></i> En Espera</span>
                            @elseif($orden->estado == 'En Reparación')
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[11px] font-bold border border-amber-200 inline-flex items-center gap-1"><i class="ph ph-wrench pulse-soft"></i> En Taller</span>
                                <p class="text-[10px] text-amber-600 font-semibold mt-0.5">{{ $orden->tiempoEnEtapaActual() }}</p>
                            @elseif($orden->estado == 'Finalizado')
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[11px] font-bold border border-blue-200 inline-flex items-center gap-1"><i class="ph ph-check-circle"></i> Listo p/ Entrega</span>
                            @elseif($orden->estado == 'Entregado')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[11px] font-bold border border-green-200 inline-flex items-center gap-1"><i class="ph ph-car-profile"></i> Entregado</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($orden->estado == 'Finalizado')
                                <a href="{{ route('finanzas.preparar', $orden->id) }}" class="btn-scale bg-green-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-green-700 transition flex items-center justify-center gap-1 mx-auto w-max shadow-sm">
                                    <i class="ph ph-currency-dollar text-base"></i> Cobrar y Entregar
                                </a>
                            @elseif($orden->estado == 'Entregado')
                                <span class="text-xs text-green-600 font-bold"><i class="ph ph-check-circle"></i> Completado</span>
                            @else
                                <span class="text-xs text-[#98A9BE] font-medium italic">En proceso...</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-[#728495]">
                            <i class="ph ph-car-simple text-5xl mb-2 block text-[#B4C5D8]"></i>
                            No hay vehículos en el taller actualmente.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-[#B4C5D8]/30 flex items-center justify-between bg-[#F8FAFB]">
            <span class="text-xs text-[#728495] font-medium">Total de órdenes: <b>{{ $ordenes->count() }}</b></span>
            <span class="text-xs text-[#728495]" id="monitor-timestamp">Última actualización: ahora</span>
        </div>
    </div>

    <script>
        // Búsqueda funcional en el monitor
        function buscarMonitor() {
            const buscar = document.getElementById('buscar-monitor').value.toLowerCase();
            document.querySelectorAll('.monitor-row').forEach(row => {
                const placa = (row.dataset.placa || '').toLowerCase();
                const mecanico = (row.dataset.mecanico || '').toLowerCase();
                row.style.display = (placa.includes(buscar) || mecanico.includes(buscar)) ? '' : 'none';
            });
        }

        // Auto-refresh cada 30 segundos (recarga la página completa para el monitor)
        setTimeout(() => location.reload(), 30000);
    </script>

@endsection
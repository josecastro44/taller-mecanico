@extends('layouts.app')

@section('contenido')

    {{-- ========================================== --}}
    {{-- TARJETAS KPI DINÁMICAS --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        
        {{-- En Espera --}}
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1 uppercase tracking-wider">En Espera</p>
                <p class="text-3xl font-black text-[#263A47]" id="kpi-espera">{{ $stats['en_espera'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                <i class="ph ph-clock text-2xl text-gray-500"></i>
            </div>
        </div>

        {{-- En Reparación --}}
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 border-l-amber-400">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1 uppercase tracking-wider">En Reparación</p>
                <p class="text-3xl font-black text-[#263A47]" id="kpi-reparacion">{{ $stats['en_reparacion'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class="ph ph-wrench text-2xl text-amber-500 pulse-soft"></i>
            </div>
        </div>

        {{-- Finalizados Hoy --}}
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 border-l-green-500">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1 uppercase tracking-wider">Finalizados Hoy</p>
                <p class="text-3xl font-black text-[#263A47]" id="kpi-finalizados">{{ $stats['finalizados_hoy'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                <i class="ph ph-check-circle text-2xl text-green-500"></i>
            </div>
        </div>

        {{-- Tiempo Promedio --}}
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm flex items-center justify-between border-l-4 border-l-blue-500">
            <div>
                <p class="text-xs text-[#728495] font-semibold mb-1 uppercase tracking-wider">Tiempo Promedio</p>
                <p class="text-2xl font-black text-[#263A47]" id="kpi-tiempo">{{ $stats['tiempo_promedio'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="ph ph-timer text-2xl text-blue-500"></i>
            </div>
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- FILTROS POR ESTADO (TABS) --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-xl border border-[#98A9BE]/30 shadow-sm overflow-hidden">
        
        <div class="px-4 md:px-6 pt-5 pb-0 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-[#263A47] flex items-center gap-2">
                    <i class="ph ph-gauge text-[#4A5B6A]"></i> Panel de Órdenes de Servicio
                </h3>
                <p class="text-xs text-[#728495] mt-0.5">Actualización automática cada 15 segundos</p>
            </div>
            
            <div class="flex items-center gap-3">
                {{-- Búsqueda --}}
                <div class="relative">
                    <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-[#728495]"></i>
                    <input type="text" id="buscar-placa" placeholder="Buscar placa..." 
                        class="pl-9 pr-4 py-2 border border-[#B4C5D8] rounded-lg text-sm focus:outline-none focus:border-[#4A5B6A] focus:ring-1 focus:ring-[#4A5B6A]/20 w-40 md:w-52 bg-[#F8FAFB]"
                        onkeyup="filtrarOrdenes()">
                </div>
                
                {{-- Indicador de auto-refresh --}}
                <div class="flex items-center gap-1.5 bg-green-50 px-3 py-1.5 rounded-lg border border-green-200">
                    <span class="status-dot active bg-green-500"></span>
                    <span class="text-xs font-semibold text-green-700">En vivo</span>
                </div>
            </div>
        </div>

        {{-- Tabs de estado --}}
        <div class="px-4 md:px-6 pt-4 flex gap-1 border-b border-[#B4C5D8]/30">
            <button onclick="filtrarPorEstado('todos')" class="tab-btn tab-active px-4 py-2.5 text-sm font-semibold rounded-t-lg transition-all" data-tab="todos">
                Todos <span class="ml-1 bg-[#263A47] text-white text-xs px-2 py-0.5 rounded-full" id="count-todos">{{ $ordenes->count() }}</span>
            </button>
            <button onclick="filtrarPorEstado('En Espera')" class="tab-btn px-4 py-2.5 text-sm font-semibold rounded-t-lg transition-all text-[#728495] hover:text-[#263A47]" data-tab="En Espera">
                <i class="ph ph-clock"></i> Espera <span class="ml-1 text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600" id="count-espera">{{ $ordenes->where('estado', 'En Espera')->count() }}</span>
            </button>
            <button onclick="filtrarPorEstado('En Reparación')" class="tab-btn px-4 py-2.5 text-sm font-semibold rounded-t-lg transition-all text-[#728495] hover:text-[#263A47]" data-tab="En Reparación">
                <i class="ph ph-wrench"></i> Taller <span class="ml-1 text-xs px-2 py-0.5 rounded-full bg-amber-100 text-amber-600" id="count-reparacion">{{ $ordenes->where('estado', 'En Reparación')->count() }}</span>
            </button>
            <button onclick="filtrarPorEstado('Finalizado')" class="tab-btn px-4 py-2.5 text-sm font-semibold rounded-t-lg transition-all text-[#728495] hover:text-[#263A47]" data-tab="Finalizado">
                <i class="ph ph-check-circle"></i> Listos <span class="ml-1 text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-600" id="count-finalizado">{{ $ordenes->where('estado', 'Finalizado')->count() }}</span>
            </button>
            <button onclick="filtrarPorEstado('Entregado')" class="tab-btn px-4 py-2.5 text-sm font-semibold rounded-t-lg transition-all text-[#728495] hover:text-[#263A47]" data-tab="Entregado">
                <i class="ph ph-car-profile"></i> Entregados
            </button>
        </div>

        {{-- ========================================== --}}
        {{-- TABLA DE ÓRDENES CON TIMELINE --}}
        {{-- ========================================== --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="tabla-ordenes">
                <thead>
                    <tr class="bg-[#F8FAFB] text-[#4A5B6A] text-[11px] uppercase tracking-wider border-b border-[#B4C5D8]/50">
                        <th class="px-4 md:px-6 py-3 font-bold">O.S. / Fecha</th>
                        <th class="px-4 md:px-6 py-3 font-bold">Vehículo</th>
                        <th class="px-4 md:px-6 py-3 font-bold">Mecánico</th>
                        <th class="px-4 md:px-6 py-3 font-bold">Progreso</th>
                        <th class="px-4 md:px-6 py-3 font-bold text-center">Tiempo</th>
                        <th class="px-4 md:px-6 py-3 font-bold text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]" id="tbody-ordenes">
                    @forelse($ordenes as $orden)
                    <tr class="border-b border-[#B4C5D8]/20 hover:bg-[#F1F4F8]/60 transition orden-row" 
                        data-estado="{{ $orden->estado }}" 
                        data-placa="{{ $orden->vehiculo->placa ?? '' }}">
                        
                        {{-- O.S. y Fecha --}}
                        <td class="px-4 md:px-6 py-4">
                            <p class="font-bold text-[#263A47]">#00{{ $orden->id }}</p>
                            <p class="text-[11px] text-[#728495]">{{ $orden->created_at->format('d M, h:i A') }}</p>
                        </td>
                        
                        {{-- Vehículo --}}
                        <td class="px-4 md:px-6 py-4">
                            <p class="font-bold">{{ $orden->vehiculo->marca ?? '' }} {{ $orden->vehiculo->modelo ?? '' }}</p>
                            <p class="text-xs font-mono font-bold text-[#98A9BE]">{{ $orden->vehiculo->placa ?? '' }}</p>
                        </td>
                        
                        {{-- Mecánico --}}
                        <td class="px-4 md:px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-[#4A5B6A]/10 flex items-center justify-center">
                                    <i class="ph ph-user text-xs text-[#4A5B6A]"></i>
                                </div>
                                <span class="font-medium text-sm text-[#4A5B6A]">{{ $orden->mecanico->nombre ?? 'Sin asignar' }}</span>
                            </div>
                        </td>
                        
                        {{-- Timeline Visual --}}
                        <td class="px-4 md:px-6 py-4">
                            @php $paso = $orden->pasoTimeline(); @endphp
                            <div class="flex items-center gap-1">
                                {{-- Paso 1: Recepción --}}
                                <div class="flex flex-col items-center">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ $paso >= 1 ? 'bg-[#263A47] text-white' : 'bg-gray-200 text-gray-400' }}">
                                        <i class="ph ph-car-profile"></i>
                                    </div>
                                </div>
                                <div class="flex-1 h-1 rounded {{ $paso >= 2 ? 'bg-amber-400' : 'bg-gray-200' }}"></div>
                                
                                {{-- Paso 2: En Reparación --}}
                                <div class="flex flex-col items-center">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ $paso >= 2 ? 'bg-amber-400 text-white' : 'bg-gray-200 text-gray-400' }} {{ $paso == 2 ? 'ring-2 ring-amber-200 ring-offset-1' : '' }}">
                                        <i class="ph ph-wrench"></i>
                                    </div>
                                </div>
                                <div class="flex-1 h-1 rounded {{ $paso >= 3 ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                                
                                {{-- Paso 3: Finalizado --}}
                                <div class="flex flex-col items-center">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ $paso >= 3 ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400' }} {{ $paso == 3 ? 'ring-2 ring-blue-200 ring-offset-1' : '' }}">
                                        <i class="ph ph-check"></i>
                                    </div>
                                </div>
                                <div class="flex-1 h-1 rounded {{ $paso >= 4 ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                                
                                {{-- Paso 4: Entregado --}}
                                <div class="flex flex-col items-center">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ $paso >= 4 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                        <i class="ph ph-flag-checkered"></i>
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Tiempo en etapa --}}
                        <td class="px-4 md:px-6 py-4 text-center">
                            @if($orden->estado == 'Entregado')
                                <span class="text-xs font-bold text-green-600 bg-green-50 px-2.5 py-1 rounded-lg">Completado</span>
                            @else
                                <span class="text-sm font-bold text-[#263A47] tabular-nums cronometro-celda" 
                                    data-inicio="{{ $orden->estado == 'En Reparación' ? ($orden->fecha_inicio_reparacion ?? $orden->created_at)->toISOString() : ($orden->fecha_recepcion ?? $orden->created_at)->toISOString() }}">
                                    {{ $orden->tiempoEnEtapaActual() }}
                                </span>
                            @endif
                        </td>

                        {{-- Acción --}}
                        <td class="px-4 md:px-6 py-4 text-center">
                            @if($orden->estado == 'Finalizado')
                                <a href="{{ route('finanzas.preparar', $orden->id) }}" class="btn-scale bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-green-700 transition inline-flex items-center gap-1 shadow-sm">
                                    <i class="ph ph-currency-dollar text-base"></i> Cobrar
                                </a>
                            @elseif($orden->estado == 'Entregado')
                                <span class="text-xs text-green-600 font-bold flex items-center justify-center gap-1">
                                    <i class="ph ph-check-circle"></i> Listo
                                </span>
                            @else
                                @if($orden->estado == 'En Espera')
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-lg">
                                        <i class="ph ph-hourglass-medium"></i> Esperando
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg">
                                        <i class="ph ph-wrench pulse-soft"></i> En taller
                                    </span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <i class="ph ph-car-simple text-5xl text-[#B4C5D8] block mb-3"></i>
                            <p class="text-[#728495] font-medium">No hay vehículos en el taller actualmente.</p>
                            <a href="/recepcion" class="text-[#4A5B6A] text-sm font-bold mt-2 inline-block hover:underline">
                                <i class="ph ph-plus-circle"></i> Registrar nueva orden
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pie de tabla --}}
        <div class="px-4 md:px-6 py-3 border-t border-[#B4C5D8]/30 flex items-center justify-between bg-[#F8FAFB]">
            <span class="text-xs text-[#728495] font-medium">
                <i class="ph ph-info"></i> Total de órdenes: <b>{{ $ordenes->count() }}</b>
            </span>
            <span class="text-xs text-[#728495] font-medium" id="ultima-actualizacion">
                Última actualización: ahora
            </span>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- SCRIPTS DEL DASHBOARD --}}
    {{-- ========================================== --}}
    <style>
        .tab-active { background: white; color: #263A47; border-bottom: 2px solid #263A47; }
        .tab-btn:not(.tab-active) { border-bottom: 2px solid transparent; }
    </style>

    <script>
        // ============================================
        // FILTRAR POR TABS DE ESTADO
        // ============================================
        let estadoActivo = 'todos';

        function filtrarPorEstado(estado) {
            estadoActivo = estado;
            
            // Actualizar estilos de tabs
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('tab-active');
                btn.classList.add('text-[#728495]');
            });
            event.target.closest('.tab-btn').classList.add('tab-active');
            event.target.closest('.tab-btn').classList.remove('text-[#728495]');

            // Filtrar filas
            document.querySelectorAll('.orden-row').forEach(row => {
                if (estado === 'todos' || row.dataset.estado === estado) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // ============================================
        // BÚSQUEDA POR PLACA
        // ============================================
        function filtrarOrdenes() {
            const buscar = document.getElementById('buscar-placa').value.toLowerCase();
            document.querySelectorAll('.orden-row').forEach(row => {
                const placa = (row.dataset.placa || '').toLowerCase();
                const visible = estadoActivo === 'todos' || row.dataset.estado === estadoActivo;
                if (placa.includes(buscar) && visible) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // ============================================
        // CRONÓMETROS EN TIEMPO REAL
        // ============================================
        function actualizarCronometros() {
            document.querySelectorAll('.cronometro-celda').forEach(el => {
                const inicio = new Date(el.dataset.inicio);
                const ahora = new Date();
                const diff = Math.floor((ahora - inicio) / 1000);
                
                const dias = Math.floor(diff / 86400);
                const horas = Math.floor((diff % 86400) / 3600);
                const minutos = Math.floor((diff % 3600) / 60);
                
                if (dias > 0) {
                    el.textContent = `${dias}d ${horas}h`;
                } else if (horas > 0) {
                    el.textContent = `${horas}h ${minutos}min`;
                } else {
                    el.textContent = `${minutos}min`;
                }
            });
        }
        setInterval(actualizarCronometros, 60000); // Cada minuto

        // ============================================
        // AUTO-REFRESH AJAX (cada 15 segundos)
        // ============================================
        function refrescarDashboard() {
            // Refrescar KPIs
            fetch('/api/dashboard/stats')
                .then(r => r.json())
                .then(data => {
                    const campos = {
                        'kpi-espera': data.en_espera,
                        'kpi-reparacion': data.en_reparacion,
                        'kpi-finalizados': data.finalizados_hoy,
                        'kpi-tiempo': data.tiempo_promedio
                    };
                    for (const [id, valor] of Object.entries(campos)) {
                        const el = document.getElementById(id);
                        if (el && el.textContent != valor) {
                            el.textContent = valor;
                            el.style.transition = 'color 0.3s';
                            el.style.color = '#059669';
                            setTimeout(() => el.style.color = '', 1500);
                        }
                    }

                    // Actualizar timestamp
                    const ts = document.getElementById('ultima-actualizacion');
                    if (ts) {
                        const ahora = new Date();
                        ts.textContent = `Última actualización: ${ahora.getHours().toString().padStart(2,'0')}:${ahora.getMinutes().toString().padStart(2,'0')}:${ahora.getSeconds().toString().padStart(2,'0')}`;
                    }
                })
                .catch(err => console.warn('Error actualizando KPIs:', err));
        }

        // Iniciar polling
        setInterval(refrescarDashboard, 15000);
    </script>

@endsection

@extends('layouts.app')

@section('contenido')

    {{-- ========================================== --}}
    {{-- ENCABEZADO CON FILTROS --}}
    {{-- ========================================== --}}
    <div class="mb-6 flex flex-col lg:flex-row lg:justify-between lg:items-end gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#263A47] flex items-center gap-2">
                <i class="ph ph-chart-bar text-[#4A5B6A] text-3xl"></i>
                Dashboard de Estadísticas y Reportes
            </h1>
            <p class="text-sm text-[#728495] mt-1">Análisis de rendimiento, ventas y operatividad del taller — Datos en tiempo real.</p>
        </div>
        
        <div class="flex flex-wrap items-end gap-3">
            {{-- Filtros de fecha --}}
            <form method="GET" action="{{ route('reportes') }}" class="flex flex-wrap items-end gap-2">
                <div>
                    <label class="block text-[10px] font-bold text-[#728495] uppercase tracking-wider mb-1">Desde</label>
                    <input type="date" name="desde" value="{{ $desde->format('Y-m-d') }}" class="border border-[#B4C5D8] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#4A5B6A] bg-white">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-[#728495] uppercase tracking-wider mb-1">Hasta</label>
                    <input type="date" name="hasta" value="{{ $hasta->format('Y-m-d') }}" class="border border-[#B4C5D8] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#4A5B6A] bg-white">
                </div>
                <button type="submit" class="btn-scale bg-[#263A47] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#4A5B6A] transition flex items-center gap-1">
                    <i class="ph ph-funnel text-base"></i> Filtrar
                </button>
            </form>

            {{-- Botones de exportación --}}
            <div class="flex gap-2">
                <a href="{{ route('reportes.pdf', ['desde' => $desde->format('Y-m-d'), 'hasta' => $hasta->format('Y-m-d')]) }}" target="_blank" class="btn-scale bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition flex items-center gap-1">
                    <i class="ph ph-file-pdf text-base"></i> PDF
                </a>
                <a href="{{ route('reportes.csv', ['desde' => $desde->format('Y-m-d'), 'hasta' => $hasta->format('Y-m-d')]) }}" class="btn-scale bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition flex items-center gap-1">
                    <i class="ph ph-file-csv text-base"></i> CSV
                </a>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- TARJETAS KPI REALES --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm border-l-4 border-l-green-500">
            <p class="text-[10px] text-[#728495] font-bold uppercase tracking-wider mb-1">Ingresos del Mes</p>
            <p class="text-2xl font-black text-[#263A47] tabular-nums">$ {{ number_format($kpis['ingresos_mes'], 2) }}</p>
        </div>
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm border-l-4 border-l-blue-500">
            <p class="text-[10px] text-[#728495] font-bold uppercase tracking-wider mb-1">Órdenes Completadas</p>
            <p class="text-2xl font-black text-[#263A47]">{{ $kpis['ordenes_completadas'] }}</p>
            <p class="text-[11px] text-[#98A9BE]">de {{ $kpis['ordenes_mes'] }} totales</p>
        </div>
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm border-l-4 border-l-purple-500">
            <p class="text-[10px] text-[#728495] font-bold uppercase tracking-wider mb-1">Tiempo Promedio</p>
            <p class="text-2xl font-black text-[#263A47]">{{ $kpis['tiempo_promedio'] }}</p>
        </div>
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm border-l-4 border-l-amber-500">
            <p class="text-[10px] text-[#728495] font-bold uppercase tracking-wider mb-1">Ticket Promedio</p>
            <p class="text-2xl font-black text-[#263A47] tabular-nums">$ {{ number_format($kpis['ticket_promedio'], 2) }}</p>
            <p class="text-[11px] text-red-500 font-semibold">{{ $kpis['inventario_bajo'] }} ítems stock bajo</p>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- GRID DE GRÁFICOS (DATOS REALES) --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">

        {{-- 1. Carros Atendidos --}}
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm">
            <h2 class="text-sm font-bold text-[#4A5B6A] mb-4 flex items-center gap-2 uppercase tracking-wider">
                <i class="ph ph-car text-[#4A5B6A] text-lg"></i> Vehículos Atendidos
            </h2>
            <div class="relative h-56 w-full">
                <canvas id="chartCarros"></canvas>
            </div>
        </div>

        {{-- 2. Ventas Repuestos --}}
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm">
            <h2 class="text-sm font-bold text-[#4A5B6A] mb-4 flex items-center gap-2 uppercase tracking-wider">
                <i class="ph ph-nut text-orange-500 text-lg"></i> Ventas de Repuestos ($)
            </h2>
            <div class="relative h-56 w-full">
                <canvas id="chartRepuestos"></canvas>
            </div>
        </div>

        {{-- 3. Tipos Reparación --}}
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm">
            <h2 class="text-sm font-bold text-[#4A5B6A] mb-4 flex items-center gap-2 uppercase tracking-wider">
                <i class="ph ph-engine text-blue-500 text-lg"></i> Tipos de Servicio
            </h2>
            <div class="relative h-56 w-full flex justify-center">
                <canvas id="chartReparaciones"></canvas>
            </div>
        </div>

        {{-- 4. Balance Financiero --}}
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm">
            <h2 class="text-sm font-bold text-[#4A5B6A] mb-4 flex items-center gap-2 uppercase tracking-wider">
                <i class="ph ph-currency-dollar text-green-500 text-lg"></i> Balance Financiero ($)
            </h2>
            <div class="relative h-56 w-full">
                <canvas id="chartFinanzas"></canvas>
            </div>
        </div>

        {{-- 5. Rendimiento Mecánicos --}}
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm">
            <h2 class="text-sm font-bold text-[#4A5B6A] mb-4 flex items-center gap-2 uppercase tracking-wider">
                <i class="ph ph-users-three text-purple-500 text-lg"></i> Trabajos por Mecánico
            </h2>
            <div class="relative h-56 w-full">
                <canvas id="chartMecanicos"></canvas>
            </div>
        </div>

        {{-- 6. Estado de Órdenes --}}
        <div class="card-hover bg-white p-5 rounded-xl border border-[#98A9BE]/30 shadow-sm">
            <h2 class="text-sm font-bold text-[#4A5B6A] mb-4 flex items-center gap-2 uppercase tracking-wider">
                <i class="ph ph-clipboard-text text-amber-500 text-lg"></i> Estado de Órdenes
            </h2>
            <div class="relative h-56 w-full flex justify-center">
                <canvas id="chartOrdenes"></canvas>
            </div>
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- TABLA DE RENTABILIDAD POR SERVICIO --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-xl border border-[#98A9BE]/30 shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-[#B4C5D8]/40 bg-[#F8FAFB]">
            <h3 class="text-sm font-bold text-[#4A5B6A] flex items-center gap-2 uppercase tracking-wider">
                <i class="ph ph-chart-pie-slice text-[#4A5B6A]"></i> Rentabilidad por Tipo de Servicio
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[11px] uppercase tracking-wider text-[#4A5B6A] border-b border-[#B4C5D8]/40 bg-[#F8FAFB]">
                        <th class="px-5 py-3 font-bold">Servicio</th>
                        <th class="px-5 py-3 font-bold text-center">Cantidad</th>
                        <th class="px-5 py-3 font-bold text-right">Ingresos Generados</th>
                        <th class="px-5 py-3 font-bold text-right">Promedio por Servicio</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($rentabilidadServicios as $servicio)
                    <tr class="border-b border-[#B4C5D8]/20 hover:bg-[#F1F4F8]/50 transition">
                        <td class="px-5 py-3 font-semibold text-[#263A47]">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-[#4A5B6A]"></div>
                                {{ $servicio->nombre }}
                            </div>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="bg-[#263A47]/10 text-[#263A47] px-2.5 py-0.5 rounded-full text-xs font-bold">{{ $servicio->cantidad }}</span>
                        </td>
                        <td class="px-5 py-3 text-right font-bold text-green-600 tabular-nums">$ {{ number_format($servicio->ingresos, 2) }}</td>
                        <td class="px-5 py-3 text-right font-medium text-[#4A5B6A] tabular-nums">
                            $ {{ $servicio->cantidad > 0 ? number_format($servicio->ingresos / $servicio->cantidad, 2) : '0.00' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-[#728495]">No hay datos de servicios en el rango seleccionado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- SCRIPTS DE GRÁFICOS (DATOS REALES) --}}
    {{-- ========================================== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const colorPrimario = '#263A47';
            const colorSecundario = '#4A5B6A';
            
            // Opciones globales de Chart.js
            Chart.defaults.font.family = 'Inter';
            Chart.defaults.plugins.legend.labels.usePointStyle = true;
            Chart.defaults.plugins.legend.labels.padding = 16;

            // 1. Carros Atendidos (datos reales)
            new Chart(document.getElementById('chartCarros'), {
                type: 'bar',
                data: {
                    labels: @json($carrosLabels),
                    datasets: [{
                        label: 'Vehículos',
                        data: @json($carrosData),
                        backgroundColor: colorPrimario,
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });

            // 2. Ventas Repuestos (datos reales)
            new Chart(document.getElementById('chartRepuestos'), {
                type: 'line',
                data: {
                    labels: @json($carrosLabels),
                    datasets: [{
                        label: 'Ventas USD ($)',
                        data: @json($ventasData),
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249, 115, 22, 0.08)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#f97316',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
            });

            // 3. Tipos de Servicio (datos reales)
            const tiposColores = ['#263A47', '#4A5B6A', '#728495', '#f97316', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444'];
            new Chart(document.getElementById('chartReparaciones'), {
                type: 'doughnut',
                data: {
                    labels: @json($tiposLabels),
                    datasets: [{
                        data: @json($tiposData),
                        backgroundColor: tiposColores.slice(0, @json(count($tiposLabels))),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: { legend: { position: 'right', labels: { font: { size: 11 } } } }
                }
            });

            // 4. Balance Financiero (datos reales)
            new Chart(document.getElementById('chartFinanzas'), {
                type: 'bar',
                data: {
                    labels: @json($carrosLabels),
                    datasets: [
                        { label: 'Ingresos', data: @json($ingresosData), backgroundColor: '#10b981', borderRadius: 6, borderSkipped: false },
                        { label: 'Egresos', data: @json($egresosData), backgroundColor: '#ef4444', borderRadius: 6, borderSkipped: false }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
            });

            // 5. Rendimiento Mecánicos (datos reales)
            new Chart(document.getElementById('chartMecanicos'), {
                type: 'bar',
                data: {
                    labels: @json($mecanicosLabels),
                    datasets: [{
                        label: 'Órdenes Completadas',
                        data: @json($mecanicosData),
                        backgroundColor: '#8b5cf6',
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });

            // 6. Estado de Órdenes (datos reales)
            const estadoColores = {
                'En Espera': '#9ca3af',
                'En Reparación': '#f59e0b',
                'Finalizado': '#3b82f6',
                'Entregado': '#10b981'
            };
            const labels6 = @json($estadoLabels);
            const colores6 = labels6.map(l => estadoColores[l] || '#728495');
            
            new Chart(document.getElementById('chartOrdenes'), {
                type: 'pie',
                data: {
                    labels: labels6,
                    datasets: [{
                        data: @json($estadoData),
                        backgroundColor: colores6,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12 } } }
                }
            });
        });
    </script>

@endsection
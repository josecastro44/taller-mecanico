<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Taller Mecánico</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="flex h-screen bg-gray-50 overflow-hidden font-sans">

    {{-- ========================================== --}}
    {{-- BARRA LATERAL (MENÚ INTACTO) --}}
    {{-- ========================================== --}}
    <aside class="w-72 bg-[#212E3B] flex flex-col h-full overflow-y-auto shadow-xl z-10">
        
        <div class="p-6 border-b border-[#314255] mb-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="ph ph-wrench text-blue-400 text-2xl"></i> Taller Mecánico
            </h2>
        </div>

        <nav class="flex-1 pb-6 px-2"> {{-- px-2 general para separar de los bordes --}}
            
            {{-- TU CÓDIGO ORIGINAL EXACTO --}}
            <div class="text-xs font-bold text-[#728495] uppercase tracking-wider mb-2 px-4 mt-2">Dirección</div>
            <a href="{{ route('gerente') ?? '#' }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('gerente') ? 'bg-[#4A5B6A] text-white' : 'text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white' }}">
                <i class="ph ph-chart-line-up text-xl"></i> <span class="font-medium">Dashboard Gerencial</span>
            </a>

            <div class="text-xs font-bold text-[#728495] uppercase tracking-wider mb-2 px-4 mt-4">Supervisión Operativa</div>
            
            <a href="/mecanico" class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors">
                <i class="ph ph-users-three text-xl"></i> <span class="font-medium">Ver Taller (Mecánicos)</span>
            </a>

            <div class="text-xs font-bold text-[#728495] uppercase tracking-wider mb-2 px-4 mt-4">Gestión General</div>
            <a href="/recepcion" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-car-profile text-xl"></i><span class="font-medium">Recepción y O.S.</span></a>
            <a href="/servicios" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-clipboard-text text-xl"></i><span class="font-medium">Servicios (Mano Obra)</span></a>
            <a href="/repuestos" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-nut text-xl"></i><span class="font-medium">Inventario / Almacén</span></a>
            <a href="/ventas" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-shopping-cart text-xl"></i><span class="font-medium">Ventas</span></a>
            <a href="/compras" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-bag text-xl"></i><span class="font-medium">Compras</span></a>
            <a href="/proveedores" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-truck text-xl"></i><span class="font-medium">Proveedores</span></a>
            <a href="/empleados" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-users text-xl"></i><span class="font-medium">Empleados y Nómina</span></a>
            <a href="/finanzas" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-currency-dollar text-xl"></i><span class="font-medium">Facturación y Finanzas</span></a>

            {{-- Nueva sección añadida con tu mismo estilo --}}
            <div class="text-xs font-bold text-[#728495] uppercase tracking-wider mb-2 px-4 mt-4">Auditoría</div>
            <a href="/reportes" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors bg-[#4A5B6A] text-white">
                <i class="ph ph-file-text text-xl"></i> <span class="font-medium">Reportes</span>
            </a>
        </nav>
    </aside>

    {{-- ========================================== --}}
    {{-- CONTENIDO PRINCIPAL (DASHBOARD DE GRÁFICOS) --}}
    {{-- ========================================== --}}
    <main class="flex-1 h-full overflow-y-auto p-6 md:p-8 bg-gray-50">
        
        {{-- Encabezado --}}
        <div class="mb-8 flex justify-between items-end bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-[#4A5B6A] flex items-center gap-2">
                    <i class="ph ph-presentation-chart text-3xl"></i>
                    Dashboard de Estadísticas y Reportes
                </h1>
                <p class="text-sm text-gray-500 mt-1">Análisis de rendimiento, ventas y operatividad del taller.</p>
            </div>
            <button onclick="window.print()" class="bg-[#4A5B6A] text-white px-5 py-2.5 rounded-lg flex items-center gap-2 hover:bg-[#374450] transition-colors shadow-sm font-medium">
                <i class="ph ph-printer text-lg"></i> Imprimir
            </button>
        </div>

        {{-- Grid de Gráficos --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 pb-10">

            {{-- 1. Carros Atendidos --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="ph ph-car text-[#4A5B6A]"></i> Carros Atendidos (Mensual)
                </h2>
                <div class="relative h-64 w-full">
                    <canvas id="chartCarros"></canvas>
                </div>
            </div>

            {{-- 2. Ventas Repuestos --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="ph ph-nut text-orange-500"></i> Ventas de Repuestos ($)
                </h2>
                <div class="relative h-64 w-full">
                    <canvas id="chartRepuestos"></canvas>
                </div>
            </div>

            {{-- 3. Tipos Reparación --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="ph ph-wrench text-blue-500"></i> Tipos de Reparación
                </h2>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="chartReparaciones"></canvas>
                </div>
            </div>

            {{-- 4. Balance Financiero --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="ph ph-currency-dollar text-green-500"></i> Balance Financiero ($)
                </h2>
                <div class="relative h-64 w-full">
                    <canvas id="chartFinanzas"></canvas>
                </div>
            </div>

            {{-- 5. Rendimiento Mecánicos --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="ph ph-users-three text-purple-500"></i> Trabajos por Mecánico
                </h2>
                <div class="relative h-64 w-full">
                    <canvas id="chartMecanicos"></canvas>
                </div>
            </div>

            {{-- 6. Estado de Órdenes --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="ph ph-clipboard-text text-yellow-500"></i> Estado de Órdenes
                </h2>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="chartOrdenes"></canvas>
                </div>
            </div>

        </div>
    </main>

    {{-- Scripts de los 6 gráficos --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const colorPrimario = '#4A5B6A';

            new Chart(document.getElementById('chartCarros'), {
                type: 'bar',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                    datasets: [{ label: 'Vehículos', data: [45, 52, 38, 65, 48, 55], backgroundColor: colorPrimario, borderRadius: 4 }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            new Chart(document.getElementById('chartRepuestos'), {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                    datasets: [{ label: 'Ventas USD ($)', data: [1200, 1900, 1500, 2200, 1800, 2500], borderColor: '#f97316', backgroundColor: 'rgba(249, 115, 22, 0.1)', borderWidth: 2, fill: true, tension: 0.3 }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            new Chart(document.getElementById('chartReparaciones'), {
                type: 'doughnut',
                data: {
                    labels: ['Frenos', 'Aceite', 'Suspensión', 'Motor', 'Eléctrico'],
                    datasets: [{ data: [35, 45, 15, 10, 20], backgroundColor: ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6'] }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
            });

            new Chart(document.getElementById('chartFinanzas'), {
                type: 'bar',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr'],
                    datasets: [
                        { label: 'Ingresos', data: [5000, 6200, 5800, 7000], backgroundColor: '#10b981', borderRadius: 4 },
                        { label: 'Gastos', data: [3200, 3500, 3100, 4000], backgroundColor: '#ef4444', borderRadius: 4 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            new Chart(document.getElementById('chartMecanicos'), {
                type: 'bar',
                data: {
                    labels: ['Carlos', 'Miguel', 'José', 'Luis'],
                    datasets: [{ label: 'Órdenes Completadas', data: [28, 35, 22, 40], backgroundColor: '#8b5cf6', borderRadius: 4 }]
                },
                options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false }
            });

            new Chart(document.getElementById('chartOrdenes'), {
                type: 'pie',
                data: {
                    labels: ['En Espera', 'En Proceso', 'Listos'],
                    datasets: [{ data: [8, 15, 5], backgroundColor: ['#f87171', '#fbbf24', '#34d399'] }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });
        });
    </script>
</body>
</html>
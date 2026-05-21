<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AutoSys - Sistema de Gestión de Taller Mecánico. Control de vehículos, servicios, inventario y facturación.">
    <title>Taller Mecánico - AutoSys</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { font-family: 'Inter', sans-serif; }

        /* Scrollbar personalizado */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #4A5B6A; border-radius: 10px; }

        /* Micro-animaciones */
        .card-hover { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(38, 58, 71, 0.12); }

        .btn-scale { transition: all 0.15s ease; }
        .btn-scale:active { transform: scale(0.97); }

        /* Animación de pulso para badges activos */
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .pulse-soft { animation: pulse-soft 2s ease-in-out infinite; }

        /* Skeleton loading */
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 4px;
        }

        /* Sidebar mobile transition */
        .sidebar-overlay {
            background: rgba(38, 58, 71, 0.5);
            backdrop-filter: blur(4px);
            transition: opacity 0.3s ease;
        }

        /* Toast notifications */
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .toast-enter { animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
        .toast-exit { animation: slideOutRight 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards; }

        /* Gradiente sutil en el header */
        .header-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        /* Animación de entrada para contenido */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: none; }
        }
        .animate-fade-in { animation: fadeInUp 0.4s ease-out forwards; }

        /* Badge con punto animado */
        .status-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        .status-dot.active {
            animation: pulse-soft 1.5s infinite;
        }
    </style>
</head>
<body class="bg-[#F1F4F8] font-sans text-[#263A47] flex h-screen overflow-hidden">

    {{-- OVERLAY para cerrar sidebar en móvil --}}
    <div id="sidebar-overlay" class="sidebar-overlay fixed inset-0 z-30 hidden md:hidden" onclick="toggleSidebar()"></div>

    {{-- ========================================== --}}
    {{-- SIDEBAR RESPONSIVO --}}
    {{-- ========================================== --}}
    <aside id="sidebar" class="w-64 bg-[#263A47] text-white flex flex-col shadow-xl z-40
        fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0
        transition-transform duration-300 ease-in-out">

        <div class="p-5 flex items-center justify-between border-b border-[#4A5B6A]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#4A5B6A] to-[#728495] rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ph ph-wrench text-xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold tracking-wide">AutoSys</h1>
                    <p class="text-[10px] text-[#98A9BE] uppercase tracking-widest font-medium">Taller Mecánico</p>
                </div>
            </div>
            {{-- Botón cerrar sidebar (solo móvil) --}}
            <button onclick="toggleSidebar()" class="md:hidden text-[#98A9BE] hover:text-white transition p-1">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>

        <nav class="flex-1 p-3 space-y-1 overflow-y-auto custom-scrollbar">
            
            @if(Auth::check())

                {{-- ========================================== --}}
                {{-- 1. MENÚ PARA MECÁNICOS --}}
                {{-- ========================================== --}}
                @if(Auth::user()->rol === 'mecanico')
                    <div class="text-[10px] font-bold text-[#728495] uppercase tracking-wider mb-2 px-3 mt-3">Menú Operativo</div>
                    <a href="/mecanico" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('mecanico') && !request()->is('mecanico/*') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}">
                        <i class="ph ph-wrench text-lg"></i> <span class="font-medium text-sm">Mi Área de Trabajo</span>
                    </a>
                    <a href="/mecanico/historial" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('mecanico/historial') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}">
                        <i class="ph ph-clipboard-text text-lg"></i> <span class="font-medium text-sm">Historial de Trabajos</span>
                    </a>
                    <a href="/mecanico/insumos" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('mecanico/insumos') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}">
                        <i class="ph ph-package text-lg"></i> <span class="font-medium text-sm">Repuestos Solicitados</span>
                    </a>

                {{-- ========================================== --}}
                {{-- 2. MENÚ PARA EL GERENTE (Tiene TODO) --}}
                {{-- ========================================== --}}
                @elseif(Auth::user()->rol === 'gerente')
                    <div class="text-[10px] font-bold text-[#728495] uppercase tracking-wider mb-2 px-3 mt-3">Dirección</div>
                    <a href="{{ route('gerente') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('gerente') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}">
                        <i class="ph ph-chart-line-up text-lg"></i> <span class="font-medium text-sm">Dashboard Gerencial</span>
                    </a>

                    <div class="text-[10px] font-bold text-[#728495] uppercase tracking-wider mb-2 px-3 mt-4">Supervisión Operativa</div>
                    
                    <a href="/inicio" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('inicio') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}">
                        <i class="ph ph-gauge text-lg"></i> <span class="font-medium text-sm">Dashboard Taller</span>
                    </a>

                    <div class="text-[10px] font-bold text-[#728495] uppercase tracking-wider mb-2 px-3 mt-4">Gestión General</div>
                    <a href="/recepcion" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('recepcion') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-car-profile text-lg"></i><span class="font-medium text-sm">Recepción y O.S.</span></a>
                    <a href="/monitor" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('monitor') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-monitor-play text-lg"></i><span class="font-medium text-sm">Monitor de Taller</span></a>
                    <a href="/servicios" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('servicios') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-engine text-lg"></i><span class="font-medium text-sm">Servicios (Mano Obra)</span></a>
                    <a href="/repuestos" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('repuestos') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-nut text-lg"></i><span class="font-medium text-sm">Inventario / Almacén</span></a>
                    <a href="{{ route('ventas') }}" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('ventas') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-shopping-cart text-lg"></i><span class="font-medium text-sm">Ventas</span></a>
                    <a href="{{ route('compras') }}" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('compras') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-bag text-lg"></i><span class="font-medium text-sm">Compras</span></a>
                    <a href="{{ route('proveedores') }}" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('proveedores') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-truck text-lg"></i><span class="font-medium text-sm">Proveedores</span></a>
                    <a href="/empleados" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('empleados') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-users text-lg"></i><span class="font-medium text-sm">Empleados y Nómina</span></a>
                    <a href="/finanzas" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('finanzas') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-currency-dollar text-lg"></i><span class="font-medium text-sm">Facturación y Finanzas</span></a>

                    {{-- Sección de Reportes --}}
                    <div class="text-[10px] font-bold text-[#728495] uppercase tracking-wider mb-2 px-3 mt-4">Auditoría</div>
                    <a href="{{ route('reportes') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('reportes') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}">
                        <i class="ph ph-chart-bar text-lg"></i> <span class="font-medium text-sm">Reportes y Análisis</span>
                    </a>
                {{-- ========================================== --}}
                {{-- 3. MENÚ PARA ADMINISTRADOR (Limitado) --}}
                {{-- ========================================== --}}
                @elseif(Auth::user()->rol === 'administrador')
                    <div class="text-[10px] font-bold text-[#728495] uppercase tracking-wider mb-2 px-3 mt-3">Administración</div>
                    <a href="{{ route('dashboard') }}" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white"><i class="ph ph-squares-four text-lg"></i><span class="font-medium text-sm">Dashboard</span></a>
                    <a href="/recepcion" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('recepcion') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-car-profile text-lg"></i><span class="font-medium text-sm">Recepción y O.S.</span></a>
                    <a href="/servicios" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('servicios') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-engine text-lg"></i><span class="font-medium text-sm">Servicios</span></a>
                    <a href="/repuestos" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('repuestos') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-nut text-lg"></i><span class="font-medium text-sm">Repuestos e Insumos</span></a>
                    <a href="{{ route('ventas') }}" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('ventas') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-shopping-cart text-lg"></i><span class="font-medium text-sm">Ventas</span></a>
                    <a href="{{ route('compras') }}" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('compras') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-bag text-lg"></i><span class="font-medium text-sm">Compras</span></a>
                    <a href="{{ route('proveedores') }}" class="menu-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('proveedores') ? 'bg-[#4A5B6A] text-white shadow-md' : 'text-[#B4C5D8] hover:bg-[#4A5B6A]/60 hover:text-white' }}"><i class="ph ph-truck text-lg"></i><span class="font-medium text-sm">Proveedores</span></a>
                @endif
                
            @endif
        </nav>

        {{-- Perfil de usuario en el pie del sidebar --}}
        <div class="p-4 border-t border-[#4A5B6A]">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#728495] to-[#98A9BE] flex items-center justify-center shadow-inner">
                        <i class="ph ph-user text-white text-sm"></i>
                    </div>
                    <div>
                        @if(Auth::check())
                            <p class="text-sm font-semibold text-white leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-[#98A9BE] capitalize font-medium">{{ Auth::user()->rol }}</p>
                        @endif
                    </div>
                </div>
                <a href="/salir" title="Cerrar Sesión" class="text-[#98A9BE] hover:text-red-400 transition p-1.5 rounded-lg hover:bg-[#4A5B6A]/50">
                   <i class="ph ph-sign-out text-xl"></i>
                </a>
            </div>
        </div>
    </aside>

    {{-- ========================================== --}}
    {{-- CONTENIDO PRINCIPAL --}}
    {{-- ========================================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        
        {{-- HEADER MEJORADO --}}
        <header class="header-gradient h-16 shadow-sm flex items-center justify-between px-4 md:px-8 border-b border-[#B4C5D8]/60 z-20">
            
            <div class="flex items-center gap-4">
                {{-- Botón hamburguesa (solo móvil) --}}
                <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg hover:bg-[#B4C5D8]/30 transition text-[#4A5B6A]">
                    <i class="ph ph-list text-2xl"></i>
                </button>

                <h2 class="text-lg md:text-xl font-bold text-[#263A47]">
                    @if(Auth::check())
                        <span class="text-[#728495] font-medium text-sm md:text-base">{{ ucfirst(Auth::user()->rol) }} —</span>
                        {{ Auth::user()->name }}
                    @else
                        Panel de Sistema
                    @endif
                </h2>
            </div>
            
            <div class="flex items-center gap-2 md:gap-4">
                {{-- Reloj en tiempo real --}}
                <div class="hidden md:flex items-center gap-2 bg-[#F1F4F8] px-3 py-1.5 rounded-lg border border-[#B4C5D8]/50">
                    <i class="ph ph-clock text-[#728495]"></i>
                    <span id="reloj-header" class="text-sm font-semibold text-[#4A5B6A] tabular-nums">--:--:--</span>
                </div>

                {{-- Campana de notificaciones con Dropdown --}}
                <div class="relative">
                    <button id="btn-notificaciones" onclick="toggleNotificaciones()" class="relative p-2 rounded-lg hover:bg-[#B4C5D8]/30 transition text-[#728495] focus:outline-none">
                        <i class="ph ph-bell text-xl"></i>
                        @if(Auth::check() && in_array(Auth::user()->rol, ['gerente', 'administrador']))
                            <span id="badge-notificaciones" class="absolute top-1 right-1 flex h-2.5 w-2.5 hidden">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border border-white"></span>
                            </span>
                        @endif
                    </button>

                    {{-- Dropdown Panel --}}
                    <div id="panel-notificaciones" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-[#B4C5D8]/50 z-50 overflow-hidden transform origin-top-right transition-all">
                        <div class="p-3 bg-[#F8FAFB] border-b border-[#B4C5D8]/50 flex justify-between items-center">
                            <h3 class="font-bold text-[#263A47] text-sm">Notificaciones del Sistema</h3>
                            <button onclick="cargarNotificaciones()" class="text-[#728495] hover:text-[#4A5B6A]" title="Actualizar">
                                <i class="ph ph-arrows-clockwise"></i>
                            </button>
                        </div>
                        <div id="lista-notificaciones" class="max-h-72 overflow-y-auto custom-scrollbar bg-white divide-y divide-gray-100">
                            <div class="p-4 text-center text-gray-400 text-sm">
                                <i class="ph ph-spinner animate-spin text-2xl mb-1"></i>
                                <p>Cargando información...</p>
                            </div>
                        </div>
                        <div class="p-2 bg-[#F8FAFB] border-t border-[#B4C5D8]/50 text-center">
                            <a href="/inicio" class="text-xs font-bold text-blue-600 hover:underline">Ir al Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- ÁREA DE CONTENIDO --}}
        <div class="flex-1 overflow-y-auto p-4 md:p-8">
            {{-- Toast container --}}
            <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-3"></div>
            
            <div class="animate-fade-in">
                @yield('contenido')
            </div>
        </div>
    </main>

    {{-- ========================================== --}}
    {{-- SCRIPTS GLOBALES --}}
    {{-- ========================================== --}}
    <script>
        // Toggle sidebar en móvil
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Reloj en tiempo real
        function actualizarReloj() {
            const ahora = new Date();
            const h = String(ahora.getHours()).padStart(2, '0');
            const m = String(ahora.getMinutes()).padStart(2, '0');
            const s = String(ahora.getSeconds()).padStart(2, '0');
            const el = document.getElementById('reloj-header');
            if (el) el.textContent = `${h}:${m}:${s}`;
        }
        setInterval(actualizarReloj, 1000);
        actualizarReloj();

        // Sistema de Toast Notifications
        function mostrarToast(mensaje, tipo = 'exito') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const colores = {
                'exito': 'bg-green-500',
                'info': 'bg-blue-500',
                'error': 'bg-red-500',
                'alerta': 'bg-amber-500'
            };

            const iconos = {
                'exito': 'ph-check-circle',
                'info': 'ph-info',
                'error': 'ph-warning-circle',
                'alerta': 'ph-warning'
            };

            toast.className = `toast-enter ${colores[tipo]} text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3 min-w-[300px] max-w-[420px]`;
            toast.innerHTML = `
                <i class="ph ${iconos[tipo]} text-xl flex-shrink-0"></i>
                <span class="text-sm font-medium flex-1">${mensaje}</span>
                <button onclick="this.parentElement.remove()" class="text-white/70 hover:text-white ml-2 flex-shrink-0">
                    <i class="ph ph-x text-lg"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Auto-remover después de 5 segundos
            setTimeout(() => {
                toast.classList.remove('toast-enter');
                toast.classList.add('toast-exit');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Cerrar sidebar al hacer clic en un link (solo móvil)
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    toggleSidebar();
                }
            });
        });

        // === Notificaciones Globales AJAX ===
        function toggleNotificaciones() {
            const panel = document.getElementById('panel-notificaciones');
            panel.classList.toggle('hidden');
            if (!panel.classList.contains('hidden')) {
                cargarNotificaciones();
            }
        }

        // Cerrar al hacer clic afuera
        document.addEventListener('click', function(event) {
            const panel = document.getElementById('panel-notificaciones');
            const btn = document.getElementById('btn-notificaciones');
            if (panel && !panel.classList.contains('hidden') && !panel.contains(event.target) && !btn.contains(event.target)) {
                panel.classList.add('hidden');
            }
        });

        async function cargarNotificaciones() {
            const lista = document.getElementById('lista-notificaciones');
            const badge = document.getElementById('badge-notificaciones');
            if(!lista) return;

            lista.innerHTML = `<div class="p-4 text-center text-gray-400 text-sm"><i class="ph ph-spinner animate-spin text-2xl mb-1"></i><p>Cargando información...</p></div>`;

            try {
                const res = await fetch('/api/dashboard/notificaciones');
                if(!res.ok) throw new Error('Error en API');
                const alertas = await res.json();
                
                lista.innerHTML = '';
                
                if (alertas.length === 0) {
                    lista.innerHTML = `<div class="p-6 text-center text-gray-400"><i class="ph ph-check-circle text-3xl mb-2 text-green-400"></i><p class="text-sm">Todo en orden, no hay avisos.</p></div>`;
                    if(badge) badge.classList.add('hidden');
                    return;
                }

                if(badge) badge.classList.remove('hidden');

                alertas.forEach(alerta => {
                    let icono = '';
                    let color = '';
                    
                    switch(alerta.tipo) {
                        case 'inventario': icono = 'ph-warning-circle'; color = 'text-amber-500 bg-amber-50'; break;
                        case 'ordenes': icono = 'ph-clock'; color = 'text-blue-500 bg-blue-50'; break;
                        case 'facturas': icono = 'ph-currency-dollar'; color = 'text-red-500 bg-red-50'; break;
                        default: icono = 'ph-info'; color = 'text-gray-500 bg-gray-50';
                    }

                    lista.innerHTML += `
                        <div class="p-3 hover:bg-gray-50 transition flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full ${color} flex items-center justify-center shrink-0">
                                <i class="ph ${icono} text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-[#263A47] leading-tight">${alerta.titulo}</p>
                                <p class="text-xs text-[#728495] mt-0.5">${alerta.mensaje}</p>
                                <p class="text-[10px] text-gray-400 mt-1 font-medium">${alerta.tiempo}</p>
                            </div>
                        </div>
                    `;
                });

            } catch (error) {
                lista.innerHTML = `<div class="p-4 text-center text-red-400 text-sm"><p>No se pudieron cargar las notificaciones.</p></div>`;
            }
        }

        // Cargar badge al iniciar si es necesario
        @if(Auth::check() && in_array(Auth::user()->rol, ['gerente', 'administrador']))
            setTimeout(() => { cargarNotificaciones(); }, 2000);
            setInterval(() => { if(document.getElementById('panel-notificaciones').classList.contains('hidden')) cargarNotificaciones(); }, 60000);
        @endif

        // === FIX GLOBAL: Mover modales al body ===
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.animate-fade-in [class*="fixed"][class*="inset-0"]').forEach(function(modal) {
                document.body.appendChild(modal);
            });
        });
    </script>

</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taller Mecánico - AutoSys</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-[#B4C5D8]/20 font-sans text-[#263A47] flex h-screen overflow-hidden">

  <aside class="w-64 bg-[#263A47] text-white flex flex-col shadow-xl z-20">
        <div class="p-6 flex items-center justify-center border-b border-[#4A5B6A]">
            <i class="ph ph-wrench text-3xl text-[#98A9BE] mr-3"></i>
            <h1 class="text-xl font-bold tracking-wider">AutoSys</h1>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto custom-scrollbar">
            
            @if(Auth::check())

                {{-- ========================================== --}}
                {{-- 1. MENÚ PARA MECÁNICOS --}}
                {{-- ========================================== --}}
                @if(Auth::user()->rol === 'mecanico')
                    <div class="text-xs font-bold text-[#728495] uppercase tracking-wider mb-2 px-4 mt-2">Menú Operativo</div>
                    <a href="/mecanico" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->is('mecanico') ? 'bg-[#4A5B6A] text-white' : 'text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white' }}">
                        <i class="ph ph-wrench text-xl"></i> <span class="font-medium">Mi Área de Trabajo</span>
                    </a>
                    <a href="/mecanico/historial" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->is('mecanico/historial') ? 'bg-[#4A5B6A] text-white' : 'text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white' }}">
                        <i class="ph ph-clipboard-text text-xl"></i> <span class="font-medium">Historial de Trabajos</span>
                    </a>
                    <a href="/mecanico/insumos" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->is('mecanico/insumos') ? 'bg-[#4A5B6A] text-white' : 'text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white' }}">
                        <i class="ph ph-package text-xl"></i> <span class="font-medium">Repuestos Solicitados</span>
                    </a>

                {{-- ========================================== --}}
                {{-- 2. MENÚ PARA EL GERENTE (Tiene TODO) --}}
                {{-- ========================================== --}}
                @elseif(Auth::user()->rol === 'gerente')
                    <div class="text-xs font-bold text-[#728495] uppercase tracking-wider mb-2 px-4 mt-2">Dirección</div>
                    <a href="{{ route('gerente') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('gerente') ? 'bg-[#4A5B6A] text-white' : 'text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white' }}">
                        <i class="ph ph-chart-line-up text-xl"></i> <span class="font-medium">Dashboard Gerencial</span>
                    </a>

                    <div class="text-xs font-bold text-[#728495] uppercase tracking-wider mb-2 px-4 mt-4">Supervisión Operativa</div>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors">
                        <i class="ph ph-bell-ringing text-xl text-amber-400"></i> 
                        <span class="font-medium text-amber-400">Aprobar Repuestos</span>
                        <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full ml-auto animate-pulse">2</span>
                    </a>
                    <a href="/mecanico" class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors">
                        <i class="ph ph-users-three text-xl"></i> <span class="font-medium">Ver Taller (Mecánicos)</span>
                    </a>

                    <div class="text-xs font-bold text-[#728495] uppercase tracking-wider mb-2 px-4 mt-4">Gestión General</div>
                    <a href="/recepcion" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-car-profile text-xl"></i><span class="font-medium">Recepción y O.S.</span></a>
                    <a href="/servicios" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-clipboard-text text-xl"></i><span class="font-medium">Servicios (Mano Obra)</span></a>
                    <a href="/repuestos" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-nut text-xl"></i><span class="font-medium">Inventario / Almacén</span></a>
                    <a href="{{ route('ventas') }}" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-shopping-cart text-xl"></i><span class="font-medium">Ventas</span></a>
                    <a href="{{ route('compras') }}" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-bag text-xl"></i><span class="font-medium">Compras</span></a>
                    <a href="{{ route('proveedores') }}" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-truck text-xl"></i><span class="font-medium">Proveedores</span></a>
                    <a href="/empleados" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-users text-xl"></i><span class="font-medium">Empleados y Nómina</span></a>
                    <a href="/finanzas" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-currency-dollar text-xl"></i><span class="font-medium">Facturación y Finanzas</span></a>

                {{-- ========================================== --}}
                {{-- 3. MENÚ PARA ADMINISTRADOR (Limitado) --}}
                {{-- ========================================== --}}
                @elseif(Auth::user()->rol === 'administrador')
                    <div class="text-xs font-bold text-[#728495] uppercase tracking-wider mb-2 px-4 mt-2">Administración</div>
                    <a href="{{ route('dashboard') }}" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-squares-four text-xl"></i><span class="font-medium">Dashboard</span></a>
                    <a href="/recepcion" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-car-profile text-xl"></i><span class="font-medium">Recepción y O.S.</span></a>
                    <a href="/servicios" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-clipboard-text text-xl"></i><span class="font-medium">Servicios</span></a>
                    <a href="/repuestos" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-nut text-xl"></i><span class="font-medium">Repuestos e Insumos</span></a>
                    <a href="{{ route('ventas') }}" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-shopping-cart text-xl"></i><span class="font-medium">Ventas</span></a>
                    <a href="{{ route('compras') }}" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-bag text-xl"></i><span class="font-medium">Compras</span></a>
                    <a href="{{ route('proveedores') }}" class="menu-link flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors"><i class="ph ph-truck text-xl"></i><span class="font-medium">Proveedores</span></a>
                @endif
                
            @endif
        </nav>

        <div class="p-4 border-t border-[#4A5B6A]">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#728495] flex items-center justify-center">
                        <i class="ph ph-user text-white"></i>
                    </div>
                    <div>
                        @if(Auth::check())
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-[#98A9BE] capitalize">{{ Auth::user()->rol }}</p>
                        @endif
                    </div>
                </div>
                <a href="/salir" title="Cerrar Sesión" class="text-[#98A9BE] hover:text-red-400 transition">
                   <i class="ph ph-sign-out text-2xl"></i>
                </a>
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="bg-white h-16 shadow-sm flex items-center justify-between px-8 border-b border-[#B4C5D8]">
            <h2 class="text-2xl font-semibold text-[#263A47] capitalize">
                @if(Auth::check())
                    {{ Auth::user()->rol }} - {{ Auth::user()->name }}
                @else
                    Panel de Sistema
                @endif
            </h2>
            
            <div class="flex items-center gap-4">
                <button class="relative p-2 rounded-full hover:bg-[#B4C5D8]/30 transition text-[#728495]">
                    <i class="ph ph-bell text-2xl"></i>
                    @if(Auth::check() && Auth::user()->rol === 'gerente')
                        <span class="absolute top-1 right-1 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600 border border-white"></span>
                        </span>
                    @endif
                </button>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 relative">
            @yield('contenido')
        </div>
    </main>

</body>
</html>
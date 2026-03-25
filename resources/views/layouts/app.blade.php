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

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            
            {{-- MAGIA DE LARAVEL: Condicional según la URL --}}
            @if(request()->is('mecanico*'))
                
                <div class="text-xs font-bold text-[#728495] uppercase tracking-wider mb-2 px-4 mt-2">Menú Operativo</div>
                
                <a href="/mecanico" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-[#4A5B6A] text-white transition-colors">
                    <i class="ph ph-wrench text-xl"></i>
                    <span class="font-medium">Mi Área de Trabajo</span>
                </a>
<a href="/mecanico/historial" class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors">
    <i class="ph ph-clipboard-text text-xl"></i>
    <span class="font-medium">Historial de Trabajos</span>
</a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors">
                    <i class="ph ph-package text-xl"></i>
                    <span class="font-medium">Insumos Solicitados</span>
                </a>

            @else
                
                <div class="text-xs font-bold text-[#728495] uppercase tracking-wider mb-2 px-4 mt-2">Administración</div>

                <a href="/inicio" class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors">
                    <i class="ph ph-squares-four text-xl"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="/recepcion" class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors">
                    <i class="ph ph-car-profile text-xl"></i>
                    <span class="font-medium">Recepción y O.S.</span>
                </a>
                <a href="/servicios" class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors">
                    <i class="ph ph-clipboard-text text-xl"></i>
                    <span class="font-medium">Servicios (Mano Obra)</span>
                </a>
                <a href="/repuestos" class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors">
                    <i class="ph ph-nut text-xl"></i>
                    <span class="font-medium">Repuestos e Insumos</span>
                </a>
                <a href="/empleados" class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors">
                    <i class="ph ph-users text-xl"></i>
                    <span class="font-medium">Empleados y Nómina</span>
                </a>
                <a href="/finanzas" class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors">
                    <i class="ph ph-currency-dollar text-xl"></i>
                    <span class="font-medium">Facturación y Finanzas</span>
                </a>
            @endif

        </nav>

        <div class="p-4 border-t border-[#4A5B6A]">
            
            @if(request()->is('mecanico*'))
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                            <i class="ph ph-user text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">Chema</p>
                            <p class="text-xs text-[#98A9BE]">Mecánico</p>
                        </div>
                    </div>
                    <a href="/" title="Cerrar Sesión" class="text-[#98A9BE] hover:text-red-400 transition">
                        <i class="ph ph-sign-out text-2xl"></i>
                    </a>
                </div>
            @else
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#728495] flex items-center justify-center">
                            <i class="ph ph-user text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">Admin</p>
                            <p class="text-xs text-[#98A9BE]">Taller Principal</p>
                        </div>
                    </div>
                    <a href="/" title="Cerrar Sesión" class="text-[#98A9BE] hover:text-red-400 transition">
                        <i class="ph ph-sign-out text-2xl"></i>
                    </a>
                </div>
            @endif

        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="bg-white h-16 shadow-sm flex items-center justify-between px-8 border-b border-[#B4C5D8]">
            <h2 class="text-2xl font-semibold text-[#263A47]">Panel de Sistema</h2>
            <button class="p-2 rounded-full hover:bg-[#B4C5D8]/30 transition text-[#728495]">
                <i class="ph ph-bell text-xl"></i>
            </button>
        </header>

        <div class="flex-1 overflow-y-auto p-8 relative">
            
            
        {{-- La directiva @yield('contenido') es el "hueco" donde entran las vistas hijas --}}
            @yield('contenido')

        </div>
    </main>

</body>
</html>
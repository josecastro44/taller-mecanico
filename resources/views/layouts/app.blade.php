<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taller Mecánico - AutoSys</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#B4C5D8]/20 font-sans text-[#263A47] flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#263A47] text-white flex flex-col shadow-xl">
        <div class="p-6 flex items-center justify-center border-b border-[#4A5B6A]">
            <i class="ph ph-wrench text-3xl text-[#98A9BE] mr-3"></i>
            <h1 class="text-xl font-bold tracking-wider">AutoSys</h1>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
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

            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-lg text-[#B4C5D8] hover:bg-[#4A5B6A] hover:text-white transition-colors focus:outline-none">
                    <div class="flex items-center gap-3">
                        <i class="ph ph-package text-xl"></i>
                        <span class="font-medium">Repuestos e insumos</span>
                    </div>
                    <i class="ph ph-caret-down text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open"
                     x-transition
                     class="mt-1 ml-9 flex flex-col border-l border-[#4A5B6A] space-y-1">
                    <a href="/repuestos" class="py-2 px-4 text-sm text-[#B4C5D8] hover:text-white transition-colors">
                        INVENTARIO MAESTRO
                    </a>
                    <a href="/repuestos/compras" class="py-2 px-4 text-sm text-[#B4C5D8] hover:text-white transition-colors">
                        CONSULTA DE PRODUCTO
                    </a>
                    <a href="/repuestos/ajustes" class="py-2 px-4 text-sm text-[#B4C5D8] hover:text-white transition-colors">
                        EN CONSTRUCCION
                    </a>
                </div>
            </div>
        </nav>

        <div class="p-4 border-t border-[#4A5B6A]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#728495] flex items-center justify-center">
                    <i class="ph ph-user text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-medium">Admin</p>
                    <p class="text-xs text-[#98A9BE]">Taller Principal</p>
                </div>
            </div>
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
            @yield('contenido')
        </div>
    </main>

</body>
</html>

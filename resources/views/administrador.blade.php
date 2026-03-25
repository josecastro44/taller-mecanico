@extends('layouts.app')

@section('contenido')
<div class="w-full">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-[#263A47]">Panel de Administración</h1>
        <p class="text-gray-500 mt-2">Bienvenido. Desde aquí puedes gestionar las operaciones principales del taller.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <a href="/recepcion" class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm hover:shadow-md hover:border-[#4A5B6A] transition-all group flex items-center gap-5">
            <div class="bg-[#B4C5D8]/30 p-4 rounded-lg group-hover:bg-[#4A5B6A] group-hover:text-white transition-colors text-[#263A47]">
                <i class="ph ph-car-profile text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-[#263A47]">Recepción y O.S.</h3>
                <p class="text-sm text-gray-500">Gestión de órdenes</p>
            </div>
        </a>

        <a href="/servicios" class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm hover:shadow-md hover:border-[#4A5B6A] transition-all group flex items-center gap-5">
            <div class="bg-[#B4C5D8]/30 p-4 rounded-lg group-hover:bg-[#4A5B6A] group-hover:text-white transition-colors text-[#263A47]">
                <i class="ph ph-clipboard-text text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-[#263A47]">Servicios</h3>
                <p class="text-sm text-gray-500">Catálogo de mano de obra</p>
            </div>
        </a>

        <a href="/repuestos" class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm hover:shadow-md hover:border-[#4A5B6A] transition-all group flex items-center gap-5">
            <div class="bg-[#B4C5D8]/30 p-4 rounded-lg group-hover:bg-[#4A5B6A] group-hover:text-white transition-colors text-[#263A47]">
                <i class="ph ph-nut text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-[#263A47]">Repuestos</h3>
                <p class="text-sm text-gray-500">Inventario del taller</p>
            </div>
        </a>

        <a href="{{ route('ventas') }}" class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm hover:shadow-md hover:border-[#4A5B6A] transition-all group flex items-center gap-5">
            <div class="bg-[#B4C5D8]/30 p-4 rounded-lg group-hover:bg-[#4A5B6A] group-hover:text-white transition-colors text-[#263A47]">
                <i class="ph ph-shopping-cart text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-[#263A47]">Ventas</h3>
                <p class="text-sm text-gray-500">Facturación y cobros</p>
            </div>
        </a>

        <a href="{{ route('compras') }}" class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm hover:shadow-md hover:border-[#4A5B6A] transition-all group flex items-center gap-5">
            <div class="bg-[#B4C5D8]/30 p-4 rounded-lg group-hover:bg-[#4A5B6A] group-hover:text-white transition-colors text-[#263A47]">
                <i class="ph ph-bag text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-[#263A47]">Compras</h3>
                <p class="text-sm text-gray-500">Registro de gastos</p>
            </div>
        </a>

        <a href="{{ route('proveedores') }}" class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm hover:shadow-md hover:border-[#4A5B6A] transition-all group flex items-center gap-5">
            <div class="bg-[#B4C5D8]/30 p-4 rounded-lg group-hover:bg-[#4A5B6A] group-hover:text-white transition-colors text-[#263A47]">
                <i class="ph ph-truck text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-[#263A47]">Proveedores</h3>
                <p class="text-sm text-gray-500">Directorio de empresas</p>
            </div>
        </a>

    </div>
</div>
@endsection
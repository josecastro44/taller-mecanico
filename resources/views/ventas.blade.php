@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Gestión de Ventas (Mostrador)</h2>
            <p class="text-[#728495]">Registro de ventas directas de repuestos e insumos al detal</p>
        </div>
        <button class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
            <i class="ph ph-shopping-cart text-xl"></i>
            Nueva Venta
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-l-4 border-l-green-500 flex items-center justify-between">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Ventas del Día</p>
                <p class="text-3xl font-bold text-[#263A47]">$ 320.00</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="ph ph-currency-dollar text-2xl text-green-500"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-l-4 border-l-[#4A5B6A] flex items-center justify-between">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Artículos Vendidos</p>
                <p class="text-3xl font-bold text-[#263A47]">18</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-[#B4C5D8]/30 flex items-center justify-center">
                <i class="ph ph-package text-2xl text-[#4A5B6A]"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-l-4 border-l-[#728495] flex items-center justify-between">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Ticket Promedio</p>
                <p class="text-3xl font-bold text-[#263A47]">$ 17.77</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-[#B4C5D8]/30 flex items-center justify-center">
                <i class="ph ph-receipt text-2xl text-[#4A5B6A]"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-[#B4C5D8] bg-[#B4C5D8]/10 flex justify-between items-center">
            <h3 class="text-lg font-bold text-[#263A47]">Últimas Ventas Realizadas</h3>
            <div class="relative w-64">
                <i class="ph ph-magnifying-glass absolute left-3 top-2 text-[#728495]"></i>
                <input type="text" placeholder="Buscar ticket..." class="w-full pl-9 pr-4 py-1.5 border border-[#B4C5D8] rounded-md text-sm focus:outline-none focus:border-[#4A5B6A]">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">N° Ticket</th>
                        <th class="px-6 py-4 font-bold">Cliente</th>
                        <th class="px-6 py-4 font-bold text-center">Artículos</th>
                        <th class="px-6 py-4 font-bold text-right">Total</th>
                        <th class="px-6 py-4 font-bold text-center">Método</th>
                        <th class="px-6 py-4 font-bold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-bold">VT-1045</td>
                        <td class="px-6 py-4">Cliente Mostrador</td>
                        <td class="px-6 py-4 text-center">2</td>
                        <td class="px-6 py-4 text-right font-bold text-green-700">$ 45.00</td>
                        <td class="px-6 py-4 text-center"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">Punto / Tarjeta</span></td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Ver Detalle"><i class="ph ph-eye text-xl"></i></button>
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Imprimir"><i class="ph ph-printer text-xl"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
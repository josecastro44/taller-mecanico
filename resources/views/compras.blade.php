@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Órdenes de Compra (Reabastecimiento)</h2>
            <p class="text-[#728495]">Ingreso de mercancía e insumos al inventario del taller</p>
        </div>
        <button class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
            <i class="ph ph-bag text-xl"></i>
            Nueva Compra
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-[#263A47]">
            <p class="text-sm text-[#728495] font-medium mb-1">Inversión del Mes</p>
            <p class="text-3xl font-bold text-[#263A47]">$ 1,850.00</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-yellow-500">
            <p class="text-sm text-[#728495] font-medium mb-1">Pedidos en Tránsito</p>
            <p class="text-3xl font-bold text-yellow-600">2</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-red-500">
            <p class="text-sm text-[#728495] font-medium mb-1">Cuentas por Pagar</p>
            <p class="text-3xl font-bold text-red-600">$ 450.00</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#B4C5D8]/20 border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">N° Orden</th>
                        <th class="px-6 py-4 font-bold">Proveedor</th>
                        <th class="px-6 py-4 font-bold">Fecha</th>
                        <th class="px-6 py-4 font-bold text-right">Monto Total</th>
                        <th class="px-6 py-4 font-bold text-center">Estado</th>
                        <th class="px-6 py-4 font-bold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-bold">OC-2026-089</td>
                        <td class="px-6 py-4">Autopartes Venezuela C.A.</td>
                        <td class="px-6 py-4 text-[#728495]">23 Mar 2026</td>
                        <td class="px-6 py-4 text-right font-bold text-red-600">$ 320.50</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Recibido</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Ver Detalles"><i class="ph ph-eye text-xl"></i></button>
                        </td>
                    </tr>
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-bold">OC-2026-090</td>
                        <td class="px-6 py-4">Lubricantes de Occidente</td>
                        <td class="px-6 py-4 text-[#728495]">24 Mar 2026</td>
                        <td class="px-6 py-4 text-right font-bold text-red-600">$ 150.00</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">En Tránsito</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Ver Detalles"><i class="ph ph-eye text-xl"></i></button>
                            <button class="text-green-600 hover:text-green-800 mx-1 transition-colors" title="Marcar Recibido"><i class="ph ph-check-circle text-xl"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
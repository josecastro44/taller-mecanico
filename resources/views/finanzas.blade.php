@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Facturación y Reportes Financieros</h2>
            <p class="text-[#728495]">Control de ingresos, egresos operativos, impuestos (IVA/IGTF) y utilidades</p>
        </div>
        <button class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
            <i class="ph ph-receipt text-xl"></i>
            Generar Factura
        </button>
    </div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-green-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Ingresos Brutos (Mes)</p>
                <p class="text-3xl font-bold text-[#263A47]">$ 12,450.00</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="ph ph-trend-up text-2xl text-green-500"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-red-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Egresos (Nómina + Repuestos)</p>
                <p class="text-3xl font-bold text-[#263A47]">$ 7,820.00</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center">
                <i class="ph ph-trend-down text-2xl text-red-500"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-blue-600">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Utilidad Neta (Ganancia Real)</p>
                <p class="text-3xl font-bold text-[#263A47]">$ 4,630.00</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="ph ph-coins text-2xl text-blue-600"></i>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm flex flex-col">
            <div class="p-5 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10 rounded-t-xl">
                <h3 class="text-lg font-bold text-[#263A47]">Historial de Facturación</h3>
                <button class="text-sm font-semibold text-[#4A5B6A] hover:underline">Ver todas</button>
            </div>
            <div class="overflow-x-auto flex-1 p-5">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-sm uppercase tracking-wider">
                            <th class="pb-3 font-bold">N° Factura / O.S.</th>
                            <th class="pb-3 font-bold text-right">Repuestos</th>
                            <th class="pb-3 font-bold text-right">Mano Obra</th>
                            <th class="pb-3 font-bold text-right">IVA / IGTF</th>
                            <th class="pb-3 font-bold text-right">Total Facturado</th>
                            <th class="pb-3 font-bold text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-[#263A47]">
                        <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                            <td class="py-4">
                                <p class="font-bold">FAC-00102</p>
                                <p class="text-xs text-[#728495]">Ref: Orden #0054</p>
                            </td>
                            <td class="py-4 text-right font-medium">$ 120.00</td>
                            <td class="py-4 text-right font-medium">$ 80.00</td>
                            <td class="py-4 text-right text-red-500 font-medium">$ 35.80</td>
                            <td class="py-4 text-right font-bold text-lg text-[#263A47]">$ 235.80</td>
                            <td class="py-4 text-center">
                                <button class="text-[#4A5B6A] hover:text-[#263A47] bg-[#B4C5D8]/30 p-2 rounded-lg transition" title="Imprimir"><i class="ph ph-printer text-xl"></i></button>
                            </td>
                        </tr>
                        <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                            <td class="py-4">
                                <p class="font-bold">FAC-00103</p>
                                <p class="text-xs text-[#728495]">Ref: Orden #0055</p>
                            </td>
                            <td class="py-4 text-right font-medium">$ 45.00</td>
                            <td class="py-4 text-right font-medium">$ 30.00</td>
                            <td class="py-4 text-right text-red-500 font-medium">$ 12.00</td>
                            <td class="py-4 text-right font-bold text-lg text-[#263A47]">$ 87.00</td>
                            <td class="py-4 text-center">
                                <button class="text-[#4A5B6A] hover:text-[#263A47] bg-[#B4C5D8]/30 p-2 rounded-lg transition" title="Imprimir"><i class="ph ph-printer text-xl"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
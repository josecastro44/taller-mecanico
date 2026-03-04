@extends('layouts.app')

@section('contenido')
    
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-[#263A47]">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Vehículos en Taller</p>
                <p class="text-3xl font-bold text-[#263A47]">12</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-[#B4C5D8]/30 flex items-center justify-center">
                <i class="ph ph-car text-2xl text-[#263A47]"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-green-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Facturación del Día</p>
                <p class="text-3xl font-bold text-[#263A47]">$450.00</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="ph ph-currency-dollar text-2xl text-green-600"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-amber-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Órdenes Pendientes</p>
                <p class="text-3xl font-bold text-[#263A47]">5</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center">
                <i class="ph ph-clipboard-text text-2xl text-amber-600"></i>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm p-6">
        
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-[#263A47]">Flujo de Estados en Tiempo Real</h3>
            {{-- Fíjate que el botón de Nueva Orden ya te manda a la ruta que creamos --}}
            <a href="/recepcion" class="text-sm bg-[#4A5B6A] text-white px-4 py-2 rounded hover:bg-[#263A47] transition">
                + Nueva Orden
            </a>
        </div>
        
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-[#B4C5D8] text-[#728495] text-sm">
                    <th class="pb-3 font-semibold">Placa</th>
                    <th class="pb-3 font-semibold">Vehículo</th>
                    <th class="pb-3 font-semibold">Estatus</th>
                </tr>
            </thead>
            <tbody class="text-sm text-[#263A47]">
                
                <tr class="border-b border-[#B4C5D8]/50 hover:bg-[#B4C5D8]/10 transition">
                    <td class="py-4 font-medium">XYZ-123</td>
                    <td class="py-4">Toyota Corolla 2018</td>
                    <td class="py-4">
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">
                            En Reparación
                        </span>
                    </td>
                </tr>
                
                <tr class="border-b border-[#B4C5D8]/50 hover:bg-[#B4C5D8]/10 transition">
                    <td class="py-4 font-medium">ABC-987</td>
                    <td class="py-4">Ford Fiesta 2015</td>
                    <td class="py-4">
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                            Listo para Entrega
                        </span>
                    </td>
                </tr>
                
                <tr class="hover:bg-[#B4C5D8]/10 transition">
                    <td class="py-4 font-medium">LMN-456</td>
                    <td class="py-4">Chevrolet Spark 2012</td>
                    <td class="py-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                            En Diagnóstico
                        </span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

@endsection
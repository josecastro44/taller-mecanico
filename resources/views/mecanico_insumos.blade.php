@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Repuestos Solicitados</h2>
            <p class="text-[#728495]">Haz seguimiento a las piezas y materiales que pediste a almacén.</p>
        </div>
        <button class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all font-medium flex items-center gap-2">
            <i class="ph ph-plus-circle text-xl"></i> Nueva Solicitud
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center gap-4 border-l-4 border-l-amber-500">
            <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                <i class="ph ph-clock text-2xl animate-pulse"></i>
            </div>
            <div>
                <p class="text-sm text-[#728495] font-medium">En Espera</p>
                <p class="text-2xl font-bold text-[#263A47]">2</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center gap-4 border-l-4 border-l-green-500">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                <i class="ph ph-check-circle text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-[#728495] font-medium">Entregados (Hoy)</p>
                <p class="text-2xl font-bold text-[#263A47]">5</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center gap-4 border-l-4 border-l-red-500">
            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                <i class="ph ph-x-circle text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-[#728495] font-medium">Rechazados / Sin Stock</p>
                <p class="text-2xl font-bold text-[#263A47]">0</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-[#B4C5D8] bg-[#B4C5D8]/10 flex justify-between items-center">
            <h3 class="text-lg font-bold text-[#263A47]">Historial de Solicitudes</h3>
            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-3 top-2 text-[#728495]"></i>
                <input type="text" placeholder="Buscar repuesto..." class="pl-9 pr-3 py-1.5 border border-[#B4C5D8] rounded-lg focus:outline-none focus:border-[#4A5B6A] text-sm">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Fecha / Hora</th>
                        <th class="px-6 py-4 font-bold">Repuesto Solicitado</th>
                        <th class="px-6 py-4 font-bold">O.S. Asignada</th>
                        <th class="px-6 py-4 font-bold text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-medium text-[#728495]">Hoy, 10:15 AM</td>
                        <td class="px-6 py-4">
                            <p class="font-bold">Aceite Semisintético 15W-40</p>
                            <p class="text-xs text-[#98A9BE] mt-0.5">Cantidad: 4 Litros</p>
                        </td>
                        <td class="px-6 py-4 font-bold text-[#4A5B6A]">#0055</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold border border-amber-200">
                                <i class="ph ph-clock mr-1"></i> Pendiente
                            </span>
                        </td>
                    </tr>

                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-medium text-[#728495]">Hoy, 10:15 AM</td>
                        <td class="px-6 py-4">
                            <p class="font-bold">Filtro de Aceite (FC-115)</p>
                            <p class="text-xs text-[#98A9BE] mt-0.5">Cantidad: 1 Unidad</p>
                        </td>
                        <td class="px-6 py-4 font-bold text-[#4A5B6A]">#0055</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold border border-amber-200">
                                <i class="ph ph-clock mr-1"></i> Pendiente
                            </span>
                        </td>
                    </tr>

                    <tr class="hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-medium text-[#728495]">Hoy, 08:30 AM</td>
                        <td class="px-6 py-4">
                            <p class="font-bold">Empacadura Tapa Válvula</p>
                            <p class="text-xs text-[#98A9BE] mt-0.5">Cantidad: 1 Unidad</p>
                        </td>
                        <td class="px-6 py-4 font-bold text-[#4A5B6A]">#0054</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">
                                <i class="ph ph-check mr-1"></i> Entregado
                            </span>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

@endsection
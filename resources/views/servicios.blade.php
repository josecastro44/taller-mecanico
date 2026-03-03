@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Catálogo de Servicios (Mano de Obra)</h2>
            <p class="text-[#728495]">Gestión de precios tabulados y variaciones por categoría de vehículo</p>
        </div>
        <button class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
            <i class="ph ph-plus-circle text-xl"></i>
            Nuevo Servicio
        </button>
    </div>

    <div class="bg-white p-4 rounded-xl border border-[#98A9BE]/50 shadow-sm mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-[#728495] text-xl"></i>
            <input type="text" placeholder="Buscar servicio por nombre o código..." class="w-full pl-10 pr-4 py-2 border border-[#B4C5D8] rounded-lg focus:outline-none focus:border-[#4A5B6A] text-[#263A47]">
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <select class="flex-1 sm:flex-none border border-[#B4C5D8] rounded-lg px-4 py-2 text-[#263A47] focus:outline-none focus:border-[#4A5B6A] bg-white cursor-pointer">
                <option value="">Todas las categorías</option>
                <option value="sencillo">Solo Sencillos</option>
                <option value="alta">Solo Alta Gama</option>
                <option value="pesada">Solo Carga Pesada</option>
            </select>
            <button class="border-2 border-[#B4C5D8] text-[#4A5B6A] px-4 py-2 rounded-lg hover:border-[#4A5B6A] hover:bg-[#B4C5D8]/10 transition flex items-center justify-center gap-2 font-semibold">
                <i class="ph ph-wrench text-xl"></i>
                Servicio Especial (Manual)
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#B4C5D8]/20 border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Código</th>
                        <th class="px-6 py-4 font-bold">Descripción del Servicio</th>
                        <th class="px-6 py-4 font-bold text-center border-l border-[#B4C5D8]/50">Sencillo</th>
                        <th class="px-6 py-4 font-bold text-center border-l border-[#B4C5D8]/50">Alta Gama</th>
                        <th class="px-6 py-4 font-bold text-center border-l border-[#B4C5D8]/50">Carga Pesada</th>
                        <th class="px-6 py-4 font-bold text-center border-l border-[#B4C5D8]/50">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-medium text-[#728495]">SRV-001</td>
                        <td class="px-6 py-4 font-bold">Cambio de Aceite y Filtros</td>
                        <td class="px-6 py-4 text-center font-semibold border-l border-[#B4C5D8]/30">$ 20.00</td>
                        <td class="px-6 py-4 text-center font-semibold border-l border-[#B4C5D8]/30">$ 35.00</td>
                        <td class="px-6 py-4 text-center font-semibold border-l border-[#B4C5D8]/30">$ 50.00</td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Editar"><i class="ph ph-pencil-simple text-xl"></i></button>
                            <button class="text-red-400 hover:text-red-600 mx-1 transition-colors" title="Eliminar"><i class="ph ph-trash text-xl"></i></button>
                        </td>
                    </tr>
                    
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-medium text-[#728495]">SRV-002</td>
                        <td class="px-6 py-4 font-bold">Revisión y Cambio de Frenos</td>
                        <td class="px-6 py-4 text-center font-semibold border-l border-[#B4C5D8]/30">$ 50.00</td>
                        <td class="px-6 py-4 text-center font-semibold border-l border-[#B4C5D8]/30">$ 80.00</td>
                        <td class="px-6 py-4 text-center font-semibold border-l border-[#B4C5D8]/30">$ 120.00</td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Editar"><i class="ph ph-pencil-simple text-xl"></i></button>
                            <button class="text-red-400 hover:text-red-600 mx-1 transition-colors" title="Eliminar"><i class="ph ph-trash text-xl"></i></button>
                        </td>
                    </tr>
                    
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-medium text-[#728495]">SRV-003</td>
                        <td class="px-6 py-4 font-bold">Reparación General de Motor</td>
                        <td class="px-6 py-4 text-center font-semibold border-l border-[#B4C5D8]/30">$ 500.00</td>
                        <td class="px-6 py-4 text-center font-semibold border-l border-[#B4C5D8]/30">$ 800.00</td>
                        <td class="px-6 py-4 text-center font-semibold border-l border-[#B4C5D8]/30">$ 1,200.00</td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Editar"><i class="ph ph-pencil-simple text-xl"></i></button>
                            <button class="text-red-400 hover:text-red-600 mx-1 transition-colors" title="Eliminar"><i class="ph ph-trash text-xl"></i></button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-[#B4C5D8] flex flex-col sm:flex-row items-center justify-between gap-4 bg-white">
            <span class="text-sm text-[#728495] font-medium">Mostrando 1 a 3 de 45 servicios registrados</span>
            <div class="flex gap-2">
                <button class="px-4 py-1.5 border border-[#B4C5D8] rounded text-[#728495] opacity-50 cursor-not-allowed font-medium">Anterior</button>
                <button class="px-4 py-1.5 bg-[#4A5B6A] text-white rounded shadow-sm font-medium">1</button>
                <button class="px-4 py-1.5 border border-[#B4C5D8] rounded text-[#728495] hover:bg-[#B4C5D8]/20 transition font-medium">2</button>
                <button class="px-4 py-1.5 border border-[#B4C5D8] rounded text-[#728495] hover:bg-[#B4C5D8]/20 transition font-medium">Siguiente</button>
            </div>
        </div>
    </div>

@endsection
@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Directorio de Proveedores</h2>
            <p class="text-[#728495]">Gestión de empresas mayoristas y distribuidores</p>
        </div>
        <button class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
            <i class="ph ph-truck text-xl"></i>
            Nuevo Proveedor
        </button>
    </div>

    <div class="bg-white p-4 rounded-xl border border-[#98A9BE]/50 shadow-sm mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-[#728495] text-xl"></i>
            <input type="text" placeholder="Buscar por empresa, RIF o contacto..." class="w-full pl-10 pr-4 py-2 border border-[#B4C5D8] rounded-lg focus:outline-none focus:border-[#4A5B6A] text-[#263A47]">
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <select class="flex-1 md:flex-none border border-[#B4C5D8] rounded-lg px-4 py-2 text-[#263A47] focus:outline-none focus:border-[#4A5B6A] bg-white cursor-pointer">
                <option value="">Todas las categorías</option>
                <option value="repuestos">Repuestos Generales</option>
                <option value="lubricantes">Aceites y Químicos</option>
                <option value="herramientas">Herramientas</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#B4C5D8]/20 border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Empresa / RIF</th>
                        <th class="px-6 py-4 font-bold">Contacto Principal</th>
                        <th class="px-6 py-4 font-bold">Teléfono</th>
                        <th class="px-6 py-4 font-bold text-center">Categoría principal</th>
                        <th class="px-6 py-4 font-bold text-center border-l border-[#B4C5D8]/50">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold">Autopartes Venezuela C.A.</p>
                            <p class="text-xs text-[#728495]">J-12345678-9</p>
                        </td>
                        <td class="px-6 py-4 font-medium">Carlos Mendoza</td>
                        <td class="px-6 py-4 text-[#728495]">0414-1234567</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-[#B4C5D8]/30 text-[#263A47] px-3 py-1 rounded-full text-xs font-bold">Repuestos Gen.</span>
                        </td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Editar"><i class="ph ph-pencil-simple text-xl"></i></button>
                            <button class="text-red-400 hover:text-red-600 mx-1 transition-colors" title="Eliminar"><i class="ph ph-trash text-xl"></i></button>
                        </td>
                    </tr>
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold">Lubricantes de Occidente</p>
                            <p class="text-xs text-[#728495]">J-98765432-1</p>
                        </td>
                        <td class="px-6 py-4 font-medium">Ana Suárez</td>
                        <td class="px-6 py-4 text-[#728495]">0424-7654321</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-[#B4C5D8]/30 text-[#263A47] px-3 py-1 rounded-full text-xs font-bold">Aceites y Quím.</span>
                        </td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Editar"><i class="ph ph-pencil-simple text-xl"></i></button>
                            <button class="text-red-400 hover:text-red-600 mx-1 transition-colors" title="Eliminar"><i class="ph ph-trash text-xl"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
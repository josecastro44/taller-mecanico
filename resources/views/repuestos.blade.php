@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Gestión de Repuestos e Insumos</h2>
            <p class="text-[#728495]">Control de inventario, costos de adquisición y precios de venta</p>
        </div>
        <button onclick="abrirModalRepuesto()" class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
            <i class="ph ph-plus-circle text-xl"></i>
            Registrar Repuesto
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-red-50 border border-red-200 p-4 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                    <i class="ph ph-warning-circle text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-red-800">Stock Crítico</p>
                    <p class="text-xs text-red-600">2 artículos por debajo del mínimo</p>
                </div>
            </div>
            <button class="text-sm bg-red-100 text-red-700 px-3 py-1.5 rounded hover:bg-red-200 font-semibold transition">Ver lista</button>
        </div>
        <div class="bg-green-50 border border-green-200 p-4 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="ph ph-check-circle text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-green-800">Inventario Sano</p>
                    <p class="text-xs text-green-600">Total valorizado: $4,250.00 en costo</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl border border-[#98A9BE]/50 shadow-sm mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-[#728495] text-xl"></i>
            <input type="text" placeholder="Buscar por código, nombre o marca..." class="w-full pl-10 pr-4 py-2 border border-[#B4C5D8] rounded-lg focus:outline-none focus:border-[#4A5B6A] text-[#263A47]">
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <select class="flex-1 sm:flex-none border border-[#B4C5D8] rounded-lg px-4 py-2 text-[#263A47] focus:outline-none focus:border-[#4A5B6A] bg-white cursor-pointer">
                <option value="">Todas las categorías</option>
                <option value="lubricantes">Lubricantes y Filtros</option>
                <option value="frenos">Sistema de Frenos</option>
                <option value="motor">Partes de Motor</option>
                <option value="electricidad">Electricidad</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#B4C5D8]/20 border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Código / Pieza</th>
                        <th class="px-6 py-4 font-bold text-center">Stock</th>
                        <th class="px-6 py-4 font-bold text-right">Costo (Compra)</th>
                        <th class="px-6 py-4 font-bold text-right">Precio Venta (PVP)</th>
                        <th class="px-6 py-4 font-bold text-center border-l border-[#B4C5D8]/50">Margen</th>
                        <th class="px-6 py-4 font-bold text-center border-l border-[#B4C5D8]/50">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold">Pastillas de Freno (Cerámica)</p>
                            <p class="text-xs text-[#728495]">Cod: REP-001 | Marca: Bosch</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-[#B4C5D8]/30 text-[#263A47] px-3 py-1 rounded-full font-bold">15 und</span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium text-red-600">$ 25.00</td>
                        <td class="px-6 py-4 text-right font-bold text-green-700">$ 45.00</td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <span class="text-[#4A5B6A] font-bold">+80%</span>
                        </td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Editar"><i class="ph ph-pencil-simple text-xl"></i></button>
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Agregar Stock"><i class="ph ph-plus-square text-xl"></i></button>
                        </td>
                    </tr>

                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-red-50 transition bg-red-50/30">
                        <td class="px-6 py-4">
                            <p class="font-bold">Aceite Sintético 5W-30</p>
                            <p class="text-xs text-[#728495]">Cod: REP-042 | Marca: Castrol (Galón)</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full font-bold">2 und</span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium text-red-600">$ 18.00</td>
                        <td class="px-6 py-4 text-right font-bold text-green-700">$ 30.00</td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <span class="text-[#4A5B6A] font-bold">+66%</span>
                        </td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Editar"><i class="ph ph-pencil-simple text-xl"></i></button>
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Agregar Stock"><i class="ph ph-plus-square text-xl"></i></button>
                        </td>
                    </tr>

                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold">Filtro de Aceite</p>
                            <p class="text-xs text-[#728495]">Cod: REP-015 | Marca: Fram</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-[#B4C5D8]/30 text-[#263A47] px-3 py-1 rounded-full font-bold">24 und</span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium text-red-600">$ 4.50</td>
                        <td class="px-6 py-4 text-right font-bold text-green-700">$ 8.00</td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <span class="text-[#4A5B6A] font-bold">+77%</span>
                        </td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Editar"><i class="ph ph-pencil-simple text-xl"></i></button>
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Agregar Stock"><i class="ph ph-plus-square text-xl"></i></button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-[#B4C5D8] flex flex-col sm:flex-row items-center justify-between gap-4 bg-white">
            <span class="text-sm text-[#728495] font-medium">Mostrando 1 a 3 de 120 repuestos registrados</span>
            <div class="flex gap-2">
                <button class="px-4 py-1.5 border border-[#B4C5D8] rounded text-[#728495] opacity-50 cursor-not-allowed font-medium">Anterior</button>
                <button class="px-4 py-1.5 bg-[#4A5B6A] text-white rounded shadow-sm font-medium">1</button>
                <button class="px-4 py-1.5 border border-[#B4C5D8] rounded text-[#728495] hover:bg-[#B4C5D8]/20 transition font-medium">2</button>
                <button class="px-4 py-1.5 border border-[#B4C5D8] rounded text-[#728495] hover:bg-[#B4C5D8]/20 transition font-medium">Siguiente</button>
            </div>
        </div>
    </div>

<div id="modal-repuesto" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
        
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-hidden transform transition-all">
            
            <div class="px-6 py-4 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10">
                <h3 class="text-lg font-bold text-[#263A47]">Nuevo Ingreso a Inventario</h3>
                <button onclick="cerrarModalRepuesto()" class="text-[#728495] hover:text-red-500 transition">
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>
            
            <form class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="md:col-span-2 text-xs font-bold text-[#4A5B6A] uppercase tracking-wider border-b border-[#B4C5D8]/30 pb-1">
                        Identificación de la Pieza
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Nombre del Repuesto *</label>
                        <input type="text" placeholder="Ej. Pastillas de Freno" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Código / SKU</label>
                        <input type="text" placeholder="Ej. REP-001" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Marca</label>
                        <input type="text" placeholder="Ej. Bosch, Castrol..." class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Categoría</label>
                        <select class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 bg-white outline-none focus:border-[#263A47]">
                            <option>Lubricantes</option>
                            <option>Frenos</option>
                            <option>Motor</option>
                            <option>Suspensión</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 text-xs font-bold text-[#4A5B6A] uppercase tracking-wider border-b border-[#B4C5D8]/30 pb-1 mt-2">
                        Cantidades y Precios
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Stock Inicial</label>
                            <input type="number" value="0" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-red-500 mb-1">Stock Mínimo</label>
                            <input type="number" value="5" class="w-full border border-red-200 rounded-lg px-4 py-2 outline-none focus:border-red-500" title="El sistema avisará cuando baje de este número">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Costo Compra ($)</label>
                            <input type="number" step="0.01" placeholder="0.00" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-green-600 mb-1">Precio Venta ($)</label>
                            <input type="number" step="0.01" placeholder="0.00" class="w-full border border-green-200 rounded-lg px-4 py-2 outline-none focus:border-green-600">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 border-t border-[#B4C5D8]/50 pt-5">
                    <button type="button" onclick="cerrarModalRepuesto()" class="px-6 py-2 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#B4C5D8]/20 transition">
                        Cerrar
                    </button>
                    <button type="button" onclick="cerrarModalRepuesto()" class="px-6 py-2 bg-[#263A47] text-white font-semibold rounded-lg hover:bg-[#4A5B6A] shadow-md transition">
                        Guardar en Inventario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalRepuesto() {
            document.getElementById('modal-repuesto').classList.remove('hidden');
        }
        function cerrarModalRepuesto() {
            document.getElementById('modal-repuesto').classList.add('hidden');
        }
    </script>

@endsection
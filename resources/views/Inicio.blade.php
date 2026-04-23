@extends('layouts.app')

@section('contenido')
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-blue-600">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Mis Vehículos Asignados</p>
                <p class="text-3xl font-bold text-[#263A47]">4</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="ph ph-car text-2xl text-blue-600"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-amber-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">En Reparación (Activos)</p>
                <p class="text-3xl font-bold text-[#263A47]">2</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center">
                <i class="ph ph-wrench text-2xl text-amber-600"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-green-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Terminados Hoy</p>
                <p class="text-3xl font-bold text-[#263A47]">2</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="ph ph-check-circle text-2xl text-green-600"></i>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm p-6 relative">
        
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-[#263A47]">Mi Flujo de Trabajo</h3>
            <button class="text-sm bg-[#4A5B6A] text-white px-4 py-2 rounded hover:bg-[#263A47] transition">
                <i class="ph ph-clipboard-text mr-1"></i> Reportar Novedad
            </button>
        </div>
        
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-[#B4C5D8] text-[#728495] text-sm">
                    <th class="pb-3 font-semibold">Placa</th>
                    <th class="pb-3 font-semibold">Vehículo</th>
                    <th class="pb-3 font-semibold">Estatus</th>
                    <th class="pb-3 font-semibold text-right">Tipo de Servicio</th>
                </tr>
            </thead>
            <tbody class="text-sm text-[#263A47]">
                
                <tr class="border-b border-[#B4C5D8]/50 hover:bg-[#B4C5D8]/10 transition">
                    <td class="py-4 font-medium">XYZ-123</td>
                    <td class="py-4">Toyota Corolla 2018</td>
                    <td class="py-4">
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">En Reparación</span>
                    </td>
                    <td class="py-4 text-right">
                        <button onclick="openModal('modal-xyz123')" class="inline-flex items-center gap-1 bg-[#F3F6F8] border border-[#B4C5D8] text-[#4A5B6A] px-3 py-1.5 rounded-md text-xs font-bold hover:bg-[#E2E8F0] transition focus:outline-none">
                            <i class="ph ph-engine text-lg"></i> Cambio de Empacadura
                            <i class="ph ph-caret-right ml-1 text-[#98A9BE]"></i>
                        </button>
                    </td>
                </tr>
                
                <tr class="border-b border-[#B4C5D8]/50 hover:bg-[#B4C5D8]/10 transition">
                    <td class="py-4 font-medium">ABC-987</td>
                    <td class="py-4">Ford Fiesta 2015</td>
                    <td class="py-4">
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Listo para Entrega</span>
                    </td>
                    <td class="py-4 text-right">
                        <button onclick="openModal('modal-abc987')" class="inline-flex items-center gap-1 bg-[#F3F6F8] border border-[#B4C5D8] text-[#4A5B6A] px-3 py-1.5 rounded-md text-xs font-bold hover:bg-[#E2E8F0] transition focus:outline-none">
                            <i class="ph ph-drop text-lg"></i> Aceite y Filtros
                            <i class="ph ph-caret-right ml-1 text-[#98A9BE]"></i>
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-[#B4C5D8]/10 transition">
                    <td class="py-4 font-medium">LMN-456</td>
                    <td class="py-4">Chevrolet Spark 2012</td>
                    <td class="py-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">En Diagnóstico</span>
                    </td>
                    <td class="py-4 text-right">
                        <button onclick="openModal('modal-lmn456')" class="inline-flex items-center gap-1 bg-[#F3F6F8] border border-[#B4C5D8] text-[#4A5B6A] px-3 py-1.5 rounded-md text-xs font-bold hover:bg-[#E2E8F0] transition focus:outline-none">
                            <i class="ph ph-magnifying-glass text-lg"></i> Escaneo General
                            <i class="ph ph-caret-right ml-1 text-[#98A9BE]"></i>
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <div id="modal-xyz123" class="hidden fixed inset-0 bg-[#263A47]/40 backdrop-blur-sm z-[100] flex items-center justify-center p-4" onclick="closeModal('modal-xyz123')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]" onclick="event.stopPropagation()">
            
            <div class="flex items-center justify-between p-4 border-b border-[#B4C5D8]/50">
                <h3 class="text-lg font-bold text-[#263A47]">Detalles del Servicio</h3>
                <button onclick="closeModal('modal-xyz123')" class="text-[#728495] hover:text-red-500 transition focus:outline-none">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-5 overflow-y-auto">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-[#263A47] text-xl mb-1">Cambio de Empacadura</h4>
                        <p class="text-sm text-[#728495]">Servicio de motor preventivo</p>
                    </div>
                    <span class="bg-purple-100 text-purple-700 text-xs px-3 py-1 rounded-md font-bold uppercase tracking-wider border border-purple-200">Paquete Carga Pesada</span>
                </div>
                
                <div>
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider mb-2">Mecánico Asignado</p>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600"><i class="ph ph-user text-lg"></i></div>
                        <span class="text-sm text-[#263A47] font-semibold">Chema (Tú)</span>
                    </div>
                </div>
                
                <div>
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider mb-2">Descripción Inicial</p>
                    <div class="text-sm text-[#4A5B6A] bg-[#F3F6F8] p-4 rounded-xl border border-[#B4C5D8]/50 leading-relaxed italic">
                        "El cliente reporta goteo de aceite en el piso de su garaje. Se requiere lavado a presión del motor, diagnóstico de fugas y reemplazo de empacadura de la tapa de válvulas."
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider">Repuestos / Insumos Utilizados</p>
                        <button class="text-xs bg-[#F3F6F8] text-[#4A5B6A] px-2 py-1 rounded font-bold border border-[#B4C5D8]/50 hover:bg-[#E2E8F0] transition flex items-center gap-1">
                            <i class="ph ph-plus"></i> Agregar
                        </button>
                    </div>
                    <ul class="text-sm text-[#4A5B6A] bg-white rounded-xl border border-[#B4C5D8]/50 divide-y divide-[#B4C5D8]/30">
                        <li class="p-3 flex justify-between items-center hover:bg-[#F3F6F8] transition">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-nut text-[#728495] text-lg"></i>
                                <span>Empacadura Tapa de Válvulas</span>
                            </div>
                            <span class="font-bold text-[#263A47] bg-[#B4C5D8]/20 px-2 py-0.5 rounded">x1</span>
                        </li>
                        <li class="p-3 flex justify-between items-center hover:bg-[#F3F6F8] transition">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-drop text-[#728495] text-lg"></i>
                                <span>Silicón Alta Temperatura</span>
                            </div>
                            <span class="font-bold text-[#263A47] bg-[#B4C5D8]/20 px-2 py-0.5 rounded">x1</span>
                        </li>
                        <li class="p-3 flex justify-between items-center hover:bg-[#F3F6F8] transition">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-spray-bottle text-[#728495] text-lg"></i>
                                <span>Desengrasante Motor</span>
                            </div>
                            <span class="font-bold text-[#263A47] bg-[#B4C5D8]/20 px-2 py-0.5 rounded">x1</span>
                        </li>
                    </ul>
                </div>

                <div class="pt-4 border-t border-[#B4C5D8]/30 flex justify-between items-center">
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider">Precio Acordado</p>
                    <p class="text-2xl font-black text-green-600">$180.00</p>
                </div>
            </div>

            <div class="p-4 bg-[#F3F6F8] border-t border-[#B4C5D8]/50 flex justify-end gap-3 mt-auto">
                <button onclick="closeModal('modal-xyz123')" class="px-4 py-2 text-[#4A5B6A] text-sm font-bold hover:bg-[#E2E8F0] rounded-lg transition">Cancelar</button>
                <button class="px-4 py-2 bg-[#4A5B6A] hover:bg-[#263A47] text-white rounded-lg transition flex items-center gap-2 text-sm font-bold shadow-sm">
                    <i class="ph ph-pencil-simple text-lg"></i> Editar Detalles
                </button>
            </div>
        </div>
    </div>

    <div id="modal-abc987" class="hidden fixed inset-0 bg-[#263A47]/40 backdrop-blur-sm z-[100] flex items-center justify-center p-4" onclick="closeModal('modal-abc987')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-4 border-b border-[#B4C5D8]/50">
                <h3 class="text-lg font-bold text-[#263A47]">Detalles del Servicio</h3>
                <button onclick="closeModal('modal-abc987')" class="text-[#728495] hover:text-red-500 transition focus:outline-none">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-5 overflow-y-auto">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-[#263A47] text-xl mb-1">Cambio de Aceite</h4>
                        <p class="text-sm text-[#728495]">Mantenimiento Rutinario</p>
                    </div>
                    <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-md font-bold uppercase tracking-wider border border-gray-300">Paquete Sencillo</span>
                </div>
                <div>
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider mb-2">Mecánico Asignado</p>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600"><i class="ph ph-user text-lg"></i></div>
                        <span class="text-sm text-[#263A47] font-semibold">Chema (Tú)</span>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider mb-2">Descripción Inicial</p>
                    <div class="text-sm text-[#4A5B6A] bg-[#F3F6F8] p-4 rounded-xl border border-[#B4C5D8]/50 leading-relaxed italic">
                        "Aceite semisintético 15W-40, cambio de filtro de aceite y revisión rápida de fluidos de freno y refrigerante."
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider">Repuestos / Insumos Utilizados</p>
                        <button class="text-xs bg-[#F3F6F8] text-[#4A5B6A] px-2 py-1 rounded font-bold border border-[#B4C5D8]/50 hover:bg-[#E2E8F0] transition flex items-center gap-1">
                            <i class="ph ph-plus"></i> Agregar
                        </button>
                    </div>
                    <ul class="text-sm text-[#4A5B6A] bg-white rounded-xl border border-[#B4C5D8]/50 divide-y divide-[#B4C5D8]/30">
                        <li class="p-3 flex justify-between items-center hover:bg-[#F3F6F8] transition">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-drop text-[#728495] text-lg"></i>
                                <span>Aceite Semisintético 15W-40 (Lts)</span>
                            </div>
                            <span class="font-bold text-[#263A47] bg-[#B4C5D8]/20 px-2 py-0.5 rounded">x4</span>
                        </li>
                        <li class="p-3 flex justify-between items-center hover:bg-[#F3F6F8] transition">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-cylinder text-[#728495] text-lg"></i>
                                <span>Filtro de Aceite</span>
                            </div>
                            <span class="font-bold text-[#263A47] bg-[#B4C5D8]/20 px-2 py-0.5 rounded">x1</span>
                        </li>
                    </ul>
                </div>

                <div class="pt-4 border-t border-[#B4C5D8]/30 flex justify-between items-center">
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider">Precio Acordado</p>
                    <p class="text-2xl font-black text-green-600">$45.00</p>
                </div>
            </div>
            <div class="p-4 bg-[#F3F6F8] border-t border-[#B4C5D8]/50 flex justify-end gap-3 mt-auto">
                <button onclick="closeModal('modal-abc987')" class="px-4 py-2 text-[#4A5B6A] text-sm font-bold hover:bg-[#E2E8F0] rounded-lg transition">Cancelar</button>
                <button class="px-4 py-2 bg-[#4A5B6A] hover:bg-[#263A47] text-white rounded-lg transition flex items-center gap-2 text-sm font-bold shadow-sm">
                    <i class="ph ph-pencil-simple text-lg"></i> Editar Detalles
                </button>
            </div>
        </div>
    </div>

    <div id="modal-lmn456" class="hidden fixed inset-0 bg-[#263A47]/40 backdrop-blur-sm z-[100] flex items-center justify-center p-4" onclick="closeModal('modal-lmn456')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-4 border-b border-[#B4C5D8]/50">
                <h3 class="text-lg font-bold text-[#263A47]">Detalles del Servicio</h3>
                <button onclick="closeModal('modal-lmn456')" class="text-[#728495] hover:text-red-500 transition focus:outline-none">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-5 overflow-y-auto">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-[#263A47] text-xl mb-1">Escaneo General</h4>
                        <p class="text-sm text-[#728495]">Revisión electrónica</p>
                    </div>
                    <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-md font-bold uppercase tracking-wider border border-blue-200">Paquete Alta Gama</span>
                </div>
                <div>
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider mb-2">Mecánico Asignado</p>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600"><i class="ph ph-user text-lg"></i></div>
                        <span class="text-sm text-[#263A47] font-semibold">Chema (Tú)</span>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider mb-2">Descripción Inicial</p>
                    <div class="text-sm text-[#4A5B6A] bg-[#F3F6F8] p-4 rounded-xl border border-[#B4C5D8]/50 leading-relaxed italic">
                        "Luz de 'Check Engine' encendida en el tablero. Se realizará un escaneo por computadora OBD2 para determinar los códigos de error antes de proceder con reparaciones mecánicas."
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider">Repuestos / Insumos Utilizados</p>
                        <button class="text-xs bg-[#F3F6F8] text-[#4A5B6A] px-2 py-1 rounded font-bold border border-[#B4C5D8]/50 hover:bg-[#E2E8F0] transition flex items-center gap-1">
                            <i class="ph ph-plus"></i> Agregar
                        </button>
                    </div>
                    <div class="bg-gray-50 border border-dashed border-[#B4C5D8] rounded-xl p-4 text-center text-sm text-[#728495] font-medium">
                        <i class="ph ph-info text-xl mb-1 block text-[#98A9BE]"></i>
                        No se han utilizado repuestos para este servicio (Solo mano de obra).
                    </div>
                </div>

                <div class="pt-4 border-t border-[#B4C5D8]/30 flex justify-between items-center">
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider">Precio Acordado</p>
                    <p class="text-2xl font-black text-green-600">$25.00</p>
                </div>
            </div>
            <div class="p-4 bg-[#F3F6F8] border-t border-[#B4C5D8]/50 flex justify-end gap-3 mt-auto">
                <button onclick="closeModal('modal-lmn456')" class="px-4 py-2 text-[#4A5B6A] text-sm font-bold hover:bg-[#E2E8F0] rounded-lg transition">Cancelar</button>
                <button class="px-4 py-2 bg-[#4A5B6A] hover:bg-[#263A47] text-white rounded-lg transition flex items-center gap-2 text-sm font-bold shadow-sm">
                    <i class="ph ph-pencil-simple text-lg"></i> Editar Detalles
                </button>
            </div>
        </div>
    </div>

    <script>
        // Función para abrir la ventana
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        // Función para cerrar la ventana
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>

@endsection
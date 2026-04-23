@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Historial de Trabajos</h2>
            <p class="text-[#728495]">Registro de vehículos reparados y servicios completados.</p>
        </div>
        <div class="relative w-full md:w-80">
            <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-[#728495] text-xl"></i>
            <input type="text" placeholder="Buscar por placa o modelo..." class="w-full pl-10 pr-4 py-2 border border-[#B4C5D8] rounded-lg focus:outline-none focus:border-[#4A5B6A] text-[#263A47] bg-white shadow-sm">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-green-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Terminados (Este Mes)</p>
                <p class="text-3xl font-bold text-[#263A47]">18</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="ph ph-check-fat text-2xl text-green-600"></i>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-[#4A5B6A]">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Horas Facturadas</p>
                <p class="text-3xl font-bold text-[#263A47]">42h</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-[#B4C5D8]/30 flex items-center justify-center">
                <i class="ph ph-clock text-2xl text-[#4A5B6A]"></i>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-purple-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Especialidad (Motor)</p>
                <p class="text-3xl font-bold text-[#263A47]">12</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center">
                <i class="ph ph-engine text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-[#B4C5D8] bg-[#B4C5D8]/10 flex justify-between items-center">
            <h3 class="text-lg font-bold text-[#263A47]">Últimos Trabajos Entregados</h3>
            <button class="text-sm text-[#4A5B6A] font-bold hover:underline flex items-center gap-1">
                <i class="ph ph-funnel text-lg"></i> Filtrar por mes
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Fecha de Entrega</th>
                        <th class="px-6 py-4 font-bold">Vehículo / Placa</th>
                        <th class="px-6 py-4 font-bold">Servicio Realizado</th>
                        <th class="px-6 py-4 font-bold text-center">Estatus</th>
                        <th class="px-6 py-4 font-bold text-center border-l border-[#B4C5D8]/50">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-medium text-[#728495]">Hoy, 10:30 AM</td>
                        <td class="px-6 py-4">
                            <p class="font-bold">Honda Civic 2010</p>
                            <p class="text-xs font-mono text-[#98A9BE] mt-0.5">DEF-456</p>
                        </td>
                        <td class="px-6 py-4 font-medium text-[#4A5B6A]">Revisión de Frenos</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">
                                <i class="ph ph-check mr-1"></i> Terminado
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button onclick="openModal('modal-historial-1')" class="bg-[#F3F6F8] border border-[#B4C5D8] text-[#4A5B6A] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#E2E8F0] transition flex items-center justify-center gap-1 mx-auto">
                                <i class="ph ph-eye text-lg"></i> Ver Detalles
                            </button>
                        </td>
                    </tr>

                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-medium text-[#728495]">03 Mar 2026</td>
                        <td class="px-6 py-4">
                            <p class="font-bold">Chevrolet Silverado</p>
                            <p class="text-xs font-mono text-[#98A9BE] mt-0.5">GHI-789</p>
                        </td>
                        <td class="px-6 py-4 font-medium text-[#4A5B6A]">Limpieza de Inyectores</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">
                                <i class="ph ph-check mr-1"></i> Terminado
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button class="bg-[#F3F6F8] border border-[#B4C5D8] text-[#4A5B6A] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#E2E8F0] transition flex items-center justify-center gap-1 mx-auto">
                                <i class="ph ph-eye text-lg"></i> Ver Detalles
                            </button>
                        </td>
                    </tr>

                    <tr class="hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-medium text-[#728495]">28 Feb 2026</td>
                        <td class="px-6 py-4">
                            <p class="font-bold">Hyundai Tucson</p>
                            <p class="text-xs font-mono text-[#98A9BE] mt-0.5">JKL-012</p>
                        </td>
                        <td class="px-6 py-4 font-medium text-[#4A5B6A]">Cambio de Aceite</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">
                                <i class="ph ph-check mr-1"></i> Terminado
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button class="bg-[#F3F6F8] border border-[#B4C5D8] text-[#4A5B6A] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#E2E8F0] transition flex items-center justify-center gap-1 mx-auto">
                                <i class="ph ph-eye text-lg"></i> Ver Detalles
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-[#B4C5D8] flex items-center justify-between bg-white">
            <span class="text-sm text-[#728495] font-medium">Mostrando 1 a 3 de 18 registros</span>
            <div class="flex gap-2">
                <button class="px-3 py-1.5 border border-[#B4C5D8] rounded text-[#728495] opacity-50 cursor-not-allowed font-medium text-sm">Anterior</button>
                <button class="px-3 py-1.5 border border-[#B4C5D8] rounded text-[#728495] hover:bg-[#B4C5D8]/20 transition font-medium text-sm">Siguiente</button>
            </div>
        </div>
    </div>

    <div id="modal-historial-1" class="hidden fixed inset-0 bg-[#263A47]/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4" onclick="closeModal('modal-historial-1')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col" onclick="event.stopPropagation()">
            
            <div class="bg-green-600 p-6 text-white flex justify-between items-start">
                <div>
                    <span class="bg-green-800/50 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-2 inline-block">Trabajo Completado</span>
                    <h3 class="text-2xl font-black">Revisión de Frenos</h3>
                    <p class="text-green-100 text-sm mt-1">O.S. #0052 • Finalizado Hoy, 10:30 AM</p>
                </div>
                <button onclick="closeModal('modal-historial-1')" class="text-green-200 hover:text-white transition focus:outline-none">
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <p class="text-xs text-[#98A9BE] uppercase font-bold mb-1">Vehículo</p>
                        <p class="font-bold text-[#263A47]">Honda Civic 2010</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <p class="text-xs text-[#98A9BE] uppercase font-bold mb-1">Placa</p>
                        <p class="font-bold text-[#263A47] font-mono">DEF-456</p>
                    </div>
                </div>

                <div>
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider mb-2">Notas Finales del Mecánico</p>
                    <div class="text-sm text-[#4A5B6A] bg-green-50 p-4 rounded-xl border border-green-200 leading-relaxed italic">
                        <i class="ph ph-check-circle text-green-600 mr-1 text-lg"></i>
                        "Se reemplazaron las pastillas de freno delanteras y se rectificaron los discos. El sistema hidráulico fue purgado exitosamente. Prueba de manejo OK."
                    </div>
                </div>

                <div>
                    <p class="text-xs text-[#98A9BE] uppercase font-bold tracking-wider mb-2">Repuestos Utilizados</p>
                    <ul class="text-sm text-[#4A5B6A] bg-white rounded-xl border border-[#B4C5D8]/50 divide-y divide-[#B4C5D8]/30">
                        <li class="p-3 flex justify-between items-center">
                            <span class="font-medium"><i class="ph ph-disc text-[#728495] mr-2"></i>Juego de Pastillas Cerámicas</span>
                            <span class="font-bold text-[#263A47] bg-[#B4C5D8]/20 px-2 py-0.5 rounded">x1</span>
                        </li>
                        <li class="p-3 flex justify-between items-center">
                            <span class="font-medium"><i class="ph ph-drop text-[#728495] mr-2"></i>Líquido de Frenos DOT 4</span>
                            <span class="font-bold text-[#263A47] bg-[#B4C5D8]/20 px-2 py-0.5 rounded">x1</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border-t border-[#B4C5D8]/50 flex justify-end">
                <button onclick="closeModal('modal-historial-1')" class="px-5 py-2 bg-white border border-[#B4C5D8] text-[#4A5B6A] font-bold hover:bg-gray-100 rounded-lg transition shadow-sm">
                    Cerrar Detalles
                </button>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>

@endsection
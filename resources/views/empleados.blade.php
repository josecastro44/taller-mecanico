@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Empleados y Nómina Automatizada</h2>
            <p class="text-[#728495]">Cálculo de comisiones por servicio y cuentas por liquidar</p>
        </div>
        <button onclick="abrirModalEmpleado()" class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
            <i class="ph ph-user-plus text-xl"></i>
            Nuevo Empleado
        </button>
    </div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-red-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Nómina Pendiente</p>
                <p class="text-3xl font-bold text-red-600">$485.00</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center">
                <i class="ph ph-wallet text-2xl text-red-500"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-blue-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Servicios Realizados (Mes)</p>
                <p class="text-3xl font-bold text-[#263A47]">34</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="ph ph-wrench text-2xl text-blue-500"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-emerald-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Mecánicos Activos</p>
                <p class="text-3xl font-bold text-[#263A47]">4</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-emerald-50 flex items-center justify-center">
                <i class="ph ph-users-three text-2xl text-emerald-500"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        
        <div class="p-5 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10">
            <h3 class="text-lg font-bold text-[#263A47]">Cuentas por Pagar (Comisiones Acumuladas)</h3>
            <button class="text-sm border border-[#4A5B6A] text-[#4A5B6A] px-4 py-2 rounded-lg hover:bg-[#4A5B6A] hover:text-white transition font-semibold">
                Historial de Pagos
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Mecánico</th>
                        <th class="px-6 py-4 font-bold text-center">Tasa Comisión</th>
                        <th class="px-6 py-4 font-bold text-center">Servicios Pendientes</th>
                        <th class="px-6 py-4 font-bold text-right">M. Obra Generada</th>
                        <th class="px-6 py-4 font-bold text-right bg-green-50/50">A Pagar</th>
                        <th class="px-6 py-4 font-bold text-center border-l border-[#B4C5D8]/50">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#728495] text-white flex items-center justify-center font-bold">RC</div>
                                <div>
                                    <p class="font-bold">Roberto Carlos</p>
                                    <p class="text-xs text-[#728495]">Especialista Motor</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center"><span class="bg-[#B4C5D8]/30 text-[#263A47] px-3 py-1 rounded-full font-bold">30%</span></td>
                        <td class="px-6 py-4 text-center font-medium">5 Órdenes</td>
                        <td class="px-6 py-4 text-right font-medium text-[#728495]">$ 850.00</td>
                        <td class="px-6 py-4 text-right font-bold text-green-700 bg-green-50/30 text-lg">$ 255.00</td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button class="bg-[#4A5B6A] text-white px-4 py-2 rounded shadow hover:bg-[#263A47] transition font-semibold w-full">
                                Liquidar Pago
                            </button>
                        </td>
                    </tr>

                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#4A5B6A] text-white flex items-center justify-center font-bold">MG</div>
                                <div>
                                    <p class="font-bold">Miguel Gómez</p>
                                    <p class="text-xs text-[#728495]">Frenos y Suspensión</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center"><span class="bg-[#B4C5D8]/30 text-[#263A47] px-3 py-1 rounded-full font-bold">30%</span></td>
                        <td class="px-6 py-4 text-center font-medium">8 Órdenes</td>
                        <td class="px-6 py-4 text-right font-medium text-[#728495]">$ 600.00</td>
                        <td class="px-6 py-4 text-right font-bold text-green-700 bg-green-50/30 text-lg">$ 180.00</td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button class="bg-[#4A5B6A] text-white px-4 py-2 rounded shadow hover:bg-[#263A47] transition font-semibold w-full">
                                Liquidar Pago
                            </button>
                        </td>
                    </tr>

                    <tr class="hover:bg-[#B4C5D8]/10 transition opacity-60">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#B4C5D8] text-[#263A47] flex items-center justify-center font-bold">AP</div>
                                <div>
                                    <p class="font-bold">Andrés Pérez</p>
                                    <p class="text-xs text-[#728495]">Electricidad</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center"><span class="bg-[#B4C5D8]/30 text-[#263A47] px-3 py-1 rounded-full font-bold">35%</span></td>
                        <td class="px-6 py-4 text-center font-medium">0 Órdenes</td>
                        <td class="px-6 py-4 text-right font-medium text-[#728495]">$ 0.00</td>
                        <td class="px-6 py-4 text-right font-bold text-[#728495] bg-gray-50/30 text-lg">$ 0.00</td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button class="border border-[#B4C5D8] text-[#98A9BE] px-4 py-2 rounded cursor-not-allowed font-semibold w-full" disabled>
                                Sin Saldo
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <div id="modal-empleado" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-xl overflow-hidden transform transition-all">
            
            <div class="px-6 py-4 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10">
                <h3 class="text-lg font-bold text-[#263A47]">Registrar Nuevo Empleado</h3>
                <button onclick="cerrarModalEmpleado()" class="text-[#728495] hover:text-red-500 transition">
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>
            
            <form class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Nombre Completo *</label>
                        <input type="text" placeholder="Ej. Roberto Carlos" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Cédula / ID *</label>
                        <input type="text" placeholder="Ej. V-12345678" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Teléfono</label>
                        <input type="text" placeholder="Ej. 0414-0000000" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Especialidad</label>
                        <select class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 bg-white outline-none focus:border-[#263A47]">
                            <option>Mecánica General</option>
                            <option>Especialista Motor</option>
                            <option>Frenos y Suspensión</option>
                            <option>Electricidad Automotriz</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Tasa de Comisión (%) *</label>
                        <input type="number" min="0" max="100" placeholder="Ej. 30" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 border-t border-[#B4C5D8]/50 pt-5">
                    <button type="button" onclick="cerrarModalEmpleado()" class="px-5 py-2.5 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#B4C5D8]/20 transition">
                        Cancelar
                    </button>
                    <button type="button" onclick="cerrarModalEmpleado()" class="px-5 py-2.5 bg-[#263A47] text-white font-semibold rounded-lg hover:bg-[#4A5B6A] shadow-md transition">
                        Guardar Empleado
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalEmpleado() {
            document.getElementById('modal-empleado').classList.remove('hidden');
        }
        function cerrarModalEmpleado() {
            document.getElementById('modal-empleado').classList.add('hidden');
        }
    </script>

@endsection
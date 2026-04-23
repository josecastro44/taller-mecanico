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

    {{-- ALERTA DE ÉXITO --}}
    @if(session('exito'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
            <p class="font-bold">¡Excelente!</p>
            <p>{{ session('exito') }}</p>
        </div>
    @endif

    {{-- AQUI VA TUS TARJETAS (Nómina Pendiente, etc.) --}}
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

    {{-- AQUI VA TU TABLA ORIGINAL --}}
    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10">
            <h3 class="text-lg font-bold text-[#263A47]">Cuentas por Pagar (Comisiones Acumuladas)</h3>
            <button class="text-sm border border-[#4A5B6A] text-[#4A5B6A] px-4 py-2 rounded-lg hover:bg-[#4A5B6A] hover:text-white transition font-semibold">
                Historial de Pagos
            </button>
        </div>
        <div class="p-5 text-center text-[#728495]">
            <p>Por ahora mostraremos los datos estáticos, en el siguiente paso los traeremos de la BD.</p>
        </div>
    </div>

    {{-- EL MODAL AHORA ES UN FORMULARIO REAL --}}
    <div id="modal-empleado" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-xl overflow-hidden transform transition-all">
            
            <div class="px-6 py-4 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10">
                <h3 class="text-lg font-bold text-[#263A47]">Registrar Nuevo Empleado</h3>
                <button onclick="cerrarModalEmpleado()" class="text-[#728495] hover:text-red-500 transition">
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>
            
            <form action="/empleados" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Nombre Completo *</label>
                        <input type="text" name="nombre" placeholder="Ej. Roberto Carlos" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Cédula / ID *</label>
                        <input type="text" name="cedula" placeholder="Ej. V-12345678" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Teléfono</label>
                        <input type="text" name="telefono" placeholder="Ej. 0414-0000000" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Especialidad</label>
                        <select name="especialidad" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 bg-white outline-none focus:border-[#263A47]" required>
                            <option value="Mecánica General">Mecánica General</option>
                            <option value="Especialista Motor">Especialista Motor</option>
                            <option value="Frenos y Suspensión">Frenos y Suspensión</option>
                            <option value="Electricidad Automotriz">Electricidad Automotriz</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Tasa de Comisión (%) *</label>
                        <input type="number" name="comision" min="0" max="100" placeholder="Ej. 30" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 border-t border-[#B4C5D8]/50 pt-5">
                    <button type="button" onclick="cerrarModalEmpleado()" class="px-5 py-2.5 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#B4C5D8]/20 transition">
                        Cancelar
                    </button>
                    {{-- CAMBIO CLAVE: button type="submit" para que envíe los datos --}}
                    <button type="submit" class="px-5 py-2.5 bg-[#263A47] text-white font-semibold rounded-lg hover:bg-[#4A5B6A] shadow-md transition">
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
@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Empleados y Nómina Automatizada</h2>
            <p class="text-[#728495]">Cálculo de esquema híbrido: Sueldo Fijo + Comisiones</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('empleados.reporte') }}" target="_blank" class="bg-white text-[#263A47] border border-[#263A47] px-4 py-2.5 rounded-lg hover:bg-[#F1F4F8] shadow-sm transition-all flex items-center gap-2 font-medium">
                <i class="ph ph-file-pdf text-xl text-red-500"></i> Reporte PDF
            </a>
            <button onclick="abrirModalCrear()" class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
                <i class="ph ph-user-plus text-xl"></i> Nuevo Empleado
            </button>
        </div>
    </div>

    @if(session('exito'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
            <p class="font-bold">¡Excelente!</p>
            <p>{{ session('exito') }}</p>
        </div>
    @endif

    {{-- TARJETAS DINÁMICAS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-red-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Nómina Total a Pagar</p>
                <p class="text-3xl font-bold text-red-600">$ {{ number_format($nominaTotal, 2) }}</p>
                <p class="text-xs text-red-500 font-bold mt-1">Incluye $ {{ number_format($totalComisiones, 2) }} en comisiones</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center">
                <i class="ph ph-wallet text-2xl text-red-500"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-blue-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Servicios Realizados (Mes)</p>
                <p class="text-3xl font-bold text-[#263A47]">{{ $serviciosRealizados }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="ph ph-wrench text-2xl text-blue-500"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center justify-between border-l-4 border-l-emerald-500">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Personal Activo</p>
                <p class="text-3xl font-bold text-[#263A47]">{{ $mecanicosActivos }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-emerald-50 flex items-center justify-center">
                <i class="ph ph-users-three text-2xl text-emerald-500"></i>
            </div>
        </div>
    </div>

    {{-- TABLA REAL DE EMPLEADOS --}}
    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10">
            <h3 class="text-lg font-bold text-[#263A47]">Directorio de Personal y Esquema de Pago</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Empleado / Cédula</th>
                        <th class="px-6 py-4 font-bold">Acceso (Sistema)</th>
                        <th class="px-6 py-4 font-bold">Rol / Especialidad</th>
                        <th class="px-6 py-4 font-bold text-right">Sueldo Fijo</th>
                        <th class="px-6 py-4 font-bold text-center">Comisión (Mes)</th>
                        <th class="px-6 py-4 font-bold text-center border-l border-[#B4C5D8]/50">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    @forelse($empleados as $empleado)
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold">{{ $empleado->nombre }}</p>
                            <p class="text-xs text-[#728495]">{{ $empleado->cedula }} | {{ $empleado->telefono }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-blue-600"><i class="ph ph-envelope-simple"></i> {{ $empleado->user->email ?? 'No asignado' }}</p>
                            <p class="text-xs font-mono text-[#98A9BE] mt-0.5"><i class="ph ph-password"></i> ••••••</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-[#B4C5D8]/30 text-[#263A47] px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider block mb-1 w-max">{{ $empleado->user->rol ?? 'SIN ROL' }}</span>
                            <span class="text-xs text-[#4A5B6A] font-bold">{{ $empleado->especialidad }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-green-700">
                            $ {{ number_format($empleado->sueldo_base, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-[#4A5B6A]">
                            <p class="text-xs">{{ $empleado->comision }}% ({{ $comisionesPorEmpleado[$empleado->id]['ordenes_completadas'] ?? 0 }} serv.)</p>
                            <p class="text-lg text-green-600 mt-1">$ {{ number_format($comisionesPorEmpleado[$empleado->id]['comision_ganada'] ?? 0, 2) }}</p>
                        </td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30 flex justify-center gap-2">
                            <button type="button" onclick="abrirModalEditar({{ $empleado->id }}, '{{ $empleado->nombre }}', '{{ $empleado->cedula }}', '{{ $empleado->telefono }}', '{{ $empleado->especialidad }}', {{ $empleado->sueldo_base }}, {{ $empleado->comision }}, '{{ $empleado->user->email ?? '' }}', '{{ $empleado->user->rol ?? 'mecanico' }}')" class="text-[#4A5B6A] hover:text-[#263A47] transition-colors" title="Editar">
                                <i class="ph ph-pencil-simple text-xl"></i>
                            </button>
                            <form action="{{ route('empleados.eliminar', $empleado->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a este empleado?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors" title="Eliminar">
                                    <i class="ph ph-trash text-xl"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-[#728495]">No hay empleados registrados en el sistema.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL DINÁMICO (Crear / Editar) --}}
    <div id="modal-empleado" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-xl flex flex-col max-h-[90vh] transform transition-all">
            <div class="px-6 py-4 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10 shrink-0">
                <h3 id="modal-titulo" class="text-lg font-bold text-[#263A47]">Registrar Nuevo Empleado</h3>
                <button onclick="cerrarModal()" class="text-[#728495] hover:text-red-500 transition">
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>
            
            <form id="formulario-empleado" method="POST" class="p-6 overflow-y-auto overflow-x-hidden">
                @csrf
                <input type="hidden" name="_method" id="metodo-formulario" value="POST">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Nombre Completo *</label>
                        <input type="text" name="nombre" id="input-nombre" placeholder="Ej. Roberto Carlos" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Cédula / ID *</label>
                        <input type="text" name="cedula" id="input-cedula" placeholder="Ej. V-12345678" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Teléfono</label>
                        <input type="text" name="telefono" id="input-telefono" placeholder="Ej. 0414-0000000" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    </div>
                    
                    <div class="md:col-span-2 border-t border-[#B4C5D8]/50 pt-4 mt-2" id="seccion-acceso-titulo">
                        <p class="text-sm font-bold text-[#4A5B6A] uppercase tracking-wider mb-3">Acceso al Sistema</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Correo Electrónico (Usuario) *</label>
                        <input type="email" name="correo" id="input-correo" placeholder="Ej. empleado@taller.com" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Clave (Contraseña)</label>
                        <input type="text" name="clave" id="input-clave" placeholder="Si se deja vacío será: 123456" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                        <p class="text-[10px] text-[#728495] mt-1" id="texto-clave-ayuda">Dejar vacío para usar clave por defecto (123456) o no cambiarla al editar.</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Rol de Acceso (Permisos) *</label>
                        <select name="rol_sistema" id="input-rol" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 bg-white outline-none focus:border-[#263A47]" required>
                            <option value="mecanico">Mecánico (App de reparación)</option>
                            <option value="administrador">Administrador (Recepción e inventario)</option>
                            <option value="gerente">Gerente (Reportes y finanzas)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 border-t border-[#B4C5D8]/50 pt-4 mt-2">
                        <p class="text-sm font-bold text-[#4A5B6A] uppercase tracking-wider mb-3">Esquema Laboral y Pago</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Especialidad *</label>
                        <select name="especialidad" id="input-especialidad" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 bg-white outline-none focus:border-[#263A47]" required>
                            <option value="Mecánica General">Mecánica General</option>
                            <option value="Especialista Motor">Especialista Motor</option>
                            <option value="Frenos y Suspensión">Frenos y Suspensión</option>
                            <option value="Electricidad Automotriz">Electricidad Automotriz</option>
                            <option value="Recepcionista / Administrativo">Recepcionista / Administrativo</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Sueldo Fijo ($) *</label>
                        <input type="number" step="0.01" name="sueldo_base" id="input-sueldo" min="0" placeholder="Ej. 150.00" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Comisión por Servicio (%) *</label>
                        <input type="number" name="comision" id="input-comision" min="0" max="100" placeholder="Ej. 30" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="cerrarModal()" class="px-5 py-2.5 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#B4C5D8]/20 transition">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#263A47] text-white font-semibold rounded-lg hover:bg-[#4A5B6A] shadow-md transition">Guardar Empleado</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modal-empleado');
        const form = document.getElementById('formulario-empleado');
        const titulo = document.getElementById('modal-titulo');
        const metodoForm = document.getElementById('metodo-formulario');

        function cerrarModal() {
            modal.classList.add('hidden');
        }

        function abrirModalCrear() {
            titulo.innerText = "Registrar Nuevo Empleado";
            form.action = "{{ route('empleados.guardar') }}";
            metodoForm.value = "POST";
            
            document.getElementById('input-nombre').value = '';
            document.getElementById('input-cedula').value = '';
            document.getElementById('input-telefono').value = '';
            document.getElementById('input-especialidad').value = 'Mecánica General';
            document.getElementById('input-sueldo').value = '';
            document.getElementById('input-comision').value = '';
            document.getElementById('input-correo').value = '';
            document.getElementById('input-clave').value = '';
            document.getElementById('input-rol').value = 'mecanico';
            
            // Mostrar los campos siempre
            document.getElementById('input-correo').parentElement.style.display = 'block';
            document.getElementById('input-clave').parentElement.style.display = 'block';
            document.getElementById('input-rol').parentElement.style.display = 'block';
            document.getElementById('seccion-acceso-titulo').style.display = 'block';
            
            modal.classList.remove('hidden');
        }

        function abrirModalEditar(id, nombre, cedula, telefono, especialidad, sueldo, comision, correo, rol) {
            titulo.innerText = "Editar Empleado";
            form.action = "/empleados/" + id; 
            metodoForm.value = "PUT";
            
            document.getElementById('input-nombre').value = nombre;
            document.getElementById('input-cedula').value = cedula;
            document.getElementById('input-telefono').value = telefono === 'null' ? '' : telefono;
            document.getElementById('input-especialidad').value = especialidad;
            document.getElementById('input-sueldo').value = sueldo;
            document.getElementById('input-comision').value = comision;
            document.getElementById('input-correo').value = correo;
            document.getElementById('input-clave').value = '';
            document.getElementById('input-rol').value = rol;
            
            // Mostrar los campos de usuario al editar
            document.getElementById('input-correo').parentElement.style.display = 'block';
            document.getElementById('input-clave').parentElement.style.display = 'block';
            document.getElementById('input-rol').parentElement.style.display = 'block';
            document.getElementById('seccion-acceso-titulo').style.display = 'block';
            
            modal.classList.remove('hidden');
        }
    </script>
@endsection
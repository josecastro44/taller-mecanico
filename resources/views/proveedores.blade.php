@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Directorio de Proveedores</h2>
            <p class="text-[#728495]">Gestión de empresas mayoristas y distribuidores</p>
        </div>
        <button onclick="abrirModalCrear()" class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
            <i class="ph ph-truck text-xl"></i> Nuevo Proveedor
        </button>
    </div>

    @if(session('exito'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
            <p class="font-bold">¡Excelente!</p>
            <p>{{ session('exito') }}</p>
        </div>
    @endif

    {{-- BARRA DE BÚSQUEDA Y FILTROS --}}
    <form method="GET" action="{{ route('proveedores') }}" class="bg-white p-4 rounded-xl border border-[#98A9BE]/50 shadow-sm mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-[#728495] text-xl"></i>
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por empresa, RIF o contacto..." class="w-full pl-10 pr-4 py-2 border border-[#B4C5D8] rounded-lg focus:outline-none focus:border-[#4A5B6A] text-[#263A47]">
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <select name="categoria" onchange="this.form.submit()" class="flex-1 md:flex-none border border-[#B4C5D8] rounded-lg px-4 py-2 text-[#263A47] focus:outline-none focus:border-[#4A5B6A] bg-white cursor-pointer">
                <option value="">Todas las categorías</option>
                <option value="Repuestos Generales" {{ request('categoria') == 'Repuestos Generales' ? 'selected' : '' }}>Repuestos Generales</option>
                <option value="Aceites y Químicos" {{ request('categoria') == 'Aceites y Químicos' ? 'selected' : '' }}>Aceites y Químicos</option>
                <option value="Herramientas" {{ request('categoria') == 'Herramientas' ? 'selected' : '' }}>Herramientas</option>
            </select>
            @if(request('buscar') || request('categoria'))
              <a href="{{ route('proveedores') }}" class="px-4 py-2 border border-[#B4C5D8] text-[#728495] rounded-lg hover:bg-[#B4C5D8]/20 transition flex items-center">Limpiar</a>
            @endif
            <button type="submit" class="bg-[#4A5B6A] text-white px-4 py-2 rounded-lg hover:bg-[#263A47] shadow-sm transition">Buscar</button>
        </div>
    </form>

    {{-- TABLA DE PROVEEDORES --}}
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
                    @forelse($proveedores as $prov)
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold">{{ $prov->nombre }}</p>
                            <p class="text-xs text-[#728495]">{{ $prov->rif ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4 font-medium">{{ $prov->contacto ?? 'Sin contacto' }}</td>
                        <td class="px-6 py-4 text-[#728495]">{{ $prov->telefono ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($prov->categoria)
                                <span class="bg-[#B4C5D8]/30 text-[#263A47] px-3 py-1 rounded-full text-xs font-bold">{{ $prov->categoria }}</span>
                            @else
                                <span class="text-[#728495] italic">Sin categoría</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30 flex justify-center gap-2">
                            <button type="button" onclick="abrirModalEditar({{ $prov->id }}, '{{ $prov->nombre }}', '{{ $prov->rif }}', '{{ $prov->contacto }}', '{{ $prov->telefono }}', '{{ $prov->categoria }}')" class="text-[#4A5B6A] hover:text-[#263A47] transition-colors" title="Editar">
                                <i class="ph ph-pencil-simple text-xl"></i>
                            </button>
                            <form action="{{ route('proveedores.eliminar', $prov->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a {{ $prov->nombre }}?');">
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
                        <td colspan="5" class="px-6 py-8 text-center text-[#728495]">No hay proveedores registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($proveedores->hasPages())
            <div class="px-6 py-4 border-t border-[#B4C5D8]">
                {{ $proveedores->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL DINÁMICO (Crear / Editar) --}}
    <div id="modal-proveedor" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10">
                <h3 id="modal-titulo" class="text-lg font-bold text-[#263A47]">Registrar Proveedor</h3>
                <button onclick="cerrarModal()" class="text-[#728495] hover:text-red-500 transition"><i class="ph ph-x text-2xl"></i></button>
            </div>
            
            <form id="formulario-proveedor" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="_method" id="metodo-formulario" value="POST">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Nombre de la Empresa *</label>
                        <input type="text" name="nombre" id="input-nombre" required placeholder="Ej. Autopartes Venezuela C.A." class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">RIF</label>
                        <input type="text" name="rif" id="input-rif" placeholder="Ej. J-12345678-9" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Categoría Principal</label>
                        <select name="categoria" id="input-categoria" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47] bg-white">
                            <option value="">Seleccione...</option>
                            <option value="Repuestos Generales">Repuestos Generales</option>
                            <option value="Aceites y Químicos">Aceites y Químicos</option>
                            <option value="Herramientas">Herramientas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Contacto Principal</label>
                        <input type="text" name="contacto" id="input-contacto" placeholder="Ej. Carlos Mendoza" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Teléfono</label>
                        <input type="text" name="telefono" id="input-telefono" placeholder="Ej. 0414-1234567" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="cerrarModal()" class="px-5 py-2.5 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#B4C5D8]/20 transition">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#263A47] text-white font-semibold rounded-lg hover:bg-[#4A5B6A] shadow-md transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modal-proveedor');
        const form = document.getElementById('formulario-proveedor');
        const titulo = document.getElementById('modal-titulo');
        const metodoForm = document.getElementById('metodo-formulario');

        function cerrarModal() {
            modal.classList.add('hidden');
        }

        function abrirModalCrear() {
            titulo.innerText = "Registrar Nuevo Proveedor";
            form.action = "{{ route('proveedores.guardar') }}";
            metodoForm.value = "POST";
            
            document.getElementById('input-nombre').value = '';
            document.getElementById('input-rif').value = '';
            document.getElementById('input-contacto').value = '';
            document.getElementById('input-telefono').value = '';
            document.getElementById('input-categoria').value = '';
            
            modal.classList.remove('hidden');
        }

        function abrirModalEditar(id, nombre, rif, contacto, telefono, categoria) {
            titulo.innerText = "Editar Proveedor";
            form.action = "/proveedores/" + id; 
            metodoForm.value = "PUT";
            
            document.getElementById('input-nombre').value = nombre;
            document.getElementById('input-rif').value = rif === 'null' ? '' : rif;
            document.getElementById('input-contacto').value = contacto === 'null' ? '' : contacto;
            document.getElementById('input-telefono').value = telefono === 'null' ? '' : telefono;
            document.getElementById('input-categoria').value = categoria === 'null' ? '' : categoria;
            
            modal.classList.remove('hidden');
        }
    </script>

@endsection
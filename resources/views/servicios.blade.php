@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Catálogo de Servicios (Mano de Obra)</h2>
            <p class="text-[#728495]">Gestión de precios tabulados y variaciones por categoría de vehículo</p>
        </div>
        <button onclick="abrirModalCrear()" class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
            <i class="ph ph-plus-circle text-xl"></i> Nuevo Servicio
        </button>
    </div>

    @if(session('exito'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
            <p class="font-bold">¡Excelente!</p>
            <p>{{ session('exito') }}</p>
        </div>
    @endif

    {{-- BARRA DE BÚSQUEDA FUNCIONAL --}}
    <form method="GET" action="{{ route('servicios.index') }}" class="bg-white p-4 rounded-xl border border-[#98A9BE]/50 shadow-sm mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-1/2">
            <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-[#728495] text-xl"></i>
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre o código y presiona Enter..." class="w-full pl-10 pr-4 py-2 border border-[#B4C5D8] rounded-lg focus:outline-none focus:border-[#4A5B6A] text-[#263A47]">
        </div>
        <div class="flex gap-2">
            @if(request('buscar'))
                <a href="{{ route('servicios.index') }}" class="px-4 py-2 text-[#728495] border border-[#B4C5D8] rounded-lg hover:bg-[#B4C5D8]/20 transition">Limpiar</a>
            @endif
            <button type="submit" class="bg-[#4A5B6A] text-white px-4 py-2 rounded-lg font-semibold shadow-sm hover:bg-[#263A47] transition">Buscar</button>
        </div>
    </form>

    {{-- TABLA DINÁMICA --}}
    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden mb-6">
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
                    @forelse($servicios as $servicio)
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-medium text-[#728495]">{{ $servicio->codigo }}</td>
                        <td class="px-6 py-4 font-bold">{{ $servicio->descripcion }}</td>
                        <td class="px-6 py-4 text-center font-semibold border-l border-[#B4C5D8]/30">$ {{ number_format($servicio->precio_sencillo, 2) }}</td>
                        <td class="px-6 py-4 text-center font-semibold border-l border-[#B4C5D8]/30">$ {{ number_format($servicio->precio_alta_gama, 2) }}</td>
                        <td class="px-6 py-4 text-center font-semibold border-l border-[#B4C5D8]/30">$ {{ number_format($servicio->precio_carga_pesada, 2) }}</td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30 flex justify-center gap-2">
                            
                            {{-- BOTÓN EDITAR --}}
                            <button type="button" onclick="abrirModalEditar({{ $servicio->id }}, '{{ $servicio->codigo }}', '{{ $servicio->descripcion }}', {{ $servicio->precio_sencillo }}, {{ $servicio->precio_alta_gama }}, {{ $servicio->precio_carga_pesada }})" class="text-[#4A5B6A] hover:text-[#263A47] transition-colors" title="Editar">
                                <i class="ph ph-pencil-simple text-xl"></i>
                            </button>

                            {{-- BOTÓN ELIMINAR --}}
                            <form action="{{ route('servicios.eliminar', $servicio->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar el servicio {{ $servicio->codigo }}? Esta acción no se puede deshacer.');">
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
                        <td colspan="6" class="px-6 py-8 text-center text-[#728495] font-medium">
                            No se encontraron servicios registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- PAGINACIÓN AUTOMÁTICA DE LARAVEL --}}
        @if($servicios->hasPages())
            <div class="px-6 py-4 border-t border-[#B4C5D8]">
                {{ $servicios->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL ÚNICO (Sirve para Crear y Editar) --}}
    <div id="modal-servicio" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10">
                <h3 id="modal-titulo" class="text-lg font-bold text-[#263A47]">Registrar Nuevo Servicio</h3>
                <button onclick="cerrarModal()" class="text-[#728495] hover:text-red-500 transition">
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>
            
            <form id="formulario-servicio" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="_method" id="metodo-formulario" value="POST">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Código del Servicio *</label>
                        <input type="text" name="codigo" id="input-codigo" required placeholder="Ej. SRV-004" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Descripción del Servicio *</label>
                        <input type="text" name="descripcion" id="input-descripcion" required placeholder="Ej. Alineación y Balanceo" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    </div>
                    <div class="md:col-span-2 mt-2 border-t border-[#B4C5D8]/50 pt-5">
                        <p class="text-sm font-bold text-[#4A5B6A] uppercase tracking-wider mb-4">Tarifas por Categoría</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#728495] mb-1">Sencillo ($) *</label>
                                <input type="number" name="precio_sencillo" id="input-sencillo" step="0.01" required class="w-full border border-[#B4C5D8] rounded-lg px-3 py-2 outline-none focus:border-[#263A47]">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#728495] mb-1">Alta Gama ($) *</label>
                                <input type="number" name="precio_alta_gama" id="input-alta" step="0.01" required class="w-full border border-[#B4C5D8] rounded-lg px-3 py-2 outline-none focus:border-[#263A47]">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#728495] mb-1">Carga Pesada ($) *</label>
                                <input type="number" name="precio_carga_pesada" id="input-pesada" step="0.01" required class="w-full border border-[#B4C5D8] rounded-lg px-3 py-2 outline-none focus:border-[#263A47]">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="cerrarModal()" class="px-5 py-2.5 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#B4C5D8]/20 transition">Cancelar</button>
                    <button id="btn-guardar" type="submit" class="px-5 py-2.5 bg-[#263A47] text-white font-semibold rounded-lg hover:bg-[#4A5B6A] shadow-md transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS PARA EL MODAL DINÁMICO --}}
    <script>
        const modal = document.getElementById('modal-servicio');
        const form = document.getElementById('formulario-servicio');
        const titulo = document.getElementById('modal-titulo');
        const metodoForm = document.getElementById('metodo-formulario');

        function cerrarModal() {
            modal.classList.add('hidden');
        }

        // Modo CREAR
        function abrirModalCrear() {
            titulo.innerText = "Registrar Nuevo Servicio";
            form.action = "{{ route('servicios.guardar') }}";
            metodoForm.value = "POST";
            
            // Limpiamos los campos
            document.getElementById('input-codigo').value = '';
            document.getElementById('input-descripcion').value = '';
            document.getElementById('input-sencillo').value = '';
            document.getElementById('input-alta').value = '';
            document.getElementById('input-pesada').value = '';
            
            modal.classList.remove('hidden');
        }

        // Modo EDITAR
        function abrirModalEditar(id, codigo, descripcion, sencillo, alta, pesada) {
            titulo.innerText = "Editar Servicio: " + codigo;
            form.action = "/servicios/" + id; 
            metodoForm.value = "PUT"; // Laravel requiere PUT para actualizar
            
            // Llenamos los campos con la data actual
            document.getElementById('input-codigo').value = codigo;
            document.getElementById('input-descripcion').value = descripcion;
            document.getElementById('input-sencillo').value = sencillo;
            document.getElementById('input-alta').value = alta;
            document.getElementById('input-pesada').value = pesada;
            
            modal.classList.remove('hidden');
        }
    </script>
@endsection
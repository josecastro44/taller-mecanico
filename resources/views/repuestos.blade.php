@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Gestión de Repuestos e Insumos</h2>
            <p class="text-[#728495]">Control de inventario, compatibilidad de vehículos y márgenes operativos</p>
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
                    <p class="text-xs text-red-600">Atención: Hay artículos por debajo del mínimo</p>
                </div>
            </div>
        </div>
        <div class="bg-green-50 border border-green-200 p-4 rounded-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="ph ph-check-circle text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-green-800">Inventario Activo</p>
                    <p class="text-xs text-green-600">Total: {{ $repuestos->count() }} repuestos registrados</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl border border-[#98A9BE]/50 shadow-sm mb-6">
        <form action="{{ route('repuestos.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-96">
                <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-[#728495] text-xl"></i>
                <input
                    type="text"
                    name="buscar"
                    value="{{ $buscar ?? '' }}"
                    placeholder="Buscar por código, nombre o marca..."
                    class="w-full pl-10 pr-4 py-2 border border-[#B4C5D8] rounded-lg focus:outline-none focus:border-[#4A5B6A] text-[#263A47]"
                >
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-[#4A5B6A] text-white px-5 py-2 rounded-lg hover:bg-[#263A47] transition font-medium">
                    Buscar
                </button>
                @if($buscar)
                    <a href="{{ route('repuestos.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg hover:bg-gray-200 transition font-medium">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#B4C5D8]/20 border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Información de la Pieza</th>
                        <th class="px-6 py-4 font-bold text-center">Stock</th>
                        <th class="px-6 py-4 font-bold text-right">Costo (Compra)</th>
                        <th class="px-6 py-4 font-bold text-right">Precio Venta (PVP)</th>
                        <th class="px-6 py-4 font-bold text-center border-l border-[#B4C5D8]/50">Margen</th>
                        <th class="px-6 py-4 font-bold text-center border-l border-[#B4C5D8]/50">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#263A47]">
                    @forelse($repuestos as $repuesto)
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition {{ $repuesto->stock <= $repuesto->stock_minimo ? 'bg-red-50/50' : '' }}">
                        <td class="px-6 py-4">
                            <p class="font-bold text-base">{{ $repuesto->nombre }}</p>
                            <p class="text-xs text-[#728495]">Cod: {{ $repuesto->codigo ?? 'N/A' }} | Marca: {{ $repuesto->marca ?? 'S/M' }}</p>
                            <p class="text-[10px] text-blue-600 font-medium uppercase">{{ $repuesto->marca_vehiculo }} {{ $repuesto->modelo_vehiculo }} ({{ $repuesto->año_vehiculo }})</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="{{ $repuesto->stock <= $repuesto->stock_minimo ? 'bg-red-100 text-red-800' : 'bg-[#B4C5D8]/30 text-[#263A47]' }} px-3 py-1 rounded-full font-bold">
                                {{ $repuesto->stock }} und
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium text-red-600">$ {{ number_format($repuesto->costo_adquisicion, 2) }}</td>
                        <td class="px-6 py-4 text-right font-bold text-green-700">$ {{ number_format($repuesto->precio_venta, 2) }}</td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            @php
                                $diff = $repuesto->precio_venta - $repuesto->costo_adquisicion;
                                $margen = $repuesto->costo_adquisicion > 0 ? ($diff / $repuesto->costo_adquisicion) * 100 : 0;
                            @endphp
                            <span class="text-[#4A5B6A] font-bold">+{{ round($margen) }}%</span>
                        </td>
                        <td class="px-6 py-4 text-center border-l border-[#B4C5D8]/30">
                            <button onclick="abrirModalEditar({{ $repuesto->id }})" class="text-[#4A5B6A] hover:text-blue-600 mx-1 transition-colors" title="Editar">
                                <i class="ph ph-pencil-simple text-xl"></i>
                            </button>
                        </td>
                    </tr>

                    <div id="modal-editar-{{ $repuesto->id }}" class="hidden fixed inset-0 bg-[#0F172A]/40 z-[60] flex items-center justify-center backdrop-blur-sm p-4 transition-all duration-300">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden border border-[#E2E8F0]">

                            {{-- Cabecera del Modal Editar --}}
                            <div class="px-8 py-5 bg-[#F8FAFC] border-b border-[#E2E8F0] flex justify-between items-center">
                                <h3 class="text-xl font-extrabold text-[#1E293B] flex items-center gap-2">
                                    <i class="ph ph-pencil-line text-emerald-600"></i>
                                    Actualizar Repuesto
                                </h3>
                                <button type="button" onclick="cerrarModalEditar({{ $repuesto->id }})" class="text-[#64748B] hover:text-red-500 transition-all">
                                    <i class="ph ph-x text-2xl"></i>
                                </button>
                            </div>

                            {{-- Formulario de Edición --}}
                            <form action="{{ route('repuestos.update', $repuesto->id) }}" method="POST" class="p-8 space-y-5">
                                @csrf
                                @method('PUT')

                                {{-- Bloque 1: Identificación Principal --}}
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-[#475569] uppercase mb-1">Nombre del Producto</label>
                                        <input type="text" name="nombre" value="{{ $repuesto->nombre }}" class="w-full border border-[#CBD5E1] rounded-lg px-4 py-2 text-[#1E293B] focus:border-emerald-500 outline-none transition-all" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-[#475569] uppercase mb-1">Código SKU</label>
                                        <input type="text" name="codigo" value="{{ $repuesto->codigo }}" class="w-full border border-[#CBD5E1] rounded-lg px-4 py-2 outline-none focus:border-emerald-500 transition-all">
                                    </div>
                                </div>

                                {{-- Bloque 2: Compatibilidad de Vehículo --}}
                                <div class="bg-[#F8FAFC] p-4 rounded-xl border border-[#E2E8F0] grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-3 text-[10px] font-bold text-[#94A3B8] uppercase tracking-widest border-b border-[#E2E8F0] pb-1">Compatibilidad</div>
                                    <input type="text" name="marca_vehiculo" value="{{ $repuesto->marca_vehiculo }}" placeholder="Marca Vehículo" class="w-full bg-white border border-[#CBD5E1] rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-500">
                                    <input type="text" name="modelo_vehiculo" value="{{ $repuesto->modelo_vehiculo }}" placeholder="Modelo Vehículo" class="w-full bg-white border border-[#CBD5E1] rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-500">
                                    <input type="text" name="año_vehiculo" value="{{ $repuesto->año_vehiculo }}" placeholder="Año/Rango" class="w-full bg-white border border-[#CBD5E1] rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-500">
                                </div>

                                {{-- Bloque 3: Datos de Marca y Categoría --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-[#475569] uppercase mb-1">Marca del Repuesto</label>
                                        <input type="text" name="marca" value="{{ $repuesto->marca }}" class="w-full border border-[#CBD5E1] rounded-lg px-4 py-2 outline-none focus:border-emerald-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-[#475569] uppercase mb-1">Categoría</label>
                                        <select name="categoria" class="w-full border border-[#CBD5E1] rounded-lg px-4 py-2 bg-white outline-none focus:border-emerald-500 transition-all">
                                            <option value="Lubricantes" {{ $repuesto->categoria == 'Lubricantes' ? 'selected' : '' }}>Lubricantes</option>
                                            <option value="Frenos" {{ $repuesto->categoria == 'Frenos' ? 'selected' : '' }}>Frenos</option>
                                            <option value="Motor" {{ $repuesto->categoria == 'Motor' ? 'selected' : '' }}>Motor</option>
                                            <option value="Suspensión" {{ $repuesto->categoria == 'Suspensión' ? 'selected' : '' }}>Suspensión</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Bloque 4: Precios y Stock --}}
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-2">
                                    <div class="col-span-1">
                                        <label class="block text-xs font-bold text-red-500 uppercase mb-1">Costo ($)</label>
                                        <input type="number" step="0.01" name="costo_adquisicion" value="{{ $repuesto->costo_adquisicion }}" class="w-full border border-red-100 bg-red-50/30 rounded-lg px-3 py-2 font-bold text-red-600 outline-none focus:border-red-400" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-xs font-bold text-green-600 uppercase mb-1">Venta ($)</label>
                                        <input type="number" step="0.01" name="precio_venta" value="{{ $repuesto->precio_venta }}" class="w-full border border-green-100 bg-green-50/30 rounded-lg px-3 py-2 font-bold text-green-700 outline-none focus:border-green-400" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-xs font-bold text-[#475569] uppercase mb-1">Stock Act.</label>
                                        <input type="number" name="stock" value="{{ $repuesto->stock }}" class="w-full border border-[#CBD5E1] rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-xs font-bold text-[#475569] uppercase mb-1">Stock Mín.</label>
                                        <input type="number" name="stock_minimo" value="{{ $repuesto->stock_minimo }}" class="w-full border border-[#CBD5E1] rounded-lg px-3 py-2 outline-none focus:border-emerald-500" required>
                                    </div>
                                </div>

                                {{-- Botonera Modal Editar --}}
                                <div class="flex justify-end gap-3 pt-4 border-t border-[#E2E8F0]">
                                    <button type="button" onclick="cerrarModalEditar({{ $repuesto->id }})" class="px-6 py-2 text-[#64748B] font-bold hover:text-red-500 transition-all">Cancelar</button>
                                    <button type="submit" class="bg-[#1E293B] text-white px-8 py-2.5 rounded-xl font-bold shadow-lg hover:bg-[#334155] transition-all">Actualizar Registro</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-[#728495]">No se encontraron repuestos con ese criterio.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="modal-repuesto" class="hidden fixed inset-0 bg-black/60 z-[60] flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden transform transition-all border border-[#B4C5D8]">

            {{-- Cabecera del Modal Registrar --}}
            <div class="px-8 py-5 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10">
                <h3 class="text-xl font-bold text-[#263A47] flex items-center gap-2">
                    <i class="ph ph-plus-circle text-blue-600"></i>
                    Nuevo Ingreso a Inventario
                </h3>
                <button onclick="cerrarModalRepuesto()" class="text-[#728495] hover:text-red-500 transition">
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>

            {{-- Formulario de Registro --}}
            <form action="{{ route('repuestos.store') }}" method="POST" class="p-8 space-y-5">
                @csrf

                {{-- Bloque 1: Identificación Principal --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-[#4A5B6A] uppercase mb-1">Nombre del Repuesto *</label>
                        <input type="text" name="nombre" placeholder="Ej. Pastillas de Freno" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47] transition-all" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] uppercase mb-1">Código SKU</label>
                            <input type="text" name="codigo" placeholder="***"
                                class="w-full border border-[#B4C5D8] bg-gray-50 rounded-lg px-4 py-2 outline-none text-gray-500 italic" readonly>
                    </div>
                </div>

                {{-- Bloque 2: Compatibilidad de Vehículo --}}
                <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-3 text-[10px] font-bold text-blue-400 uppercase tracking-widest border-b border-blue-100 pb-1">Compatibilidad de Vehículo</div>
                    <input type="text" name="marca_vehiculo" placeholder="Marca (Ej: Toyota)" class="w-full bg-white border border-[#B4C5D8] rounded-lg px-3 py-2 text-sm outline-none focus:border-[#263A47]">
                    <input type="text" name="modelo_vehiculo" placeholder="Modelo (Ej: Corolla)" class="w-full bg-white border border-[#B4C5D8] rounded-lg px-3 py-2 text-sm outline-none focus:border-[#263A47]">
                    <input type="text" name="año_vehiculo" placeholder="Año (Ej: 2010)" class="w-full bg-white border border-[#B4C5D8] rounded-lg px-3 py-2 text-sm outline-none focus:border-[#263A47]">
                </div>

                {{-- Bloque 3: Datos de Marca y Categoría --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] uppercase mb-1">Marca del Repuesto</label>
                        <input type="text" name="marca" placeholder="Ej. Bosch, Federal" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] uppercase mb-1">Categoría</label>
                        <select name="categoria" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 bg-white outline-none focus:border-[#263A47]">
                            <option value="Lubricantes">Lubricantes</option>
                            <option value="Frenos">Frenos</option>
                            <option value="Motor">Motor</option>
                            <option value="Suspensión">Suspensión</option>
                        </select>
                    </div>
                </div>

                {{-- Bloque 4: Precios y Stock --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-red-500 uppercase mb-1">Costo Compra ($)</label>
                        <input type="number" step="0.01" name="costo_adquisicion" placeholder="0.00" class="w-full border border-[#B4C5D8] rounded-lg px-3 py-2 outline-none focus:border-[#263A47]" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-green-600 uppercase mb-1">Precio Venta ($)</label>
                        <input type="number" step="0.01" name="precio_venta" placeholder="0.00" class="w-full border border-green-200 rounded-lg px-3 py-2 font-bold text-green-700 outline-none focus:border-green-600" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] uppercase mb-1">Stock Inicial</label>
                        <input type="number" name="stock" value="0" class="w-full border border-[#B4C5D8] rounded-lg px-3 py-2 outline-none focus:border-[#263A47]" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#4A5B6A] uppercase mb-1">Stock Mínimo</label>
                        <input type="number" name="stock_minimo" value="5" class="w-full border border-[#B4C5D8] rounded-lg px-3 py-2 outline-none focus:border-[#263A47]" required>
                    </div>
                </div>

                {{-- Botonera Modal Registrar --}}
                <div class="mt-8 flex justify-end gap-3 border-t border-[#B4C5D8]/50 pt-5">
                    <button type="button" onclick="cerrarModalRepuesto()" class="px-6 py-2 text-[#728495] font-bold hover:text-red-500 transition">Cerrar</button>
                    <button type="submit" class="px-8 py-2.5 bg-[#263A47] text-white font-bold rounded-xl hover:bg-[#4A5B6A] shadow-lg transition-all">Guardar en Inventario</button>
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
        function abrirModalEditar(id) {
            const modal = document.getElementById('modal-editar-' + id);
            if(modal) modal.classList.remove('hidden');
        }
        function cerrarModalEditar(id) {
            const modal = document.getElementById('modal-editar-' + id);
            if(modal) modal.classList.add('hidden');
        }
    </script>

@endsection

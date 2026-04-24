@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Mi Área de Trabajo</h2>
            <p class="text-[#728495]">Bienvenido, <span class="font-bold text-[#4A5B6A] capitalize">{{ Auth::user()->name }}</span>.</p>
        </div>
        <button class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 shadow-md transition-all font-medium flex items-center gap-2">
            <i class="ph ph-clock text-xl"></i> Marcar Entrada (Reloj)
        </button>
    </div>

    @if(session('exito'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
            <p class="font-bold">¡Excelente!</p>
            <p>{{ session('exito') }}</p>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm">
            <p class="font-bold">Error:</p>
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    {{-- TARJETAS DE RESUMEN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center gap-4 border-l-4 border-l-blue-500">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <i class="ph ph-clipboard-text text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-[#728495] font-medium">Asignados (Cola)</p>
                <p class="text-2xl font-bold text-[#263A47]">{{ $pendientes->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center gap-4 border-l-4 border-l-amber-500">
            <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                <i class="ph ph-wrench text-2xl animate-pulse"></i>
            </div>
            <div>
                <p class="text-sm text-[#728495] font-medium">Trabajando Ahora</p>
                <p class="text-2xl font-bold text-[#263A47]">{{ $enProceso->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center gap-4 border-l-4 border-l-green-500">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                <i class="ph ph-check-circle text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-[#728495] font-medium">Terminados Hoy</p>
                <p class="text-2xl font-bold text-[#263A47]">{{ $terminadosHoy }}</p>
            </div>
        </div>
    </div>

    <h3 class="text-lg font-bold text-[#263A47] mb-4 border-b border-[#B4C5D8] pb-2">Vehículos en mi Estación</h3>
    
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- ÓRDENES EN PROCESO (Color Ambar) --}}
        @foreach($enProceso as $orden)
        <div class="bg-white rounded-xl border-2 border-amber-400 shadow-md p-0 overflow-hidden relative transform transition hover:-translate-y-1 hover:shadow-lg">
            <div class="bg-amber-50 px-5 py-3 border-b border-amber-200 flex justify-between items-center">
                <span class="bg-amber-500 text-white text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-white animate-ping"></span> En Reparación
                </span>
                <span class="text-sm font-bold text-amber-700"><i class="ph ph-hash"></i> O.S. 00{{ $orden->id }}</span>
            </div>
            
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="font-black text-[#263A47] text-2xl">{{ $orden->vehiculo->marca }} {{ $orden->vehiculo->modelo }}</h4>
                        <div class="inline-block bg-[#F3F6F8] border border-[#B4C5D8] px-3 py-1 rounded text-lg font-mono font-bold text-[#4A5B6A] mt-2 tracking-widest">
                            {{ $orden->vehiculo->placa }}
                        </div>
                    </div>
                </div>
                
                <div class="text-sm text-[#4A5B6A] bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6 italic">
                    <i class="ph ph-chat-circle-text text-[#98A9BE] mr-1 text-lg"></i>
                    "{{ $orden->diagnostico }}"
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <button onclick="abrirModalInsumos({{ $orden->id }})" class="flex-1 bg-white border-2 border-[#263A47] text-[#263A47] py-3 rounded-lg font-bold hover:bg-gray-50 transition flex justify-center items-center gap-2 shadow-sm">
                        <i class="ph ph-nut text-xl"></i> Pedir Insumos
                    </button>
                    <form action="{{ route('mecanico.estado', ['id' => $orden->id, 'estado' => 'Finalizado']) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full h-full bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700 transition flex justify-center items-center gap-2 shadow-sm" onclick="return confirm('¿Seguro que terminaste de reparar este vehículo?');">
                            <i class="ph ph-check-fat text-xl"></i> Finalizar Trabajo
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        {{-- ÓRDENES EN ESPERA (Color Gris) --}}
        @foreach($pendientes as $orden)
        <div class="bg-white rounded-xl border border-[#B4C5D8] shadow-sm p-0 overflow-hidden opacity-90 hover:opacity-100 transition">
            <div class="bg-[#F3F6F8] px-5 py-3 border-b border-[#B4C5D8] flex justify-between items-center">
                <span class="bg-[#728495] text-white text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider">
                    Siguiente en Cola
                </span>
                <span class="text-sm font-bold text-[#728495]"><i class="ph ph-hash"></i> O.S. 00{{ $orden->id }}</span>
            </div>
            
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="font-bold text-[#263A47] text-xl">{{ $orden->vehiculo->marca }} {{ $orden->vehiculo->modelo }}</h4>
                        <div class="inline-block bg-[#F3F6F8] border border-[#B4C5D8] px-2 py-0.5 rounded text-sm font-mono font-bold text-[#4A5B6A] mt-2">
                            {{ $orden->vehiculo->placa }}
                        </div>
                    </div>
                </div>
                
                <div class="text-sm text-[#4A5B6A] bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6 italic">
                    <i class="ph ph-chat-circle-text text-[#98A9BE] mr-1 text-lg"></i>
                    "{{ $orden->diagnostico }}"
                </div>
                
{{-- Dentro del foreach de pendientes --}}
<form action="{{ route('mecanico.estado', ['id' => $orden->id, 'estado' => 'En Reparación']) }}" method="POST">
    @csrf
    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition flex justify-center items-center gap-2 shadow-sm">
        <i class="ph ph-play-circle text-xl"></i> Iniciar Reparación
    </button>
</form>
            </div>
        </div>
        @endforeach

    </div>

    {{-- MODAL PEDIR INSUMOS --}}
    <div id="modal-insumos" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-[#B4C5D8] flex justify-between items-center bg-[#B4C5D8]/10">
                <h3 class="text-lg font-bold text-[#263A47]">Agregar Repuesto al Vehículo</h3>
                <button onclick="cerrarModalInsumos()" class="text-[#728495] hover:text-red-500"><i class="ph ph-x text-2xl"></i></button>
            </div>
            <form id="form-insumos" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Repuesto del Inventario *</label>
                        <select name="repuesto_id" required class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                            <option value="">Seleccione qué repuesto usó...</option>
                            @foreach($repuestos as $repuesto)
                                <option value="{{ $repuesto->id }}">{{ $repuesto->nombre }} (Stock: {{ $repuesto->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Cantidad Usada *</label>
                        <input type="number" name="cantidad" required min="1" value="1" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2 outline-none focus:border-[#263A47]">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="cerrarModalInsumos()" class="px-5 py-2 border border-[#B4C5D8] text-[#728495] font-semibold rounded-lg hover:bg-[#B4C5D8]/20">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-amber-500 text-white font-bold rounded-lg hover:bg-amber-600">Descontar e Instalar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalInsumos(ordenId) { 
            document.getElementById('form-insumos').action = `/mecanico/orden/${ordenId}/repuesto`;
            document.getElementById('modal-insumos').classList.remove('hidden'); 
        }
        function cerrarModalInsumos() { 
            document.getElementById('modal-insumos').classList.add('hidden'); 
        }
    </script>
@endsection
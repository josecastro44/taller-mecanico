@extends('layouts.app')

@section('contenido')
    
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Recepción y Órdenes de Servicio</h2>
            <p class="text-[#728495]">Registro de Hoja de Vida y generación de diagnóstico inicial</p>
        </div>
    </div>

    {{-- Cuadro verde que aparece si todo se guarda con éxito --}}
    @if(session('exito'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
            <p class="font-bold">¡Excelente!</p>
            <p>{{ session('exito') }}</p>
        </div>
    @endif

    {{-- Corregimos el action para que apunte a la ruta correcta --}}
    <form action="/recepcion" method="POST" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        {{-- Escudo de seguridad --}}
        @csrf

        <div class="xl:col-span-2 bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm p-6">
            <h3 class="text-lg font-bold text-[#263A47] border-b border-[#B4C5D8] pb-3 mb-5">1. Datos de Ingreso</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <div class="md:col-span-2 text-sm font-bold text-[#4A5B6A] uppercase tracking-wider mt-2 border-b-2 border-[#B4C5D8]/30 pb-1">
                    Información del Cliente
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Cédula de Identidad (CI) *</label>
                    {{-- Cambiamos 'ci' por 'cedula' para que coincida con el controlador --}}
                    <input type="number" name="cedula" value="{{ old('cedula') }}" placeholder="Ej. 12345678" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    @error('cedula') <span class="text-red-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Nombre Completo *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej. Juan Pérez" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    @error('nombre') <span class="text-red-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- NUEVO CAMPO: Teléfono --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Número de Teléfono *</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}" placeholder="Ej. 0414-1234567" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    @error('telefono') <span class="text-red-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2 text-sm font-bold text-[#4A5B6A] uppercase tracking-wider mt-4 border-b-2 border-[#B4C5D8]/30 pb-1">
                    Información del Vehículo
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Placa *</label>
                    <input type="text" name="placa" value="{{ old('placa') }}" placeholder="Ej. ABC-1234" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    @error('placa') <span class="text-red-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Marca *</label>
                    <input type="text" name="marca" value="{{ old('marca') }}" placeholder="Ej. Toyota" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    @error('marca') <span class="text-red-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Modelo *</label>
                    <input type="text" name="modelo" value="{{ old('modelo') }}" placeholder="Ej. Corolla" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                    @error('modelo') <span class="text-red-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Kilometraje</label>
                    <input type="number" name="kilometraje" value="{{ old('kilometraje') }}" placeholder="Ej. 150000" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                </div>

                <div class="md:col-span-2 mt-4">
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Diagnóstico Inicial *</label>
                    <textarea name="diagnostico" rows="3" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-3 outline-none focus:border-[#263A47]">{{ old('diagnostico') }}</textarea>
                    @error('diagnostico') <span class="text-red-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

<div class="bg-[#B4C5D8]/10 rounded-xl border border-[#98A9BE]/50 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold text-[#263A47] border-b border-[#B4C5D8] pb-3 mb-4">2. Asignación y Servicios</h3>
                
                {{-- NUEVO: Selección de Servicios --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-2">Servicios a Realizar *</label>
                    <div class="max-h-40 overflow-y-auto bg-white border border-[#B4C5D8] rounded-lg p-3 space-y-2">
                        @foreach($servicios as $servicio)
                            <label class="flex items-center gap-2 cursor-pointer hover:bg-[#B4C5D8]/10 p-1 rounded transition">
                                <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}" class="w-4 h-4 text-[#263A47] border-[#B4C5D8] rounded focus:ring-[#263A47]">
                                <span class="text-sm font-medium text-[#263A47]">{{ $servicio->codigo }} - {{ $servicio->descripcion }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('servicios') <span class="text-red-500 text-xs font-semibold mt-1 block">Debe seleccionar al menos un servicio.</span> @enderror
                </div>

                {{-- CORREGIDO: Mecánico Real desde Empleados --}}
                <div class="mb-2">
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Mecánico Principal</label>
                    <select name="mecanico_id" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 text-[#263A47] bg-white outline-none focus:border-[#263A47]">
                        <option value="">Pendiente de asignar...</option>
                        @foreach($mecanicos as $mecanico)
                            <option value="{{ $mecanico->id }}" {{ old('mecanico_id') == $mecanico->id ? 'selected' : '' }}>
                                {{ $mecanico->nombre }} ({{ $mecanico->especialidad }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="pt-5 mt-5 border-t border-[#B4C5D8]">
                <button type="submit" class="w-full bg-[#263A47] text-white font-bold py-3.5 rounded-lg shadow-md hover:bg-[#4A5B6A] transition-all">
                    Generar Orden de Servicio
                </button>
            </div>
        </div>
        
    </form>



@endsection
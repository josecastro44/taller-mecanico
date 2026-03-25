@extends('layouts.app')

@section('contenido')
    
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Recepción y Órdenes de Servicio</h2>
            <p class="text-[#728495]">Registro de Hoja de Vida y generación de diagnóstico inicial</p>
        </div>
    </div>

    <form action="/guardar-recepcion" method="POST" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        {{-- @csrf es el escudo de seguridad obligatorio de Laravel para formularios --}}
        @csrf

        <div class="xl:col-span-2 bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm p-6">
            <h3 class="text-lg font-bold text-[#263A47] border-b border-[#B4C5D8] pb-3 mb-5">1. Datos de Ingreso</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <div class="md:col-span-2 text-sm font-bold text-[#4A5B6A] uppercase tracking-wider mt-2 border-b-2 border-[#B4C5D8]/30 pb-1">
                    Información del Cliente
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Cédula de Identidad (CI) *</label>
                    <input type="number" name="ci" placeholder="Ej. V-12345678" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Nombre Completo *</label>
                    <input type="text"  name="nombre"  pattern="[A-Za-z\s]+" placeholder="Ej. Juan Pérez" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                </div>

                <div class="md:col-span-2 text-sm font-bold text-[#4A5B6A] uppercase tracking-wider mt-4 border-b-2 border-[#B4C5D8]/30 pb-1">
                    Información del Vehículo
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Placa *</label>
                    <input type="text" name="placa" placeholder="Ej. ABC-1234" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Marca *</label>
                    <input type="text" name="marca" placeholder="Ej. Toyota" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Modelo *</label>
                    <input type="text" name="modelo" placeholder="Ej. Corolla" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Kilometraje</label>
                    <input type="number" name="kilometraje" placeholder="Ej. 150000" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                </div>

                <div class="md:col-span-2 mt-4">
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Diagnóstico Inicial</label>
                    <textarea name="diagnostico" rows="3" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-3 outline-none focus:border-[#263A47]"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-[#B4C5D8]/10 rounded-xl border border-[#98A9BE]/50 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold text-[#263A47] border-b border-[#B4C5D8] pb-3 mb-5">2. Asignación</h3>
                <div class="mb-2">
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Mecánico Principal</label>
                    <select name="mecanico" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 text-[#263A47] bg-white outline-none focus:border-[#263A47]">
                        <option value="">Pendiente...</option>
                        <option value="Roberto Carlos">Roberto Carlos (Motor)</option>
                        <option value="Miguel Gomez">Miguel Gómez (Frenos)</option>
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
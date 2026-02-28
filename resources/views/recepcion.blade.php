@extends('layouts.app')

@section('contenido')
    
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Recepción y Órdenes de Servicio</h2>
            <p class="text-[#728495]">Registro de Hoja de Vida y generación de diagnóstico inicial</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm p-6">
            <h3 class="text-lg font-bold text-[#263A47] border-b border-[#B4C5D8] pb-3 mb-5">1. Datos del Vehículo</h3>
            
            <form class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Placa *</label>
                    <input type="text" placeholder="Ej. ABC-1234" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Cliente</label>
                    <input type="text" placeholder="Nombre completo" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 outline-none focus:border-[#263A47]">
                </div>
                <div class="md:col-span-2 mt-2">
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Diagnóstico Inicial</label>
                    <textarea rows="3" class="w-full border border-[#B4C5D8] rounded-lg px-4 py-3 outline-none focus:border-[#263A47]"></textarea>
                </div>
            </form>
        </div>

        <div class="bg-[#B4C5D8]/10 rounded-xl border border-[#98A9BE]/50 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold text-[#263A47] border-b border-[#B4C5D8] pb-3 mb-5">2. Asignación</h3>
                <div class="mb-2">
                    <label class="block text-sm font-semibold text-[#4A5B6A] mb-1">Mecánico Principal</label>
                    <select class="w-full border border-[#B4C5D8] rounded-lg px-4 py-2.5 text-[#263A47] bg-white outline-none focus:border-[#263A47]">
                        <option>Pendiente...</option>
                    </select>
                </div>
            </div>
            <div class="pt-5 mt-5 border-t border-[#B4C5D8]">
                <button class="w-full bg-[#263A47] text-white font-bold py-3.5 rounded-lg shadow-md hover:bg-[#4A5B6A] transition-all">
                    Generar Orden de Servicio
                </button>
            </div>
        </div>
    </div>

@endsection
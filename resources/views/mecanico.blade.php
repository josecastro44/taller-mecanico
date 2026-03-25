@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Mi Área de Trabajo</h2>
            <p class="text-[#728495]">Bienvenido, <span class="font-bold text-[#4A5B6A] capitalize">{{ Auth::user()->name }}</span>. Aquí tienes tus órdenes asignadas para hoy.</p>
        </div>
        <button class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 shadow-md transition-all font-medium flex items-center gap-2">
            <i class="ph ph-clock text-xl"></i> Marcar Entrada (Reloj)
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center gap-4 border-l-4 border-l-blue-500">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <i class="ph ph-clipboard-text text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-[#728495] font-medium">Asignados (Cola)</p>
                <p class="text-2xl font-bold text-[#263A47]">3</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center gap-4 border-l-4 border-l-amber-500">
            <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                <i class="ph ph-wrench text-2xl animate-pulse"></i>
            </div>
            <div>
                <p class="text-sm text-[#728495] font-medium">Trabajando Ahora</p>
                <p class="text-2xl font-bold text-[#263A47]">1</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm flex items-center gap-4 border-l-4 border-l-green-500">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                <i class="ph ph-check-circle text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-[#728495] font-medium">Terminados Hoy</p>
                <p class="text-2xl font-bold text-[#263A47]">2</p>
            </div>
        </div>
    </div>

    <h3 class="text-lg font-bold text-[#263A47] mb-4 border-b border-[#B4C5D8] pb-2">Vehículos en mi Estación</h3>
    
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <div class="bg-white rounded-xl border-2 border-amber-400 shadow-md p-0 overflow-hidden relative transform transition hover:-translate-y-1 hover:shadow-lg">
            
            <div class="bg-amber-50 px-5 py-3 border-b border-amber-200 flex justify-between items-center">
                <span class="bg-amber-500 text-white text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-white animate-ping"></span> En Reparación
                </span>
                <span class="text-sm font-bold text-amber-700"><i class="ph ph-hash"></i> O.S. 0054</span>
            </div>
            
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="font-black text-[#263A47] text-2xl">Toyota Corolla 2018</h4>
                        <div class="inline-block bg-[#F3F6F8] border border-[#B4C5D8] px-3 py-1 rounded text-lg font-mono font-bold text-[#4A5B6A] mt-2 tracking-widest">
                            XYZ-123
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-[#98A9BE] uppercase font-bold">Servicio Principal</p>
                        <p class="text-sm font-bold text-[#4A5B6A] bg-blue-50 px-3 py-1 rounded text-blue-700 mt-1">Cambio Empacadura</p>
                    </div>
                </div>
                
                <div class="text-sm text-[#4A5B6A] bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6 italic">
                    <i class="ph ph-chat-circle-text text-[#98A9BE] mr-1 text-lg"></i>
                    "El cliente reporta goteo de aceite en el piso de su garaje. Se requiere lavado a presión del motor y reemplazo."
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <button class="flex-1 bg-white border-2 border-[#263A47] text-[#263A47] py-3 rounded-lg font-bold hover:bg-gray-50 transition flex justify-center items-center gap-2 shadow-sm">
                        <i class="ph ph-nut text-xl"></i> Pedir Insumos
                    </button>
                    <button class="flex-1 bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700 transition flex justify-center items-center gap-2 shadow-sm">
                        <i class="ph ph-check-fat text-xl"></i> Finalizar Trabajo
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#B4C5D8] shadow-sm p-0 overflow-hidden opacity-90 hover:opacity-100 transition">
            
            <div class="bg-[#F3F6F8] px-5 py-3 border-b border-[#B4C5D8] flex justify-between items-center">
                <span class="bg-[#728495] text-white text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider">
                    Siguiente en Cola
                </span>
                <span class="text-sm font-bold text-[#728495]"><i class="ph ph-hash"></i> O.S. 0055</span>
            </div>
            
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="font-bold text-[#263A47] text-xl">Ford Fiesta 2015</h4>
                        <div class="inline-block bg-[#F3F6F8] border border-[#B4C5D8] px-2 py-0.5 rounded text-sm font-mono font-bold text-[#4A5B6A] mt-2">
                            ABC-987
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-[#98A9BE] uppercase font-bold">Servicio Principal</p>
                        <p class="text-sm font-bold text-[#4A5B6A] mt-1">Aceite y Filtros</p>
                    </div>
                </div>
                
                <div class="text-sm text-[#4A5B6A] bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6 italic">
                    <i class="ph ph-chat-circle-text text-[#98A9BE] mr-1 text-lg"></i>
                    "Mantenimiento rutinario. Aceite semisintético 15W-40."
                </div>
                
                <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition flex justify-center items-center gap-2 shadow-sm">
                    <i class="ph ph-play-circle text-xl"></i> Iniciar Reparación
                </button>
            </div>
        </div>

    </div>

@endsection
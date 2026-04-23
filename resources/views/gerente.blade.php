@extends('layouts.app')

@section('contenido')
<div class="w-full">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-[#263A47]">Visión General del Negocio</h1>
            <p class="text-gray-500 mt-1">Hola, <span class="font-bold">{{ Auth::user()->name }}</span>. Aquí tienes el resumen de AutoSys hoy.</p>
        </div>
        <button class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold flex items-center gap-2 shadow-sm hover:bg-green-700 transition">
            <i class="ph ph-file-pdf text-xl"></i> Exportar Reporte
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-blue-500">
            <p class="text-sm text-[#728495] font-bold uppercase">Ingresos del Mes</p>
            <p class="text-3xl font-black text-[#263A47] mt-1">$4,250</p>
            <p class="text-xs text-green-600 mt-2 font-bold"><i class="ph ph-trend-up"></i> +12% vs mes anterior</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-purple-500">
            <p class="text-sm text-[#728495] font-bold uppercase">Órdenes Activas</p>
            <p class="text-3xl font-black text-[#263A47] mt-1">8</p>
            <p class="text-xs text-[#728495] mt-2">En el taller ahora mismo</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-amber-500">
            <p class="text-sm text-[#728495] font-bold uppercase">Solicitudes Repuestos</p>
            <p class="text-3xl font-black text-amber-600 mt-1">2</p>
            <p class="text-xs text-amber-600 mt-2 font-bold animate-pulse">Requieren tu aprobación</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-[#4A5B6A]">
            <p class="text-sm text-[#728495] font-bold uppercase">Empleados Activos</p>
            <p class="text-3xl font-black text-[#263A47] mt-1">5</p>
            <p class="text-xs text-[#728495] mt-2">Nómina al día</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-amber-200 shadow-sm overflow-hidden mb-8">
        <div class="bg-amber-50 p-4 border-b border-amber-200 flex justify-between items-center">
            <h3 class="font-bold text-amber-800 flex items-center gap-2"><i class="ph ph-warning-circle text-xl"></i> Repuestos Solicitados por Mecánicos (Pendientes)</h3>
            <a href="#" class="text-sm text-amber-700 font-bold hover:underline">Ver todas las solicitudes</a>
        </div>
        <div class="p-0">
            <table class="w-full text-left border-collapse">
                <tr class="border-b border-gray-100">
                    <td class="p-4"><p class="font-bold text-[#263A47]">Chema (Mecánico)</p><p class="text-xs text-gray-500">O.S. #0055</p></td>
                    <td class="p-4 font-bold text-[#4A5B6A]">Aceite Semisintético 15W-40 (x4 Lts)</td>
                    <td class="p-4 text-right">
                        <button class="bg-green-100 text-green-700 px-3 py-1.5 rounded font-bold text-xs mr-2 hover:bg-green-200">Aprobar (Hay Stock)</button>
                        <button class="bg-red-100 text-red-700 px-3 py-1.5 rounded font-bold text-xs hover:bg-red-200">Rechazar</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
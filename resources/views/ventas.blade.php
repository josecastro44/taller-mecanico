@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Gestión de Ventas (Mostrador)</h2>
            <p class="text-[#728495]">Registro de ventas directas de repuestos e insumos al detal</p>
        </div>
        <button id="btnNuevaVenta" type="button" class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
            <i class="ph ph-shopping-cart text-xl"></i>
            Nueva Venta
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-l-4 border-l-green-500 flex items-center justify-between">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Ventas del Día</p>
                <p class="text-3xl font-bold text-[#263A47]">$ 320.00</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                <i class="ph ph-currency-dollar text-2xl text-green-500"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-l-4 border-l-[#4A5B6A] flex items-center justify-between">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Artículos Vendidos</p>
                <p class="text-3xl font-bold text-[#263A47]">18</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-[#B4C5D8]/30 flex items-center justify-center">
                <i class="ph ph-package text-2xl text-[#4A5B6A]"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-l-4 border-l-[#728495] flex items-center justify-between">
            <div>
                <p class="text-sm text-[#728495] font-medium mb-1">Ticket Promedio</p>
                <p class="text-3xl font-bold text-[#263A47]">$ 17.77</p>
            </div>
            <div class="w-12 h-12 rounded-lg bg-[#B4C5D8]/30 flex items-center justify-center">
                <i class="ph ph-receipt text-2xl text-[#4A5B6A]"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-[#B4C5D8] bg-[#B4C5D8]/10 flex justify-between items-center">
            <h3 class="text-lg font-bold text-[#263A47]">Últimas Ventas Realizadas</h3>
            <div class="relative w-64">
                <i class="ph ph-magnifying-glass absolute left-3 top-2 text-[#728495]"></i>
                <input type="text" placeholder="Buscar ticket..." class="w-full pl-9 pr-4 py-1.5 border border-[#B4C5D8] rounded-md text-sm focus:outline-none focus:border-[#4A5B6A]">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">N° Ticket</th>
                        <th class="px-6 py-4 font-bold">Cliente</th>
                        <th class="px-6 py-4 font-bold text-center">Artículos</th>
                        <th class="px-6 py-4 font-bold text-right">Total</th>
                        <th class="px-6 py-4 font-bold text-center">Método</th>
                        <th class="px-6 py-4 font-bold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="ventasTbody" class="text-sm text-[#263A47]">
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-bold">VT-1045</td>
                        <td class="px-6 py-4">Cliente Mostrador</td>
                        <td class="px-6 py-4 text-center">2</td>
                        <td class="px-6 py-4 text-right font-bold text-green-700">$ 45.00</td>
                        <td class="px-6 py-4 text-center"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">Punto / Tarjeta</span></td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Ver Detalle"><i class="ph ph-eye text-xl"></i></button>
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Imprimir"><i class="ph ph-printer text-xl"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Nueva Venta -->
    <div id="nuevaVentaModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div id="modalBackdrop" class="absolute inset-0 bg-black/50"></div>
        <div class="relative bg-white rounded-lg shadow-xl w-[95%] max-w-2xl mx-4 z-10 overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center">
                <h4 class="font-bold text-lg">Nueva Venta</h4>
                <button id="closeModal" class="text-[#728495] hover:text-[#263A47]">Cerrar ✕</button>
            </div>
            <div id="modalErrorBanner" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-2">
                <strong class="font-medium">Error:</strong>
                <span id="modalErrorMessage" class="ml-2">Mensaje de error</span>
            </div>
            <form id="formNuevaVenta" class="p-4 space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm text-[#728495]">N° Ticket</label>
                        <input id="ticket" name="ticket" type="text" required class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none">
                        <span data-error-for="ticket" class="text-red-600 text-sm mt-1 hidden"></span>
                    </div>
                    <div>
                        <label class="text-sm text-[#728495]">Cliente</label>
                        <input id="cliente" name="cliente" type="text" required class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none">
                        <span data-error-for="cliente" class="text-red-600 text-sm mt-1 hidden"></span>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm text-[#728495]">Artículo</label>
                        <input id="articulo" name="articulo" type="text" class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none">
                        <span data-error-for="articulo" class="text-red-600 text-sm mt-1 hidden"></span>
                    </div>
                    <div>
                        <label class="text-sm text-[#728495]">Total</label>
                        <input id="total" name="total" type="number" step="0.01" class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none">
                        <span data-error-for="total" class="text-red-600 text-sm mt-1 hidden"></span>
                    </div>
                    <div>
                        <label class="text-sm text-[#728495]">Método</label>
                        <select id="metodo" name="metodo" class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none">
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                        <span data-error-for="metodo" class="text-red-600 text-sm mt-1 hidden"></span>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm text-[#728495]">Acciones / Observaciones</label>
                        <textarea id="acciones" name="acciones" rows="3" class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none"></textarea>
                        <span data-error-for="acciones" class="text-red-600 text-sm mt-1 hidden"></span>
                    </div>
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t">
                    <button type="button" id="cancelBtn" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
                    <button type="submit" class="px-4 py-2 rounded bg-[#263A47] text-white">Guardar Venta</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/ventas.js') }}"></script>

@endsection

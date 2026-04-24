@extends('layouts.app')

@section('contenido')

    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#263A47]">Órdenes de Compra (Reabastecimiento)</h2>
            <p class="text-[#728495]">Ingreso de mercancía e insumos al inventario del taller</p>
        </div>
        <button id="btnNuevaCompra" type="button" class="bg-[#263A47] text-white px-5 py-2.5 rounded-lg hover:bg-[#4A5B6A] shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2 font-medium">
            <i class="ph ph-bag text-xl"></i>
            Nueva Compra
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-[#263A47]">
            <p class="text-sm text-[#728495] font-medium mb-1">Inversión del Mes</p>
            <p class="text-3xl font-bold text-[#263A47]">$ 1,850.00</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-yellow-500">
            <p class="text-sm text-[#728495] font-medium mb-1">Pedidos en Tránsito</p>
            <p class="text-3xl font-bold text-yellow-600">2</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-[#98A9BE]/50 shadow-sm border-t-4 border-t-red-500">
            <p class="text-sm text-[#728495] font-medium mb-1">Cuentas por Pagar</p>
            <p class="text-3xl font-bold text-red-600">$ 450.00</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#98A9BE]/50 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#B4C5D8]/20 border-b-2 border-[#B4C5D8] text-[#4A5B6A] text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">N° Orden</th>
                        <th class="px-6 py-4 font-bold">Proveedor</th>
                        <th class="px-6 py-4 font-bold">Fecha</th>
                        <th class="px-6 py-4 font-bold text-right">Monto Total</th>
                        <th class="px-6 py-4 font-bold text-center">Estado</th>
                        <th class="px-6 py-4 font-bold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="comprasTbody" class="text-sm text-[#263A47]">
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-bold">OC-2026-089</td>
                        <td class="px-6 py-4">Autopartes Venezuela C.A.</td>
                        <td class="px-6 py-4 text-[#728495]">23 Mar 2026</td>
                        <td class="px-6 py-4 text-right font-bold text-red-600">$ 320.50</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Recibido</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Ver Detalles"><i class="ph ph-eye text-xl"></i></button>
                        </td>
                    </tr>
                    <tr class="border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition">
                        <td class="px-6 py-4 font-bold">OC-2026-090</td>
                        <td class="px-6 py-4">Lubricantes de Occidente</td>
                        <td class="px-6 py-4 text-[#728495]">24 Mar 2026</td>
                        <td class="px-6 py-4 text-right font-bold text-red-600">$ 150.00</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">En Tránsito</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Ver Detalles"><i class="ph ph-eye text-xl"></i></button>
                            <button class="text-green-600 hover:text-green-800 mx-1 transition-colors" title="Marcar Recibido"><i class="ph ph-check-circle text-xl"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection

<!-- Modal Nueva Compra -->
<div id="nuevaCompraModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div id="modalBackdropCompra" class="absolute inset-0 bg-black/50"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-[95%] max-w-2xl mx-4 z-10 overflow-hidden">
        <div class="p-4 border-b flex justify-between items-center">
            <h4 class="font-bold text-lg">Nueva Compra</h4>
            <button id="closeModalCompra" class="text-[#728495] hover:text-[#263A47]">Cerrar ✕</button>
        </div>
        <div id="modalErrorBannerCompra" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-2">
            <strong class="font-medium">Error:</strong>
            <span id="modalErrorMessageCompra" class="ml-2">Mensaje de error</span>
        </div>
        <form id="formNuevaCompra" class="p-4 space-y-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="text-sm text-[#728495]">N° Orden</label>
                    <input id="orden" name="orden" type="text" required class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none">
                    <span data-error-for="orden" class="text-red-600 text-sm mt-1 hidden"></span>
                </div>
                <div>
                    <label class="text-sm text-[#728495]">Proveedor</label>
                    <input id="proveedor" name="proveedor" type="text" required class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none">
                    <span data-error-for="proveedor" class="text-red-600 text-sm mt-1 hidden"></span>
                </div>
                <div>
                    <label class="text-sm text-[#728495]">Fecha</label>
                    <input id="fecha" name="fecha" type="date" required class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none">
                    <span data-error-for="fecha" class="text-red-600 text-sm mt-1 hidden"></span>
                </div>
                <div>
                    <label class="text-sm text-[#728495]">Monto Total</label>
                    <input id="monto" name="monto" type="number" step="0.01" required class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none">
                    <span data-error-for="monto" class="text-red-600 text-sm mt-1 hidden"></span>
                </div>
                <div>
                    <label class="text-sm text-[#728495]">Estado</label>
                    <select id="estado" name="estado" class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none">
                        <option value="recibido">Recibido</option>
                        <option value="en_transito">En Tránsito</option>
                        <option value="pendiente">Pendiente</option>
                    </select>
                    <span data-error-for="estado" class="text-red-600 text-sm mt-1 hidden"></span>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-[#728495]">Acciones / Observaciones</label>
                    <textarea id="acciones_compra" name="acciones" rows="3" class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none"></textarea>
                    <span data-error-for="acciones" class="text-red-600 text-sm mt-1 hidden"></span>
                </div>
            </div>
            <div class="pt-3 flex justificEnd gap-2 border-t">
                <button type="button" id="cancelCompraBtn" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded bg-[#263A47] text-white">Guardar Compra</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/compras.js') }}"></script>
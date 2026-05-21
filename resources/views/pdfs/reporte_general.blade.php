<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General del Taller</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Helvetica Neue', Arial, sans-serif; }
        body { font-size: 10px; color: #333; padding: 20px; }
        
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #263A47; padding-bottom: 15px; }
        .logo { font-size: 20px; font-weight: bold; text-transform: uppercase; color: #2563eb; }
        .company-info { font-size: 9px; color: #728495; margin-top: 4px; }
        .title { font-size: 16px; font-weight: bold; margin-top: 10px; color: #263A47; }
        
        table.kpis { width: 100%; border-collapse: separate; border-spacing: 10px 0; margin-bottom: 20px; }
        .kpi { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 15px; text-align: center; }
        .kpi .valor { font-size: 20px; font-weight: bold; color: #263A47; }
        .kpi .etiqueta { font-size: 9px; color: #728495; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 5px; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; }
        .data-table th { background: #263A47; color: white; padding: 8px 10px; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; }
        .data-table td { padding: 7px 10px; border-bottom: 1px solid #e9ecef; font-size: 10px; }
        .data-table tr:nth-child(even) { background: #f8f9fa; }
        
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-espera { background: #f3f4f6; color: #6b7280; }
        .badge-reparacion { background: #fef3c7; color: #d97706; }
        .badge-finalizado { background: #dbeafe; color: #2563eb; }
        .badge-entregado { background: #dcfce7; color: #16a34a; }
        .badge-pagado { background: #dcfce7; color: #16a34a; }
        .badge-pendiente { background: #fee2e2; color: #dc2626; }
        .badge-parcial { background: #fef3c7; color: #d97706; }
        
        .section-title { font-size: 13px; color: #263A47; margin-bottom: 5px; border-bottom: 1px solid #cbd5e1; padding-bottom: 5px; }
        .text-right { text-align: right; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">AutoSys</div>
        <div class="company-info">RIF: J-00000000-0 | Barquisimeto, Edo. Lara | Tel: 0424-xxx-xxxx</div>
        <div class="title">REPORTE GENERAL DEL TALLER</div>
        <div style="font-size: 10px; color: #728495; margin-top: 5px;">Período: {{ $mes }}</div>
    </div>

    <table class="kpis">
        <tr>
            <td class="kpi">
                <div class="valor">{{ $totalOrdenes }}</div>
                <div class="etiqueta">Órdenes Totales</div>
            </td>
            <td class="kpi">
                <div class="valor">$ {{ number_format($totalIngresos, 2) }}</div>
                <div class="etiqueta">Ingresos Facturados</div>
            </td>
            <td class="kpi">
                <div class="valor">{{ $facturas->count() }}</div>
                <div class="etiqueta">Facturas Emitidas</div>
            </td>
            <td class="kpi">
                <div class="valor">$ {{ $facturas->count() > 0 ? number_format($totalIngresos / $facturas->count(), 2) : '0.00' }}</div>
                <div class="etiqueta">Ticket Promedio</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Detalle de Órdenes de Servicio</div>

    <table class="data-table">
        <thead>
            <tr>
                <th>#O.S.</th>
                <th>Fecha</th>
                <th>Placa</th>
                <th>Vehículo</th>
                <th>Mecánico</th>
                <th>Estado</th>
                <th>Diagnóstico</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ordenes as $orden)
            <tr>
                <td style="font-weight: bold;">#00{{ $orden->id }}</td>
                <td>{{ $orden->created_at->format('d/m/Y') }}</td>
                <td style="font-family: monospace; font-weight: bold;">{{ $orden->vehiculo->placa ?? '—' }}</td>
                <td>{{ ($orden->vehiculo->marca ?? '') . ' ' . ($orden->vehiculo->modelo ?? '') }}</td>
                <td>{{ $orden->mecanico->nombre ?? 'Sin asignar' }}</td>
                <td>
                    @if($orden->estado == 'En Espera')
                        <span class="badge badge-espera">En Espera</span>
                    @elseif($orden->estado == 'En Reparación')
                        <span class="badge badge-reparacion">En Taller</span>
                    @elseif($orden->estado == 'Finalizado')
                        <span class="badge badge-finalizado">Finalizado</span>
                    @elseif($orden->estado == 'Entregado')
                        <span class="badge badge-entregado">Entregado</span>
                    @endif
                </td>
                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($orden->diagnostico, 50) }}</td>
            </tr>
            @endforeach
            @if($ordenes->count() === 0)
            <tr><td colspan="7" class="text-center" style="color: #94a3b8; padding: 15px;">No hay órdenes de servicio en este período</td></tr>
            @endif
        </tbody>
    </table>

    @if($facturas->count() > 0)
    <div class="section-title" style="margin-top: 30px;">Resumen de Facturación</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>N° Factura</th>
                <th>Referencia</th>
                <th class="text-right">Repuestos ($)</th>
                <th class="text-right">Mano Obra ($)</th>
                <th class="text-right">Total ($)</th>
                <th class="text-right">Estado Pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facturas as $factura)
            <tr>
                <td style="font-weight: bold;">{{ $factura->numero_factura }}</td>
                <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $factura->referencia }}</td>
                <td class="text-right">{{ number_format($factura->subtotal_repuestos, 2) }}</td>
                <td class="text-right">{{ number_format($factura->subtotal_mano_obra, 2) }}</td>
                <td class="text-right" style="font-weight: bold; color: #16a34a;">{{ number_format($factura->total_facturado, 2) }}</td>
                <td class="text-right">
                    @if($factura->estado_pago === 'Pagado')
                        <span class="badge badge-pagado">PAGADO</span>
                    @elseif($factura->estado_pago === 'Parcial')
                        <span class="badge badge-parcial">PARCIAL</span>
                    @else
                        <span class="badge badge-pendiente">PENDIENTE</span>
                    @endif
                </td>
            </tr>
            @endforeach
            <tr style="background: #263A47; color: white; font-weight: bold;">
                <td colspan="4" class="text-right" style="padding: 10px;">TOTAL FACTURADO:</td>
                <td class="text-right" style="padding: 10px; font-size: 14px;">$ {{ number_format($totalIngresos, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @endif

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} — Sistema AutoSys | Página <span class="page-number"></span>
    </div>
</body>
</html>

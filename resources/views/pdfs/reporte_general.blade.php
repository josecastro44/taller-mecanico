<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General del Taller</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Helvetica', Arial, sans-serif; }
        body { font-size: 11px; color: #333; padding: 20px; }
        
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #263A47; padding-bottom: 15px; }
        .header h1 { font-size: 18px; color: #263A47; margin-bottom: 3px; }
        .header p { color: #728495; font-size: 11px; }
        
        .kpis { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .kpi { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 10px 15px; text-align: center; width: 23%; }
        .kpi .valor { font-size: 18px; font-weight: bold; color: #263A47; }
        .kpi .etiqueta { font-size: 9px; color: #728495; text-transform: uppercase; letter-spacing: 0.5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #263A47; color: white; padding: 8px 10px; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; }
        td { padding: 7px 10px; border-bottom: 1px solid #e9ecef; font-size: 10px; }
        tr:nth-child(even) { background: #f8f9fa; }
        
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-espera { background: #f3f4f6; color: #6b7280; }
        .badge-reparacion { background: #fef3c7; color: #d97706; }
        .badge-finalizado { background: #dbeafe; color: #2563eb; }
        .badge-entregado { background: #d1fae5; color: #059669; }
        
        .footer { margin-top: 20px; text-align: center; color: #728495; font-size: 9px; border-top: 1px solid #dee2e6; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔧 Reporte General del Taller — AutoSys</h1>
        <p>Período: {{ $mes }} | Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="kpis">
        <div class="kpi">
            <div class="valor">{{ $totalOrdenes }}</div>
            <div class="etiqueta">Órdenes Totales</div>
        </div>
        <div class="kpi">
            <div class="valor">${{ number_format($totalIngresos, 2) }}</div>
            <div class="etiqueta">Ingresos Facturados</div>
        </div>
        <div class="kpi">
            <div class="valor">{{ $facturas->count() }}</div>
            <div class="etiqueta">Facturas Emitidas</div>
        </div>
        <div class="kpi">
            <div class="valor">${{ $facturas->count() > 0 ? number_format($totalIngresos / $facturas->count(), 2) : '0.00' }}</div>
            <div class="etiqueta">Ticket Promedio</div>
        </div>
    </div>

    <h3 style="font-size: 13px; color: #263A47; margin-bottom: 5px;">Detalle de Órdenes de Servicio</h3>

    <table>
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
                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{ Str::limit($orden->diagnostico, 60) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($facturas->count() > 0)
    <h3 style="font-size: 13px; color: #263A47; margin: 20px 0 5px;">Resumen de Facturación</h3>
    <table>
        <thead>
            <tr>
                <th>N° Factura</th>
                <th>Referencia</th>
                <th style="text-align: right;">Repuestos</th>
                <th style="text-align: right;">Mano Obra</th>
                <th style="text-align: right;">IVA</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facturas as $factura)
            <tr>
                <td style="font-weight: bold;">{{ $factura->numero_factura }}</td>
                <td>{{ $factura->referencia }}</td>
                <td style="text-align: right;">${{ number_format($factura->subtotal_repuestos, 2) }}</td>
                <td style="text-align: right;">${{ number_format($factura->subtotal_mano_obra, 2) }}</td>
                <td style="text-align: right;">${{ number_format($factura->monto_iva, 2) }}</td>
                <td style="text-align: right; font-weight: bold;">${{ number_format($factura->total_facturado, 2) }}</td>
            </tr>
            @endforeach
            <tr style="background: #263A47; color: white; font-weight: bold;">
                <td colspan="5" style="text-align: right; padding: 8px 10px;">TOTAL FACTURADO:</td>
                <td style="text-align: right; padding: 8px 10px;">${{ number_format($totalIngresos, 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <div class="footer">
        AutoSys — Sistema de Gestión de Taller Mecánico | Reporte generado automáticamente
    </div>
</body>
</html>

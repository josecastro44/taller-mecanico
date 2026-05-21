<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Libro de Ventas</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #263A47; font-size: 10px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #263A47; padding-bottom: 15px; }
        .logo { font-size: 20px; font-weight: bold; text-transform: uppercase; color: #2563eb; }
        .company-info { font-size: 9px; color: #728495; margin-top: 4px; }
        .title { font-size: 16px; font-weight: bold; margin-top: 10px; color: #263A47; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #263A47; color: white; padding: 6px; text-align: center; font-size: 9px; text-transform: uppercase; }
        td { padding: 6px; border-bottom: 1px solid #e2e8f0; text-align: center; font-size: 10px; }
        .text-right { text-align: right; }
        .totales { margin-top: 20px; width: 100%; border-top: 2px solid #263A47; padding-top: 10px; text-align: right; font-weight: bold; font-size: 14px; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 8px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .badge-pagado { background: #dcfce7; color: #16a34a; }
        .badge-pendiente { background: #fee2e2; color: #dc2626; }
        .badge-parcial { background: #fef3c7; color: #d97706; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">AutoSys</div>
        <div class="company-info">RIF: J-00000000-0 | Barquisimeto, Edo. Lara | Tel: 0424-xxx-xxxx</div>
        <div class="title">LIBRO DE VENTAS - {{ strtoupper($mes) }}</div>
        <div style="font-size: 10px; color: #728495; margin-top: 5px;">Reporte Oficial de Ingresos</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>FECHA</th>
                <th>N° FACTURA</th>
                <th>REFERENCIA / CLIENTE</th>
                <th class="text-right">MANO DE OBRA ($)</th>
                <th class="text-right">REPUESTOS ($)</th>
                <th class="text-right">TOTAL FACTURADO ($)</th>
                <th>ESTADO PAGO</th>
                <th class="text-right">SALDO PEND. ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facturas as $fac)
            <tr>
                <td>{{ $fac->created_at->format('d/m/Y') }}</td>
                <td><strong>{{ $fac->numero_factura }}</strong></td>
                <td style="text-align: left; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $fac->referencia }}</td>
                <td class="text-right">{{ number_format($fac->subtotal_mano_obra, 2) }}</td>
                <td class="text-right">{{ number_format($fac->subtotal_repuestos, 2) }}</td>
                <td class="text-right" style="font-weight: bold; color: #16a34a;">{{ number_format($fac->total_facturado, 2) }}</td>
                <td>
                    @if($fac->estado_pago === 'Pagado')
                        <span class="badge badge-pagado">PAGADO</span>
                    @elseif($fac->estado_pago === 'Parcial')
                        <span class="badge badge-parcial">PARCIAL</span>
                    @else
                        <span class="badge badge-pendiente">PENDIENTE</span>
                    @endif
                </td>
                <td class="text-right" style="color: {{ $fac->saldo_pendiente > 0 ? '#dc2626' : '#94a3b8' }};">
                    {{ number_format($fac->saldo_pendiente, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totales">
        INGRESOS TOTALES FACTURADOS DEL MES: <span style="color: #16a34a;">$ {{ number_format($ingresosBrutos, 2) }}</span>
    </div>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} — Sistema AutoSys | Página <span class="page-number"></span>
    </div>
</body>
</html>
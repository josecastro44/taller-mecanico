<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Compras</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #263A47; font-size: 10px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 15px; }
        .logo { font-size: 20px; font-weight: bold; text-transform: uppercase; color: #2563eb; }
        .company-info { font-size: 9px; color: #728495; margin-top: 4px; }
        .title { font-size: 16px; font-weight: bold; margin-top: 10px; color: #263A47; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .data-table th { background: #263A47; color: white; padding: 6px; text-align: center; font-size: 9px; text-transform: uppercase; }
        .data-table td { padding: 6px; border-bottom: 1px solid #e2e8f0; text-align: center; font-size: 10px; }
        .data-table tr:nth-child(even) { background: #f8fafc; }
        
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .totales { margin-top: 20px; width: 100%; border-top: 2px solid #263A47; padding-top: 10px; text-align: right; font-weight: bold; font-size: 14px; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">AutoSys</div>
        <div class="company-info">RIF: J-00000000-0 | Barquisimeto, Edo. Lara | Tel: 0424-xxx-xxxx</div>
        <div class="title">REPORTE DE ÓRDENES DE COMPRA</div>
        <div style="font-size: 10px; color: #728495; margin-top: 5px;">Historial de abastecimiento de inventario</div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>FECHA</th>
                <th>N° ORDEN</th>
                <th class="text-left">PROVEEDOR</th>
                <th>ESTADO</th>
                <th class="text-right">TOTAL ($)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalInversion = 0; @endphp
            @foreach($compras as $c)
            @php $totalInversion += $c->total; @endphp
            <tr>
                <td>{{ $c->created_at->format('d/m/Y') }}</td>
                <td style="font-weight: bold;">{{ $c->numero_orden }}</td>
                <td class="text-left">{{ $c->proveedor->nombre }}</td>
                <td style="color: {{ $c->estado === 'Recibido' ? '#16a34a' : '#d97706' }}; font-weight: bold;">
                    {{ strtoupper($c->estado) }}
                </td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($c->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totales">
        TOTAL INVERSIÓN: <span style="color: #dc2626;">$ {{ number_format($totalInversion, 2) }}</span>
    </div>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} — Sistema AutoSys | Página <span class="page-number"></span>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Balance General</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #263A47; font-size: 11px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #263A47; padding-bottom: 15px; }
        .logo { font-size: 20px; font-weight: bold; text-transform: uppercase; color: #2563eb; }
        .company-info { font-size: 9px; color: #728495; margin-top: 4px; }
        .title { font-size: 16px; font-weight: bold; margin-top: 10px; color: #263A47; }
        
        .resumen-container { width: 100%; display: table; margin-bottom: 25px; }
        .resumen-col { display: table-cell; width: 33.33%; padding: 0 5px; }
        .resumen-box { border: 1px solid #cbd5e1; background: #f8fafc; border-radius: 4px; padding: 15px; text-align: center; }
        .resumen-box h3 { font-size: 10px; color: #728495; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .resumen-box .valor { font-size: 20px; font-weight: bold; margin-top: 5px; }
        
        .verde { color: #16a34a; }
        .rojo { color: #dc2626; }
        .azul { color: #2563eb; }

        .section-title { font-size: 13px; color: #263A47; margin: 20px 0 10px; padding-bottom: 5px; border-bottom: 1px solid #cbd5e1; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th { background: #263A47; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; border-bottom: 2px solid #cbd5e1; }
        .data-table td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        .text-right { text-align: right; }
        .subtotal-row { background: #f1f5f9; font-weight: bold; }
        .subtotal-row td { border-bottom: 2px solid #cbd5e1; }

        .firmas { width: 100%; display: table; margin-top: 50px; }
        .firma-col { display: table-cell; width: 50%; text-align: center; }
        .linea-firma { border-top: 1px solid #263A47; width: 200px; margin: 0 auto; padding-top: 5px; font-size: 10px; }

        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">AutoSys</div>
        <div class="company-info">RIF: J-00000000-0 | Barquisimeto, Edo. Lara | Tel: 0424-xxx-xxxx</div>
        <div class="title">BALANCE GENERAL FINANCIERO</div>
        <div style="font-size: 10px; color: #728495; margin-top: 5px;">Período: {{ $periodo }}</div>
    </div>

    {{-- RESUMEN EJECUTIVO --}}
    <div class="resumen-container">
        <div class="resumen-col">
            <div class="resumen-box">
                <h3>Total Ingresos</h3>
                <div class="valor verde">$ {{ number_format($balance['ingresos'], 2) }}</div>
            </div>
        </div>
        <div class="resumen-col">
            <div class="resumen-box">
                <h3>Total Egresos</h3>
                <div class="valor rojo">$ {{ number_format($balance['egresos'], 2) }}</div>
            </div>
        </div>
        <div class="resumen-col">
            <div class="resumen-box" style="border: 2px solid {{ $balance['utilidad_neta'] >= 0 ? '#16a34a' : '#dc2626' }};">
                <h3>Utilidad Neta</h3>
                <div class="valor {{ $balance['utilidad_neta'] >= 0 ? 'verde' : 'rojo' }}">$ {{ number_format($balance['utilidad_neta'], 2) }}</div>
            </div>
        </div>
    </div>

    {{-- DESGLOSE POR CATEGORÍA --}}
    @php
        $ingresos = $desglose->where('tipo', 'ingreso');
        $egresos = $desglose->where('tipo', 'egreso');
        $catLabels = ['facturacion' => 'Facturación (O.S.)', 'venta_mostrador' => 'Ventas al Mostrador', 'compra_inventario' => 'Compras de Inventario', 'gasto_operativo' => 'Gastos Operativos', 'nomina' => 'Nómina de Empleados'];
    @endphp

    <div class="section-title">Detalle de Ingresos (Debe)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Categoría</th>
                <th class="text-right">Monto ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ingresos as $item)
            <tr>
                <td>{{ $catLabels[$item->categoria] ?? ucfirst(str_replace('_', ' ', $item->categoria)) }}</td>
                <td class="text-right verde" style="font-weight: bold;">$ {{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
            @if($ingresos->count() === 0)
            <tr><td colspan="2" style="text-align: center; color: #98A9BE; padding: 15px;">Sin ingresos registrados en el período</td></tr>
            @endif
            <tr class="subtotal-row">
                <td class="text-right">SUBTOTAL INGRESOS:</td>
                <td class="text-right verde" style="font-size: 13px;">$ {{ number_format($balance['ingresos'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Detalle de Egresos (Haber)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Categoría</th>
                <th class="text-right">Monto ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($egresos as $item)
            <tr>
                <td>{{ $catLabels[$item->categoria] ?? ucfirst(str_replace('_', ' ', $item->categoria)) }}</td>
                <td class="text-right rojo" style="font-weight: bold;">$ {{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
            @if($egresos->count() === 0)
            <tr><td colspan="2" style="text-align: center; color: #98A9BE; padding: 15px;">Sin egresos registrados en el período</td></tr>
            @endif
            <tr class="subtotal-row">
                <td class="text-right">SUBTOTAL EGRESOS:</td>
                <td class="text-right rojo" style="font-size: 13px;">$ {{ number_format($balance['egresos'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="firmas">
        <div class="firma-col">
            <div class="linea-firma">Preparado por / Administración</div>
        </div>
        <div class="firma-col">
            <div class="linea-firma">Aprobado por / Gerencia</div>
        </div>
    </div>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} — Sistema AutoSys | Página <span class="page-number"></span>
    </div>
</body>
</html>

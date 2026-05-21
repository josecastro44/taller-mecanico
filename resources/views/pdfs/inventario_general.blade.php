<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inventario General</title>
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
        
        .critico { color: #dc2626; font-weight: bold; background: #fee2e2; padding: 2px 6px; border-radius: 4px; }
        .badge-margen { color: #2563eb; font-size: 9px; font-weight: bold; }

        .resumen-container { width: 100%; display: table; margin-top: 20px; margin-bottom: 20px; }
        .resumen-col { display: table-cell; width: 33.33%; padding: 0 5px; }
        .resumen-box { border: 1px solid #cbd5e1; background: #f8fafc; border-radius: 4px; padding: 15px; text-align: center; }
        .resumen-box h3 { font-size: 10px; color: #728495; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .resumen-box .valor { font-size: 18px; font-weight: bold; margin-top: 5px; color: #263A47; }

        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">AutoSys</div>
        <div class="company-info">RIF: J-00000000-0 | Barquisimeto, Edo. Lara | Tel: 0424-xxx-xxxx</div>
        <div class="title">INVENTARIO GENERAL DE REPUESTOS</div>
        <div style="font-size: 10px; color: #728495; margin-top: 5px;">Generado el: {{ $fecha }}</div>
    </div>

    <div class="resumen-container">
        <div class="resumen-col">
            <div class="resumen-box">
                <h3>Total de Ítems</h3>
                <div class="valor">{{ $repuestos->count() }}</div>
            </div>
        </div>
        <div class="resumen-col">
            <div class="resumen-box">
                <h3>Ítems Críticos (Bajo Stock)</h3>
                <div class="valor" style="color: #dc2626;">{{ $repuestos->where('stock', '<=', 'stock_minimo')->count() }}</div>
            </div>
        </div>
        <div class="resumen-col">
            <div class="resumen-box">
                <h3>Valor Total en Almacén</h3>
                <div class="valor" style="color: #16a34a;">$ {{ number_format($totalInversion, 2) }}</div>
            </div>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>CÓDIGO</th>
                <th class="text-left">REPUESTO / COMPATIBILIDAD</th>
                <th>STOCK / MÍNIMO</th>
                <th class="text-right">COSTO (C/U)</th>
                <th class="text-right">PRECIO VENTA</th>
                <th class="text-right">MARGEN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($repuestos as $rep)
            @php
                $margen = $rep->costo_adquisicion > 0 ? (($rep->precio_venta - $rep->costo_adquisicion) / $rep->costo_adquisicion) * 100 : 0;
                $esCritico = $rep->stock <= $rep->stock_minimo;
            @endphp
            <tr>
                <td style="font-weight: bold;">{{ $rep->codigo }}</td>
                <td class="text-left">
                    <strong>{{ $rep->nombre }}</strong>
                    @if($rep->marca_vehiculo || $rep->modelo_vehiculo)
                        <br><span style="font-size: 8px; color: #728495;">Compat: {{ $rep->marca_vehiculo }} {{ $rep->modelo_vehiculo }} {{ $rep->año_vehiculo }}</span>
                    @endif
                </td>
                <td>
                    <span class="{{ $esCritico ? 'critico' : '' }}">{{ $rep->stock }} und</span>
                    <br><span style="font-size: 8px; color: #94a3b8;">Mín: {{ $rep->stock_minimo }}</span>
                </td>
                <td class="text-right">$ {{ number_format($rep->costo_adquisicion, 2) }}</td>
                <td class="text-right" style="font-weight: bold; color: #16a34a;">$ {{ number_format($rep->precio_venta, 2) }}</td>
                <td class="text-right"><span class="badge-margen">{{ number_format($margen, 1) }}%</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} — Sistema AutoSys | Página <span class="page-number"></span>
    </div>
</body>
</html>
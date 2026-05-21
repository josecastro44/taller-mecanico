<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Gastos Operativos</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #263A47; font-size: 10px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #dc2626; padding-bottom: 15px; }
        .logo { font-size: 20px; font-weight: bold; text-transform: uppercase; color: #dc2626; }
        .company-info { font-size: 9px; color: #728495; margin-top: 4px; }
        .title { font-size: 16px; font-weight: bold; margin-top: 10px; color: #263A47; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .data-table th { background: #263A47; color: white; padding: 6px; text-align: center; font-size: 9px; text-transform: uppercase; }
        .data-table td { padding: 6px; border-bottom: 1px solid #e2e8f0; text-align: center; font-size: 10px; }
        .data-table tr:nth-child(even) { background: #f8fafc; }
        
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .badge-pagado { background: #dcfce7; color: #16a34a; }
        .badge-pendiente { background: #fef3c7; color: #d97706; }
        .badge-vencido { background: #fee2e2; color: #dc2626; }
        
        .totales { margin-top: 20px; width: 100%; border-top: 2px solid #263A47; padding-top: 10px; text-align: right; font-weight: bold; font-size: 14px; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">AutoSys</div>
        <div class="company-info">RIF: J-00000000-0 | Barquisimeto, Edo. Lara | Tel: 0424-xxx-xxxx</div>
        <div class="title">REPORTE DE GASTOS OPERATIVOS</div>
        <div style="font-size: 10px; color: #728495; margin-top: 5px;">Período: {{ $periodo }}</div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th class="text-left">CATEGORÍA</th>
                <th class="text-left">DESCRIPCIÓN</th>
                <th>FRECUENCIA</th>
                <th>ESTADO</th>
                <th>FECHA PAGO</th>
                <th>MÉTODO</th>
                <th class="text-right">MONTO ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gastos as $i => $gasto)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="text-left" style="font-weight: bold;">{{ $gasto->categoria->nombre ?? 'Sin categoría' }}</td>
                <td class="text-left">{{ $gasto->descripcion }}</td>
                <td>{{ ucfirst($gasto->frecuencia) }}</td>
                <td>
                    @if($gasto->estado === 'pagado')
                        <span class="badge badge-pagado">PAGADO</span>
                    @elseif($gasto->estaVencido())
                        <span class="badge badge-vencido">VENCIDO</span>
                    @else
                        <span class="badge badge-pendiente">PENDIENTE</span>
                    @endif
                </td>
                <td>{{ $gasto->fecha_pago ? $gasto->fecha_pago->format('d/m/Y') : '—' }}</td>
                <td>{{ $gasto->metodo_pago ?? '—' }}</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($gasto->monto, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totales">
        TOTAL GASTOS OPERATIVOS: <span style="color: #dc2626;">$ {{ number_format($totalGastos, 2) }}</span>
    </div>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} — Sistema AutoSys | Página <span class="page-number"></span>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Libro Diario Financiero</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #263A47; font-size: 10px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #263A47; padding-bottom: 15px; }
        .logo { font-size: 20px; font-weight: bold; text-transform: uppercase; color: #2563eb; }
        .company-info { font-size: 9px; color: #728495; margin-top: 4px; }
        .title { font-size: 16px; font-weight: bold; margin-top: 10px; color: #263A47; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .data-table th { background: #263A47; color: white; padding: 6px; text-align: center; font-size: 9px; text-transform: uppercase; }
        .data-table td { padding: 6px; border-bottom: 1px solid #e2e8f0; text-align: center; font-size: 10px; }
        .data-table tr:nth-child(even) { background: #f8fafc; }
        
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .badge-ingreso { background: #dcfce7; color: #16a34a; }
        .badge-egreso { background: #fee2e2; color: #dc2626; }
        
        .resumen-container { width: 100%; display: table; margin-top: 20px; }
        .resumen-col { display: table-cell; width: 33.33%; padding: 10px; }
        .resumen-box { border: 1px solid #cbd5e1; background: #f8fafc; border-radius: 4px; padding: 15px; text-align: center; }
        .resumen-box .valor { font-size: 16px; font-weight: bold; margin-top: 5px; }
        
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
        <div class="title">LIBRO DIARIO FINANCIERO</div>
        <div style="font-size: 10px; color: #728495; margin-top: 5px;">Período: {{ $periodo }}</div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>FECHA</th>
                <th>TIPO</th>
                <th>CATEGORÍA</th>
                <th class="text-left">CONCEPTO / REFERENCIA</th>
                <th class="text-right">DEBE (INGRESOS)</th>
                <th class="text-right">HABER (EGRESOS)</th>
                <th class="text-right">SALDO ACUM.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asientos as $asiento)
            <tr>
                <td>{{ $asiento->fecha->format('d/m/Y') }}</td>
                <td>
                    <span class="badge {{ $asiento->tipo == 'ingreso' ? 'badge-ingreso' : 'badge-egreso' }}">
                        {{ $asiento->tipo }}
                    </span>
                </td>
                <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $asiento->categoria) }}</td>
                <td class="text-left">
                    {{ $asiento->concepto }}
                    @if($asiento->referencia)
                        <br><span style="font-size: 8px; color: #64748b;">Ref: {{ $asiento->referencia }}</span>
                    @endif
                </td>
                
                @if($asiento->tipo == 'ingreso')
                    <td class="text-right" style="color: #16a34a;">$ {{ number_format($asiento->monto, 2) }}</td>
                    <td class="text-right">-</td>
                @else
                    <td class="text-right">-</td>
                    <td class="text-right" style="color: #dc2626;">$ {{ number_format($asiento->monto, 2) }}</td>
                @endif
                
                <td class="text-right" style="font-weight: bold;">$ {{ number_format($asiento->saldo_acumulado, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="resumen-container">
        <div class="resumen-col">
            <div class="resumen-box">
                <div style="color: #64748b; font-size: 10px; text-transform: uppercase;">Total Ingresos</div>
                <div class="valor" style="color: #16a34a;">$ {{ number_format($balance['ingresos'], 2) }}</div>
            </div>
        </div>
        <div class="resumen-col">
            <div class="resumen-box">
                <div style="color: #64748b; font-size: 10px; text-transform: uppercase;">Total Egresos</div>
                <div class="valor" style="color: #dc2626;">$ {{ number_format($balance['egresos'], 2) }}</div>
            </div>
        </div>
        <div class="resumen-col">
            <div class="resumen-box">
                <div style="color: #64748b; font-size: 10px; text-transform: uppercase;">Balance (Flujo de Caja)</div>
                <div class="valor" style="color: {{ $balance['utilidad_neta'] >= 0 ? '#2563eb' : '#dc2626' }};">
                    $ {{ number_format($balance['utilidad_neta'], 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="firmas">
        <div class="firma-col">
            <div class="linea-firma">Preparado por / Administración</div>
        </div>
        <div class="firma-col">
            <div class="linea-firma">Aprobado por / Gerencia</div>
        </div>
    </div>

    <div class="footer">
                <h3>Total Egresos</h3>
                <p class="egreso">$ {{ number_format($balance['egresos'], 2) }}</p>
            </div>
            <div class="total-box">
                <h3>Utilidad Neta</h3>
                <p style="color: {{ $balance['utilidad_neta'] >= 0 ? '#2563eb' : '#dc2626' }}">$ {{ number_format($balance['utilidad_neta'], 2) }}</p>
            </div>
        </div>
    </div>

    <p class="fecha-gen">Generado el {{ now()->format('d/m/Y H:i') }} — AutoSys / Castro Technology Research</p>
</body>
</html>

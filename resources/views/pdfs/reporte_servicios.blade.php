<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Servicios</title>
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
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">AutoSys</div>
        <div class="company-info">RIF: J-00000000-0 | Barquisimeto, Edo. Lara | Tel: 0424-xxx-xxxx</div>
        <div class="title">CATÁLOGO DE SERVICIOS</div>
        <div style="font-size: 10px; color: #728495; margin-top: 5px;">Mano de obra y precios tabulados</div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>CÓDIGO</th>
                <th class="text-left">DESCRIPCIÓN DEL SERVICIO</th>
                <th class="text-right">SENCILLO ($)</th>
                <th class="text-right">ALTA GAMA ($)</th>
                <th class="text-right">CARGA PESADA ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($servicios as $s)
            <tr>
                <td style="font-weight: bold; color: #4A5B6A;">{{ $s->codigo }}</td>
                <td class="text-left" style="font-weight: bold;">{{ $s->descripcion }}</td>
                <td class="text-right">{{ number_format($s->precio_sencillo, 2) }}</td>
                <td class="text-right">{{ number_format($s->precio_alta_gama, 2) }}</td>
                <td class="text-right">{{ number_format($s->precio_carga_pesada, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} — Sistema AutoSys | Página <span class="page-number"></span>
    </div>
</body>
</html>

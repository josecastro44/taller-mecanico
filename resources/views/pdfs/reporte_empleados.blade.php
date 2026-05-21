<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Empleados</title>
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
        
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">AutoSys</div>
        <div class="company-info">RIF: J-00000000-0 | Barquisimeto, Edo. Lara | Tel: 0424-xxx-xxxx</div>
        <div class="title">REPORTE DE EMPLEADOS</div>
        <div style="font-size: 10px; color: #728495; margin-top: 5px;">Personal Activo y Esquema Laboral</div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-left">NOMBRE</th>
                <th>CÉDULA</th>
                <th>TELÉFONO</th>
                <th>ESPECIALIDAD</th>
                <th>ROL SISTEMA</th>
                <th class="text-right">SUELDO BASE ($)</th>
                <th class="text-right">COMISIÓN (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empleados as $e)
            <tr>
                <td class="text-left" style="font-weight: bold;">{{ $e->nombre }}</td>
                <td>{{ $e->cedula }}</td>
                <td>{{ $e->telefono ?? 'N/A' }}</td>
                <td>{{ $e->especialidad }}</td>
                <td style="text-transform: uppercase;">{{ $e->user->rol ?? 'N/A' }}</td>
                <td class="text-right" style="font-weight: bold; color: #16a34a;">{{ number_format($e->sueldo_base, 2) }}</td>
                <td class="text-right">{{ $e->comision }} %</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} — Sistema AutoSys | Página <span class="page-number"></span>
    </div>
</body>
</html>

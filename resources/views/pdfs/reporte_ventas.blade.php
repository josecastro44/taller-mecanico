<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #263A47; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { font-size: 20px; font-weight: bold; }
        .tabla { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .tabla th { background: #263A47; color: white; padding: 8px; text-align: center; font-size: 11px; }
        .tabla td { padding: 8px; border-bottom: 1px solid #B4C5D8; text-align: center; }
        .totales { margin-top: 20px; text-align: right; font-weight: bold; font-size: 16px;}
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">CTR - Castro Technology Research</div>
        <h2>Reporte de Ventas por Mostrador</h2>
        <p>Mes: {{ ucfirst($mes) }}</p>
    </div>

    <table class="tabla">
        <thead>
            <tr>
                <th>FECHA</th>
                <th>N° TICKET</th>
                <th>CLIENTE</th>
                <th>CI / RIF</th>
                <th>MÉTODO PAGO</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $v)
            <tr>
                <td>{{ $v->created_at->format('d/m/Y') }}</td>
                <td><strong>{{ $v->numero_ticket }}</strong></td>
                <td>{{ $v->cliente }}</td>
                <td>{{ $v->cedula }}</td>
                <td>{{ $v->metodo_pago }}</td>
                <td><strong>$ {{ number_format($v->total, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totales">
        TOTAL INGRESOS MOSTRADOR: $ {{ number_format($totalVentas, 2) }}
    </div>
</body>
</html>
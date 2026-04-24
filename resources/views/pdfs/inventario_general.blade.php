<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #263A47; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #263A47; padding-bottom: 10px; }
        .logo { font-size: 20px; font-weight: bold; }
        .tabla { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .tabla th { background: #263A47; color: white; padding: 8px; font-size: 11px; text-align: left; }
        .tabla td { padding: 8px; border-bottom: 1px solid #B4C5D8; }
        .critico { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">CTR - Castro Technology Research</div>
        <h2>Reporte General de Inventario</h2>
        <p>Generado el: {{ $fecha }}</p>
    </div>

    <table class="tabla">
        <thead>
            <tr>
                <th>CÓDIGO</th>
                <th>REPUESTO / MARCA</th>
                <th>STOCK</th>
                <th>COSTO (C/U)</th>
                <th>PRECIO VENTA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($repuestos as $rep)
            <tr>
                <td>{{ $rep->codigo }}</td>
                <td><strong>{{ $rep->nombre }}</strong><br><span style="font-size:10px; color:#728495">{{ $rep->marca }}</span></td>
                <td class="{{ $rep->stock <= 5 ? 'critico' : '' }}">{{ $rep->stock }} und</td>
                <td>$ {{ number_format($rep->precio_compra, 2) }}</td>
                <td>$ {{ number_format($rep->precio_venta, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: right; font-size: 16px;">
        <strong>Total Invertido en Almacén: $ {{ number_format($totalInversion, 2) }}</strong>
    </div>
</body>
</html>
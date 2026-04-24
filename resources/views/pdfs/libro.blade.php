<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #263A47; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { font-size: 20px; font-weight: bold; text-transform: uppercase; }
        .tabla { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .tabla th { background: #263A47; color: white; padding: 8px; text-align: center; font-size: 11px; }
        .tabla td { padding: 8px; border-bottom: 1px solid #B4C5D8; text-align: center; }
        .totales { margin-top: 20px; width: 100%; border-top: 2px solid #263A47; padding-top: 10px; text-align: right; font-weight: bold; font-size: 14px;}
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #728495; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">CTR - Castro Technology Research</div>
        <h2>Libro de Ventas - {{ ucfirst($mes) }}</h2>
        <p>Reporte Oficial de Ingresos y Retenciones</p>
    </div>

    <table class="tabla">
        <thead>
            <tr>
                <th>FECHA</th>
                <th>N° FACTURA</th>
                <th>REFERENCIA</th>
                <th>MANO DE OBRA</th>
                <th>REPUESTOS</th>
                <th>IVA (16%)</th>
                <th>IGTF (3%)</th>
                <th>TOTAL FAC.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facturas as $fac)
            <tr>
                <td>{{ $fac->created_at->format('d/m/Y') }}</td>
                <td><strong>{{ $fac->numero_factura }}</strong></td>
                <td>{{ $fac->referencia }}</td>
                <td>$ {{ number_format($fac->subtotal_mano_obra, 2) }}</td>
                <td>$ {{ number_format($fac->subtotal_repuestos, 2) }}</td>
                <td>$ {{ number_format($fac->monto_iva, 2) }}</td>
                <td>$ {{ number_format($fac->monto_igtf, 2) }}</td>
                <td><strong>$ {{ number_format($fac->total_facturado, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totales">
        <p>Total IVA Recaudado: $ {{ number_format($totalIva, 2) }}</p>
        <p>Total IGTF Recaudado: $ {{ number_format($totalIgtf, 2) }}</p>
        <p style="font-size: 18px;">INGRESOS TOTALES DEL MES: $ {{ number_format($ingresosBrutos, 2) }}</p>
    </div>

    <div class="footer">
        Generado automáticamente por el Sistema AutoSys - CTR
    </div>
</body>
</html>
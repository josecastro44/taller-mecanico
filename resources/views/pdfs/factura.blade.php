<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #263A47; }
        .header { border-bottom: 2px solid #263A47; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #263A47; }
        .info-factura { float: right; text-align: right; }
        .tabla { w-full; border-collapse: collapse; margin-top: 30px; width: 100%; }
        .tabla th { background: #F3F6F8; padding: 10px; text-align: left; font-size: 12px; }
        .tabla td { padding: 10px; border-bottom: 1px solid #B4C5D8; font-size: 14px; }
        .totales { float: right; width: 250px; margin-top: 20px; }
        .fila-total { padding: 5px 0; border-bottom: 1px inset #eee; }
        .total-final { font-size: 18px; font-weight: bold; color: #263A47; margin-top: 10px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #728495; }
    </style>
</head>
<body>
    <div class="header">
        <span class="logo">CTR - Castro Technology Research</span>
        <div class="info-factura">
            <p><strong>FACTURA:</strong> {{ $factura->numero_factura }}</p>
            <p>Fecha: {{ $factura->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <div style="margin-top: 10px;">
        <p><strong>Referencia:</strong> {{ $factura->referencia }}</p>
        <p><strong>Ubicación:</strong> Barquisimeto, Edo. Lara</p>
    </div>

    <table class="tabla">
        <thead>
            <tr>
                <th>DESCRIPCIÓN DEL SERVICIO / PRODUCTO</th>
                <th style="text-align: right;">MONTO (USD)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Servicios de Mano de Obra Especializada</td>
                <td style="text-align: right;">$ {{ number_format($factura->subtotal_mano_obra, 2) }}</td>
            </tr>
            <tr>
                <td>Repuestos e Insumos Utilizados</td>
                <td style="text-align: right;">$ {{ number_format($factura->subtotal_repuestos, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="totales">
        <div class="fila-total">Subtotal: <span style="float:right">$ {{ number_format($factura->base_imponible, 2) }}</span></div>
        <div class="fila-total">IVA (16%): <span style="float:right">$ {{ number_format($factura->monto_iva, 2) }}</span></div>
        @if($factura->monto_igtf > 0)
            <div class="fila-total">IGTF (3%): <span style="float:right">$ {{ number_format($factura->monto_igtf, 2) }}</span></div>
        @endif
        <div class="total-final">TOTAL A PAGAR: <span style="float:right">$ {{ number_format($factura->total_facturado, 2) }}</span></div>
    </div>

    <div class="footer">
        Software de Gestión Automotriz - Desarrollado por Castro Technology Research C.A.
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: monospace; color: #000; font-size: 12px; margin: 0; padding: 10px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-bottom: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; }
    </style>
</head>
<body>
    <div class="center bold" style="font-size: 16px;">CTR AUTO PARTS</div>
    <div class="center">RIF: J-00000000-0</div>
    <div class="center">Barquisimeto, Edo. Lara</div>
    <div class="line"></div>
    
    <div><strong>TICKET:</strong> {{ $venta->numero_ticket }}</div>
    <div><strong>FECHA:</strong> {{ $venta->created_at->format('d/m/Y h:i A') }}</div>
    <div><strong>CLIENTE:</strong> {{ $venta->cliente }}</div>
    <div><strong>CI/RIF:</strong> {{ $venta->cedula }}</div>
    <div><strong>TLF:</strong> {{ $venta->telefono }}</div>
    <div class="line"></div>

    <table>
        <tr>
            <td class="bold">CANT</td>
            <td class="bold">DESCRIPCIÓN</td>
            <td class="bold" style="text-align: right;">TOTAL</td>
        </tr>
        @foreach($venta->detalles as $detalle)
        <tr>
            <td style="vertical-align: top;">{{ $detalle->cantidad }}x</td>
            <td>{{ $detalle->repuesto->nombre }}</td>
            <td style="text-align: right; vertical-align: top;">$ {{ number_format($detalle->subtotal, 2) }}</td>
        </tr>
        @endforeach
    </table>
    
    <div class="line"></div>
    <div style="font-size: 16px; text-align: right;" class="bold">
        TOTAL: $ {{ number_format($venta->total, 2) }}
    </div>
    <div style="text-align: right;">Método: {{ $venta->metodo_pago }}</div>
    
    <div class="line"></div>
    <div class="center">¡Gracias por su compra!</div>
    <div class="center">No se aceptan devoluciones.</div>
</body>
</html>
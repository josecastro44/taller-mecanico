<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket {{ $venta->numero_ticket }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; color: #000; font-size: 11px; margin: 0; padding: 10px; width: 280px; }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .line { border-bottom: 1px dashed #000; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        td { padding: 2px 0; vertical-align: top; }
        .header-logo { font-size: 16px; font-weight: bold; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="center header-logo">AUTOSYS</div>
    <div class="center">RIF: J-00000000-0</div>
    <div class="center">Barquisimeto, Edo. Lara</div>
    <div class="center">Tlf: 0424-xxx-xxxx</div>
    <div class="line"></div>
    
    <div><span class="bold">TICKET:</span> {{ $venta->numero_ticket }}</div>
    <div><span class="bold">FECHA:</span> {{ $venta->created_at->format('d/m/Y h:i A') }}</div>
    <div><span class="bold">CLIENTE:</span> {{ $venta->cliente }}</div>
    <div><span class="bold">CI/RIF:</span> {{ $venta->cedula }}</div>
    <div><span class="bold">TLF:</span> {{ $venta->telefono }}</div>
    <div class="line"></div>

    <table>
        <tr>
            <td class="bold" style="width: 15%;">CANT</td>
            <td class="bold" style="width: 55%;">DESCRIPCIÓN</td>
            <td class="bold right" style="width: 30%;">TOTAL</td>
        </tr>
        <tr><td colspan="3"><div class="line" style="margin: 2px 0;"></div></td></tr>
        @foreach($venta->detalles as $detalle)
        <tr>
            <td>{{ $detalle->cantidad }}x</td>
            <td>
                {{ $detalle->repuesto->nombre }}<br>
                <span style="font-size: 9px;">P.U: ${{ number_format($detalle->precio_unitario, 2) }}</span>
            </td>
            <td class="right">${{ number_format($detalle->subtotal, 2) }}</td>
        </tr>
        @endforeach
    </table>
    
    <div class="line"></div>
    <table style="font-size: 14px; margin-bottom: 2px;">
        <tr>
            <td class="bold">TOTAL A PAGAR:</td>
            <td class="bold right">${{ number_format($venta->total, 2) }}</td>
        </tr>
    </table>
    <div class="right" style="font-size: 10px;">Método de Pago: {{ $venta->metodo_pago }}</div>
    
    <div class="line" style="margin-top: 15px;"></div>
    <div class="center" style="font-size: 10px;">¡Gracias por su compra!</div>
    <div class="center" style="font-size: 9px; margin-top: 5px;">* Conserve este ticket *<br>No se aceptan devoluciones eléctricas.</div>
</body>
</html>
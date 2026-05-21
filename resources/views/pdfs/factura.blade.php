<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $factura->numero_factura }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #263A47; font-size: 12px; margin: 0; padding: 20px; }
        .header { display: table; width: 100%; border-bottom: 2px solid #263A47; padding-bottom: 10px; margin-bottom: 20px; }
        .header-col { display: table-cell; vertical-align: top; }
        .logo-text { font-size: 24px; font-weight: bold; color: #263A47; text-transform: uppercase; margin-bottom: 5px; }
        .company-info { font-size: 10px; color: #728495; line-height: 1.4; }
        .invoice-details { text-align: right; }
        .invoice-title { font-size: 18px; font-weight: bold; color: #2563eb; margin-bottom: 5px; }
        .info-box { border: 1px solid #cbd5e1; background: #f8fafc; padding: 10px; border-radius: 4px; margin-bottom: 20px; width: 100%; display: table; }
        .info-col { display: table-cell; width: 50%; vertical-align: top; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #263A47; color: white; padding: 8px; text-align: left; font-size: 11px; text-transform: uppercase; }
        td { padding: 8px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totales-container { width: 100%; display: table; margin-top: 20px; }
        .totales-col-left { display: table-cell; width: 60%; vertical-align: bottom; }
        .totales-col-right { display: table-cell; width: 40%; }
        .totales-table { width: 100%; border-collapse: collapse; }
        .totales-table td { padding: 6px 8px; border: none; font-size: 12px; }
        .totales-table .total-final { font-size: 16px; font-weight: bold; background: #263A47; color: white; border-radius: 4px; }
        .estado-pago { display: inline-block; padding: 4px 10px; border-radius: 12px; font-weight: bold; font-size: 11px; text-transform: uppercase; }
        .estado-pagado { background: #dcfce7; color: #16a34a; }
        .estado-pendiente { background: #fee2e2; color: #dc2626; }
        .estado-parcial { background: #fef3c7; color: #d97706; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-col" style="width: 60%;">
            <div class="logo-text">AutoSys</div>
            <div class="company-info">
                RIF: J-00000000-0<br>
                Barquisimeto, Edo. Lara<br>
                Teléfono: 0424-xxx-xxxx
            </div>
        </div>
        <div class="header-col invoice-details" style="width: 40%;">
            <div class="invoice-title">FACTURA</div>
            <div><strong>N° {{ $factura->numero_factura }}</strong></div>
            <div style="color: #728495; margin-top: 5px;">Fecha: {{ $factura->created_at->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="info-box">
        <div class="info-col">
            <strong style="color:#263A47">Cliente / Propietario:</strong><br>
            {{ $factura->ordenServicio->vehiculo->cliente->nombre ?? 'Cliente Mostrador' }}<br>
            CI/RIF: {{ $factura->ordenServicio->vehiculo->cliente->cedula ?? 'N/A' }}<br>
            Tlf: {{ $factura->ordenServicio->vehiculo->cliente->telefono ?? 'N/A' }}
        </div>
        <div class="info-col">
            <strong style="color:#263A47">Datos del Vehículo:</strong><br>
            Placa: {{ $factura->ordenServicio->vehiculo->placa ?? 'N/A' }}<br>
            Vehículo: {{ $factura->ordenServicio->vehiculo->marca ?? '' }} {{ $factura->ordenServicio->vehiculo->modelo ?? '' }} ({{ $factura->ordenServicio->vehiculo->año ?? '' }})<br>
            Orden de Servicio: #00{{ $factura->orden_servicio_id }}
        </div>
    </div>

    @if(isset($factura->ordenServicio->servicios) && $factura->ordenServicio->servicios->count() > 0)
    <div style="font-weight: bold; margin-bottom: 5px; font-size: 12px; color: #263A47;">Mano de Obra y Servicios</div>
    <table>
        <thead>
            <tr>
                <th style="width: 80%;">Descripción</th>
                <th class="text-right" style="width: 20%;">Monto ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($factura->ordenServicio->servicios as $servicio)
            <tr>
                <td>{{ $servicio->descripcion }}</td>
                <td class="text-right">{{ number_format($servicio->pivot->precio_cobrado, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(isset($factura->ordenServicio->repuestos) && $factura->ordenServicio->repuestos->count() > 0)
    <div style="font-weight: bold; margin-bottom: 5px; font-size: 12px; color: #263A47;">Repuestos e Insumos</div>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">Cant</th>
                <th style="width: 50%;">Descripción</th>
                <th class="text-right" style="width: 20%;">P.U ($)</th>
                <th class="text-right" style="width: 20%;">Subtotal ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($factura->ordenServicio->repuestos as $repuesto)
            <tr>
                <td class="text-center">{{ $repuesto->pivot->cantidad }}</td>
                <td>{{ $repuesto->nombre }}</td>
                <td class="text-right">{{ number_format($repuesto->pivot->precio_unitario, 2) }}</td>
                <td class="text-right">{{ number_format($repuesto->pivot->cantidad * $repuesto->pivot->precio_unitario, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="totales-container">
        <div class="totales-col-left">
            <div style="margin-bottom: 15px;">
                <strong>Estado de Pago:</strong><br>
                @if($factura->estaPagada())
                    <span class="estado-pago estado-pagado">PAGADO COMPLETAMENTE</span>
                @elseif($factura->estado_pago === 'Parcial')
                    <span class="estado-pago estado-parcial">PAGO PARCIAL</span>
                @else
                    <span class="estado-pago estado-pendiente">PENDIENTE POR PAGAR</span>
                @endif
            </div>
            
            <div style="font-size: 10px; color: #728495;">
                * Equivalente en Bolívares calculado a la Tasa BCV actual.<br>
                Tasa referencial BCV: Bs. {{ number_format($tasaBcv ?? 0, 4) }}
            </div>
        </div>
        <div class="totales-col-right">
            <table class="totales-table">
                <tr>
                    <td class="text-right">Subtotal Repuestos:</td>
                    <td class="text-right" style="width: 80px;">$ {{ number_format($factura->subtotal_repuestos, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right">Subtotal Mano Obra:</td>
                    <td class="text-right">$ {{ number_format($factura->subtotal_mano_obra, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right">Exento (IVA 0%):</td>
                    <td class="text-right">$ 0.00</td>
                </tr>
                <tr class="total-final">
                    <td class="text-right" style="color: white;">TOTAL A PAGAR:</td>
                    <td class="text-right" style="color: white;">$ {{ number_format($factura->total_facturado, 2) }}</td>
                </tr>
                @if(!$factura->estaPagada())
                <tr>
                    <td class="text-right" style="color: #dc2626; font-weight: bold;">SALDO PENDIENTE:</td>
                    <td class="text-right" style="color: #dc2626; font-weight: bold;">$ {{ number_format($factura->saldo_pendiente, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td class="text-right" style="font-size: 10px; color: #728495;">Equivalente:</td>
                    <td class="text-right" style="font-size: 10px; color: #728495;">Bs. {{ number_format($factura->total_facturado * ($tasaBcv ?? 0), 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        Página 1 de 1 — Documento Oficial de AutoSys
    </div>
</body>
</html>
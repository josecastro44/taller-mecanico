<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #263A47; margin: 0; padding: 15px; border: 2px solid #263A47; border-radius: 8px; width: 250px; }
        .header { text-align: center; font-weight: bold; font-size: 14px; margin-bottom: 10px; color: #2563eb; text-transform: uppercase; }
        h2 { text-align: center; margin-top: 0; margin-bottom: 5px; font-size: 16px; text-transform: uppercase; line-height: 1.2; }
        .codigo { text-align: center; font-family: 'Courier New', Courier, monospace; font-weight: bold; font-size: 14px; background: #f1f5f9; padding: 6px; border-radius: 4px; margin-bottom: 15px; letter-spacing: 1px; border: 1px solid #e2e8f0; }
        .info { font-size: 12px; margin-bottom: 8px; line-height: 1.4; }
        .info strong { color: #64748b; font-size: 10px; text-transform: uppercase; display: block; margin-bottom: 2px; }
        .precio { text-align: center; font-size: 24px; font-weight: 800; margin-top: 20px; border-top: 2px dashed #cbd5e1; padding-top: 15px; color: #16a34a; }
        .footer { text-align: center; font-size: 9px; color: #94a3b8; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">AUTOSYS PARTS</div>
    <h2>{{ $repuesto->nombre }}</h2>
    <div class="codigo">{{ $repuesto->codigo }}</div>
    
    <div class="info">
        <strong>Compatibilidad Vehicular</strong>
        {{ trim($repuesto->marca_vehiculo . ' ' . $repuesto->modelo_vehiculo . ' ' . $repuesto->año_vehiculo) ?: 'Uso Universal' }}
    </div>
    
    <div class="info">
        <strong>Stock / Disponibilidad</strong>
        {{ $repuesto->stock }} und
    </div>
    
    <div class="precio">$ {{ number_format($repuesto->precio_venta, 2) }}</div>
    
    <div class="footer">Etiqueta de Almacén - Uso Interno</div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #263A47; margin: 0; padding: 10px; border: 2px solid #263A47; border-radius: 10px;}
        h2 { text-align: center; margin-bottom: 5px; font-size: 18px; text-transform: uppercase;}
        .codigo { text-align: center; font-family: monospace; font-size: 14px; background: #eee; padding: 5px; margin-bottom: 15px;}
        .info { font-size: 14px; margin-bottom: 8px; }
        .precio { text-align: center; font-size: 24px; font-weight: bold; margin-top: 20px; border-top: 1px dashed #ccc; padding-top: 10px;}
    </style>
</head>
<body>
    <div style="text-align: center; font-weight: bold; font-size: 12px; margin-bottom: 10px;">CTR - AUTO PARTS</div>
    <h2>{{ $repuesto->nombre }}</h2>
    <div class="codigo">{{ $repuesto->codigo }}</div>
    
    <div class="info"><strong>Marca:</strong> {{ $repuesto->marca }}</div>
    <div class="info"><strong>Compatibilidad:</strong> <br> {{ $repuesto->compatibilidad ?? 'Universal' }}</div>
    <div class="info"><strong>Stock Actual:</strong> {{ $repuesto->stock }} und</div>
    
    <div class="precio">PVP: $ {{ number_format($repuesto->precio_venta, 2) }}</div>
</body>
</html>
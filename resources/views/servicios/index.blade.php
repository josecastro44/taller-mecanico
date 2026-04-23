<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Servicios</title>
    <style>
        table { width: 80%; margin: 20px auto; border-collapse: collapse; font-family: sans-serif; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f4f4f4; }
        .precio-manual { color: orange; font-weight: bold; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Catálogo Maestro de Servicios</h1>
    
    <table>
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Categoría Sugerida</th>
                <th>Precio Base</th>
                <th>Tipo de Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($servicios as $servicio)
            <tr>
                <td>{{ $servicio->nombre }}</td>
                <td>{{ $servicio->categoria ?? 'General' }}</td>
                <td>${{ number_format($servicio->precio_base, 2) }}</td>
                <td>
                    @if($servicio->es_precio_manual)
                        <span class="precio-manual">Manual (Especial)</span>
                    @else
                        <span>Tabulado</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
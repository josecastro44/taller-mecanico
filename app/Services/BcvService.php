<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BcvService
{
    /**
     * Obtiene la tasa del BCV desde la API de pydolarve.
     * Cachea el resultado por 1 hora para evitar llamadas excesivas.
     *
     * @return array ['precio' => float, 'ultimo_cambio' => string]
     */
    public static function obtenerTasa()
    {
        return Cache::remember('tasa_bcv', 3600, function () {
            try {
                $response = Http::timeout(10)->get('https://ve.dolarapi.com/v1/dolares/oficial');

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['promedio'])) {
                        return [
                            'precio' => (float) $data['promedio'],
                            'ultimo_cambio' => $data['fechaActualizacion'] ?? now()->toDateTimeString(),
                            'fuente' => 'BCV (API)',
                            'error' => false
                        ];
                    }
                }

                return self::tasaPorDefecto('No se pudo obtener la tasa de la API');
            } catch (\Exception $e) {
                return self::tasaPorDefecto($e->getMessage());
            }
        });
    }

    /**
     * Fuerza una recarga de la tasa del BCV (borra caché y consulta de nuevo)
     */
    public static function refrescarTasa()
    {
        Cache::forget('tasa_bcv');
        return self::obtenerTasa();
    }

    /**
     * Convierte un monto de USD a Bolívares
     */
    public static function convertirABs($montoUsd)
    {
        $tasa = self::obtenerTasa();
        return round($montoUsd * $tasa['precio'], 2);
    }

    /**
     * Convierte un monto de Bolívares a USD
     */
    public static function convertirAUsd($montoBs)
    {
        $tasa = self::obtenerTasa();
        if ($tasa['precio'] > 0) {
            return round($montoBs / $tasa['precio'], 2);
        }
        return 0;
    }

    /**
     * Retorna una tasa por defecto cuando falla la API
     */
    private static function tasaPorDefecto($mensajeError = '')
    {
        return [
            'precio' => 0,
            'ultimo_cambio' => now()->toDateTimeString(),
            'fuente' => 'Sin conexión',
            'error' => true,
            'mensaje_error' => $mensajeError
        ];
    }
}

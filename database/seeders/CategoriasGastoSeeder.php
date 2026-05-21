<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaGasto;

class CategoriasGastoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Electricidad',       'icono' => 'ph-lightning', 'color' => '#eab308'],
            ['nombre' => 'Agua',               'icono' => 'ph-drop',     'color' => '#3b82f6'],
            ['nombre' => 'Gas',                'icono' => 'ph-flame',    'color' => '#ef4444'],
            ['nombre' => 'Internet / Teléfono','icono' => 'ph-wifi-high','color' => '#8b5cf6'],
            ['nombre' => 'Alquiler',           'icono' => 'ph-house',    'color' => '#10b981'],
            ['nombre' => 'Seguro',             'icono' => 'ph-shield-check', 'color' => '#0ea5e9'],
            ['nombre' => 'Limpieza',           'icono' => 'ph-broom',    'color' => '#f97316'],
            ['nombre' => 'Mantenimiento Local','icono' => 'ph-wrench',   'color' => '#64748b'],
            ['nombre' => 'Otro',               'icono' => 'ph-dots-three','color' => '#94a3b8'],
        ];

        foreach ($categorias as $cat) {
            CategoriaGasto::firstOrCreate(['nombre' => $cat['nombre']], $cat);
        }
    }
}

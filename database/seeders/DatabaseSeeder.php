<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // === 1. EL GERENTE (LLAVE MAESTRA) ===
        $gerenteUser = \App\Models\User::create([
            'name' => 'Gerente General',
            'email' => 'gerente@autosys.com',
            'password' => bcrypt('admin123'),
            'rol' => 'gerente',
        ]);
        \App\Models\Empleado::create([
            'nombre' => 'Jefe AutoSys',
            'cedula' => '11111111',
            'especialidad' => 'Gerencia',
            'user_id' => $gerenteUser->id,
        ]);

        // === 2. EL ADMINISTRADOR ===
        $adminUser = \App\Models\User::create([
            'name' => 'Administrador',
            'email' => 'admin@autosys.com',
            'password' => bcrypt('admin123'),
            'rol' => 'administrador',
        ]);
        \App\Models\Empleado::create([
            'nombre' => 'Admin AutoSys',
            'cedula' => '22222222',
            'especialidad' => 'Administración',
            'user_id' => $adminUser->id,
        ]);

        // === 3. EL MECÁNICO ===
        $mecanicoUser = \App\Models\User::create([
            'name' => 'Mecánico Principal',
            'email' => 'mecanico@autosys.com',
            'password' => bcrypt('admin123'),
            'rol' => 'mecanico',
        ]);
        \App\Models\Empleado::create([
            'nombre' => 'Técnico AutoSys',
            'cedula' => '33333333',
            'especialidad' => 'Mecánica General',
            'user_id' => $mecanicoUser->id,
        ]);
    }
}

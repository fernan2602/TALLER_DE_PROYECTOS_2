<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $usuarios = [];

        // 15 usuarios con id_rol = 4
        for ($i = 1; $i <= 15; $i++) {
            $usuarios[] = [
                'nombre' => 'Usuario' . $i,
                'email' => 'usuario' . $i . '@gmail.com',
                'fecha_nacimiento' => Carbon::now()->subYears(rand(18, 60))->subDays(rand(0, 365)),
                'contrasena' => Hash::make('password123'),
                'fecha_registro' => Carbon::now()->subDays(rand(0, 365)),
                'fecha_cese' => null,
                'id_rol' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 3 usuarios con id_rol = 3
        for ($i = 16; $i <= 18; $i++) {
            $usuarios[] = [
                'nombre' => 'Entrenador' . ($i - 15),
                'email' => 'entrenador' . ($i - 15) . '@gmail.com',
                'fecha_nacimiento' => Carbon::now()->subYears(rand(25, 45))->subDays(rand(0, 365)),
                'contrasena' => Hash::make('password123'),
                'fecha_registro' => Carbon::now()->subDays(rand(0, 365)),
                'fecha_cese' => null,
                'id_rol' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 2 usuarios con id_rol = 2
        for ($i = 19; $i <= 20; $i++) {
            $usuarios[] = [
                'nombre' => 'Nutriologo' . ($i - 18),
                'email' => 'nutriologo' . ($i - 18) . '@gmail.com',
                'fecha_nacimiento' => Carbon::now()->subYears(rand(28, 50))->subDays(rand(0, 365)),
                'contrasena' => Hash::make('password123'),
                'fecha_registro' => Carbon::now()->subDays(rand(0, 365)),
                'fecha_cese' => null,
                'id_rol' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('usuarios')->insert($usuarios);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreferenciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('preferencias')->insert([
            ['tipo' => 'dieta', 'descripcion' => 'Vegetariano', 'created_at' => now(), 'updated_at' => now()],
            ['tipo' => 'dieta', 'descripcion' => 'Vegano', 'created_at' => now(), 'updated_at' => now()],
            ['tipo' => 'dieta', 'descripcion' => 'Sin Gluten', 'created_at' => now(), 'updated_at' => now()],
            ['tipo' => 'dieta', 'descripcion' => 'Bajo en Azúcar', 'created_at' => now(), 'updated_at' => now()],
            ['tipo' => 'alergia', 'descripcion' => 'Sin Lactosa', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

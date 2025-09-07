<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromedioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $datos = [
            ['16-19', 'masculino', 12.35],
            ['16-19', 'femenino', 21.57],
            ['20-24', 'masculino', 12.12],
            ['20-24', 'femenino', 21.87],
            ['25-29', 'femenino', 12.33],
            ['25-29', 'masculino', 21.56],
            ['30-39', 'masculino', 12.31],
            ['30-39', 'femenino', 22.08],
            ['40-49', 'masculino', 12.31],
            ['40-49', 'femenino', 22.61],
            ['50-59', 'masculino', 12.20],
            ['50-59', 'femenino', 24.21],
            ['60-69', 'masculino', 12.29],
            ['60-69', 'femenino', 18.41],
        ];

        foreach ($datos as $dato) {
            DB::table('promedio')->insert([
                'edad'        => $dato[0],
                'genero'            => $dato[1],
                'pliegue_tricipital'=> $dato[2],
                'fuerza'=> (null),
            ]);
    }
}
}

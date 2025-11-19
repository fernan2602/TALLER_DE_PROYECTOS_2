<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CambioSeeder extends Seeder
{
    public function run()
    {
        // Obtener solo usuarios con id_rol = 4
        $usuarios = DB::table('usuarios')
            ->where('id_rol', 4)
            ->get();

        $medidas = [];

        foreach ($usuarios as $usuario) {
            // Calcular edad basada en fecha_nacimiento
            $fechaNacimiento = Carbon::parse($usuario->fecha_nacimiento);
            $edad = $fechaNacimiento->age;

            // Generar medidas realistas según género y edad
            $genero = $this->generarGeneroAleatorio();
            
            $medidas[] = [
                'id_usuario' => $usuario->id,
                'peso' => $this->generarPeso($genero, $edad),
                'talla' => $this->generarTalla($genero),
                'edad' => $edad,
                'genero' => $genero,
                'circunferencia_brazo' => $this->generarCircunferenciaBrazo($genero),
                'circunferencia_antebrazo' => $this->generarCircunferenciaAntebrazo($genero),
                'circunferencia_cintura' => $this->generarCircunferenciaCintura($genero),
                'circunferencia_caderas' => $this->generarCircunferenciaCaderas($genero),
                'circunferencia_muslos' => $this->generarCircunferenciaMuslos($genero),
                'circunferencia_pantorrilla' => $this->generarCircunferenciaPantorrilla($genero),
                'circunferencia_cuello' => $this->generarCircunferenciaCuello($genero),
                'fecha_registro' => Carbon::now()->subDays(rand(0, 30)),
                'estado_fisico' => 2 ,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('medidas')->insert($medidas);

        $this->command->info('✅ ' . count($medidas) . ' medidas creadas para usuarios con rol 4');
    }

    private function generarGeneroAleatorio()
    {
        return rand(0, 1) ? 'M' : 'F';
    }

    private function generarPeso($genero, $edad)
    {
        // Peso en kg según género y edad
        if ($genero === 'M') {
            // Hombres: 65-95 kg
            return rand(6500, 9500) / 100;
        } else {
            // Mujeres: 50-80 kg
            return rand(5000, 8000) / 100;
        }
    }

    private function generarTalla($genero)
    {
        // Talla en metros según género
        if ($genero === 'M') {
            // Hombres: 1.65 - 1.90 m
            return rand(165, 190) / 100;
        } else {
            // Mujeres: 1.50 - 1.75 m
            return rand(150, 175) / 100;
        }
    }

    private function generarCircunferenciaBrazo($genero)
    {
        if ($genero === 'M') {
            return rand(280, 450) / 100; // 28-45 cm
        } else {
            return rand(250, 380) / 100; // 25-38 cm
        }
    }

    private function generarCircunferenciaAntebrazo($genero)
    {
        if ($genero === 'M') {
            return rand(220, 350) / 100; // 22-35 cm
        } else {
            return rand(200, 300) / 100; // 20-30 cm
        }
    }

    private function generarCircunferenciaCintura($genero)
    {
        if ($genero === 'M') {
            return rand(750, 1100) / 100; // 75-110 cm
        } else {
            return rand(650, 950) / 100; // 65-95 cm
        }
    }

    private function generarCircunferenciaCaderas($genero)
    {
        if ($genero === 'M') {
            return rand(850, 1150) / 100; // 85-115 cm
        } else {
            return rand(900, 1200) / 100; // 90-120 cm
        }
    }

    private function generarCircunferenciaMuslos($genero)
    {
        if ($genero === 'M') {
            return rand(450, 700) / 100; // 45-70 cm
        } else {
            return rand(400, 650) / 100; // 40-65 cm
        }
    }

    private function generarCircunferenciaPantorrilla($genero)
    {
        if ($genero === 'M') {
            return rand(320, 500) / 100; // 32-50 cm
        } else {
            return rand(300, 450) / 100; // 30-45 cm
        }
    }

    private function generarCircunferenciaCuello($genero)
    {
        if ($genero === 'M') {
            return rand(350, 450) / 100; // 35-45 cm
        } else {
            return rand(300, 380) / 100; // 30-38 cm
        }
    }
}
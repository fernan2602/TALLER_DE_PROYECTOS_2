<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Medida;
use App\Http\Controllers\MedidaController;

class MedidaSeeder extends Seeder
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
                'fecha_registro' => Carbon::now(),
                'estado_fisico' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insertar todas las medidas
        DB::table('medidas')->insert($medidas);

        // CALCULAR PROGRESO PARA CADA MEDIDA CREADA
        $this->calcularProgresoParaTodasLasMedidas();

        $this->command->info('✅ ' . count($medidas) . ' medidas creadas para usuarios con rol 4');
        $this->command->info('✅ Progresos calculados automáticamente para todas las medidas');
    }

    /**
     * Calcular progreso para todas las medidas recién creadas
     */
    private function calcularProgresoParaTodasLasMedidas()
    {
        // Obtener todas las medidas recién creadas
        $medidas = Medida::all();
        
        // Instanciar el controlador para usar el método de cálculo
        $medidaController = new MedidaController();

        foreach ($medidas as $medida) {
            try {
                // Usar el mismo método que usas en el controlador
                $medidaController->calcularYGuardarProgreso($medida);
                $this->command->info("✅ Progreso calculado para medida ID: {$medida->id}");
            } catch (\Exception $e) {
                $this->command->error("❌ Error calculando progreso para medida ID: {$medida->id} - " . $e->getMessage());
            }
        }
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
        // Talla en cm según género
        if ($genero === 'M') {
            // Hombres: 165 - 190 cm
            return rand(165, 190);
        } else {
            // Mujeres: 150 - 175 cm
            return rand(150, 175);
        }
    }

    private function generarCircunferenciaBrazo($genero)
    {
        if ($genero === 'M') {
            return rand(28, 45); // 28-45 cm (hombres)
        } else {
            return rand(25, 38); // 25-38 cm (mujeres)
        }
    }

    private function generarCircunferenciaAntebrazo($genero)
    {
        if ($genero === 'M') {
            return rand(22, 35); // 22-35 cm (hombres)
        } else {
            return rand(20, 30); // 20-30 cm (mujeres)
        }
    }

    private function generarCircunferenciaCintura($genero)
    {
        if ($genero === 'M') {
            return rand(75, 110); // 75-110 cm (hombres)
        } else {
            return rand(65, 95); // 65-95 cm (mujeres)
        }
    }

    private function generarCircunferenciaCaderas($genero)
    {
        if ($genero === 'M') {
            return rand(85, 115); // 85-115 cm (hombres)
        } else {
            return rand(90, 120); // 90-120 cm (mujeres)
        }
    }

    private function generarCircunferenciaMuslos($genero)
    {
        if ($genero === 'M') {
            return rand(45, 70); // 45-70 cm (hombres)
        } else {
            return rand(40, 65); // 40-65 cm (mujeres)
        }
    }

    private function generarCircunferenciaPantorrilla($genero)
    {
        if ($genero === 'M') {
            return rand(32, 50); // 32-50 cm (hombres)
        } else {
            return rand(30, 45); // 30-45 cm (mujeres)
        }
    }

    private function generarCircunferenciaCuello($genero)
    {
        if ($genero === 'M') {
            return rand(35, 45); // 35-45 cm (hombres)
        } else {
            return rand(30, 38); // 30-38 cm (mujeres)
        }
    }
}
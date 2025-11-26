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
        $meses = 10; // Generar 10 medidas (enero a octubre)

        foreach ($usuarios as $usuario) {
            // Calcular edad basada en fecha_nacimiento (para la primera medida)
            $fechaNacimiento = Carbon::parse($usuario->fecha_nacimiento);
            $edadBase = $fechaNacimiento->age;

            // Generar medidas realistas según género y edad
            $genero = $this->generarGeneroAleatorio();

            for ($i = 0; $i < $meses; $i++) {
                // Calcular fecha para cada mes (comenzando desde enero del año actual)
                $fechaMedida = Carbon::create(null, 1, 1)->addMonths($i); // Enero + i meses
                
                // Ajustar la edad según el mes
                $edad = $edadBase;
                if ($fechaMedida->month < $fechaNacimiento->month || 
                    ($fechaMedida->month == $fechaNacimiento->month && $fechaMedida->day < $fechaNacimiento->day)) {
                    $edad = $edadBase - 1;
                }

                // Generar medidas con pequeñas variaciones mensuales
                $pesoBase = $this->generarPeso($genero, $edad);
                $tallaBase = $this->generarTalla($genero);

                $medidas[] = [
                    'id_usuario' => $usuario->id,
                    'peso' => $this->generarPesoConProgreso($pesoBase, $i, $meses),
                    'talla' => $tallaBase, // La talla generalmente no cambia
                    'edad' => $edad,
                    'genero' => $genero,
                    'circunferencia_brazo' => $this->generarCircunferenciaConProgreso($this->generarCircunferenciaBrazo($genero), $i, $meses),
                    'circunferencia_antebrazo' => $this->generarCircunferenciaConProgreso($this->generarCircunferenciaAntebrazo($genero), $i, $meses),
                    'circunferencia_cintura' => $this->generarCircunferenciaConProgreso($this->generarCircunferenciaCintura($genero), $i, $meses),
                    'circunferencia_caderas' => $this->generarCircunferenciaConProgreso($this->generarCircunferenciaCaderas($genero), $i, $meses),
                    'circunferencia_muslos' => $this->generarCircunferenciaConProgreso($this->generarCircunferenciaMuslos($genero), $i, $meses),
                    'circunferencia_pantorrilla' => $this->generarCircunferenciaConProgreso($this->generarCircunferenciaPantorrilla($genero), $i, $meses),
                    'circunferencia_cuello' => $this->generarCircunferenciaConProgreso($this->generarCircunferenciaCuello($genero), $i, $meses),
                    'fecha_registro' => $fechaMedida,
                    'estado_fisico' => 1,
                    'created_at' => $fechaMedida,
                    'updated_at' => $fechaMedida,
                ];
            }
        }

        // Insertar todas las medidas
        DB::table('medidas')->insert($medidas);

        // CALCULAR PROGRESO PARA CADA MEDIDA CREADA
        $this->calcularProgresoParaTodasLasMedidas();

        $this->command->info('✅ ' . count($medidas) . ' medidas creadas para usuarios con rol 4 (10 por usuario)');
        $this->command->info('✅ Progresos calculados automáticamente para todas las medidas');
    }

    /**
     * Generar peso con progreso realista (simula mejora física)
     */
    private function generarPesoConProgreso($pesoBase, $mesActual, $totalMeses)
    {
        // Simular progreso: pérdida de peso gradual o ganancia muscular
        $progreso = ($totalMeses - $mesActual) / $totalMeses; // De 1 a 0
        
        // Variación de ±5% del peso base
        $variacion = $pesoBase * 0.05 * $progreso;
        
        // Tendencia: generalmente se pierde peso o se redistribuye
        $nuevoPeso = $pesoBase - $variacion;
        
        return max($nuevoPeso, $pesoBase * 0.8); // No menos del 80% del peso original
    }

    /**
     * Generar circunferencia con progreso realista
     */
    private function generarCircunferenciaConProgreso($circunferenciaBase, $mesActual, $totalMeses)
    {
        // Simular cambios realistas en medidas corporales
        $progreso = ($totalMeses - $mesActual) / $totalMeses;
        
        // Variación de ±8% para simular cambios por ejercicio
        $variacion = $circunferenciaBase * 0.08 * $progreso;
        
        // Algunas medidas disminuyen (cintura) y otras aumentan (brazo, muslo)
        $esMedidaQueAumenta = in_array($circunferenciaBase, [
            $this->generarCircunferenciaBrazo('M'),
            $this->generarCircunferenciaBrazo('F'),
            $this->generarCircunferenciaMuslos('M'),
            $this->generarCircunferenciaMuslos('F')
        ]);

        if ($esMedidaQueAumenta) {
            $nuevaCircunferencia = $circunferenciaBase + $variacion;
            return min($nuevaCircunferencia, $circunferenciaBase * 1.15); // No más del 115%
        } else {
            $nuevaCircunferencia = $circunferenciaBase - $variacion;
            return max($nuevaCircunferencia, $circunferenciaBase * 0.85); // No menos del 85%
        }
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
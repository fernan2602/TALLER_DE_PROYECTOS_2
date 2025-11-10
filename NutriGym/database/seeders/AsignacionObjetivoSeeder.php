<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ObjetivoController; // Ajusta el namespace según tu controlador

class AsignacionObjetivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Instanciar el controlador
        $objetivoController = new ObjetivoController();

        $this->command->info('🎯 Iniciando asignación de objetivos a usuarios...');

        // Obtener usuarios con rol 4
        $usuarios = DB::table('usuarios')
            ->where('id_rol', 4)
            ->get();

        // Obtener todos los objetivos disponibles
        $objetivos = DB::table('objetivos')->get();

        if ($usuarios->isEmpty()) {
            $this->command->error('❌ No hay usuarios con rol 4 para asignar objetivos.');
            return;
        }

        if ($objetivos->isEmpty()) {
            $this->command->error('❌ No hay objetivos disponibles.');
            return;
        }

        $this->command->info("📊 Encontrados {$usuarios->count()} usuarios con rol 4");
        $this->command->info("🎯 Encontrados {$objetivos->count()} objetivos disponibles");

        $asignacionesExitosas = 0;
        $asignacionesFallidas = 0;

        foreach ($usuarios as $usuario) {
            // Asignar entre 1 y 3 objetivos aleatorios por usuario
            $cantidadObjetivos = rand(1, 3);
            
            // Mezclar objetivos y tomar los primeros N
            $objetivosAleatorios = $objetivos->shuffle()->take($cantidadObjetivos);

            foreach ($objetivosAleatorios as $objetivo) {
                // Usar la función del controlador
                $resultado = $objetivoController->guardarObjetivo(
                    $usuario->id, 
                    $objetivo->id,
                    now()->subDays(rand(0, 30))->toDateString() // Fecha aleatoria en los últimos 30 días
                );

                if ($resultado['success']) {
                    $this->command->info("✅ Objetivo '{$objetivo->nombre}' asignado a usuario ID: {$usuario->id}");
                    $asignacionesExitosas++;
                } else {
                    $this->command->warn("⚠️ {$resultado['message']} - Usuario: {$usuario->id}, Objetivo: {$objetivo->nombre}");
                    $asignacionesFallidas++;
                }
            }
        }

        $this->command->info("\n📊 Resumen de asignación de objetivos:");
        $this->command->info("✅ Asignaciones exitosas: {$asignacionesExitosas}");
        $this->command->info("❌ Asignaciones fallidas: {$asignacionesFallidas}");
        $this->command->info("👥 Total de usuarios procesados: " . $usuarios->count());
    }
}
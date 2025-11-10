<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PreferenciaController;

class AsignacionPreferenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Instanciar el controlador
        $preferenciaController = new PreferenciaController();

        $this->command->info('🎯 Iniciando asignación de preferencias a usuarios...');

        // Obtener usuarios con rol 4
        $usuarios = DB::table('usuarios')
            ->where('id_rol', 4)
            ->get();

        // Obtener todas las preferencias disponibles
        $preferencias = DB::table('preferencias')->get();

        if ($usuarios->isEmpty()) {
            $this->command->error('❌ No hay usuarios con rol 4 para asignar preferencias.');
            return;
        }

        if ($preferencias->isEmpty()) {
            $this->command->error('❌ No hay preferencias disponibles.');
            return;
        }

        $this->command->info("📊 Encontrados {$usuarios->count()} usuarios con rol 4");
        $this->command->info("🎯 Encontradas {$preferencias->count()} preferencias disponibles");

        $asignacionesExitosas = 0;
        $asignacionesFallidas = 0;

        foreach ($usuarios as $usuario) {
            // Asignar entre 1 y 3 preferencias aleatorias por usuario
            $cantidadPreferencias = rand(1, 3);
            
            // Mezclar preferencias y tomar las primeras N
            $preferenciasAleatorias = $preferencias->shuffle()->take($cantidadPreferencias);

            foreach ($preferenciasAleatorias as $preferencia) {
                // Usar la función del controlador
                $resultado = $preferenciaController->guardar_preferencia(
                    $usuario->id, 
                    $preferencia->id
                );

                if ($resultado['success']) {
                    $this->command->info("✅ {$resultado['message']} - Usuario ID: {$usuario->id}");
                    $asignacionesExitosas++;
                } else {
                    $this->command->warn("⚠️ {$resultado['message']}");
                    $asignacionesFallidas++;
                }
            }
        }

        $this->command->info("\n📊 Resumen de asignación de preferencias:");
        $this->command->info("✅ Asignaciones exitosas: {$asignacionesExitosas}");
        $this->command->info("❌ Asignaciones fallidas: {$asignacionesFallidas}");
        $this->command->info("👥 Total de usuarios procesados: " . $usuarios->count());
    }
}
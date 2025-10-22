<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ObjetivoController extends Controller
{
    public function select()
    {
        try {
            // Verificar si la tabla existe antes de consultar
            if (!Schema::hasTable('objetivos')) {
                throw new \Exception('Tabla objetivos no existe');
            }

            // Lógica de objetivos
            $objetivos = DB::table('objetivos')->get();
            
            // Lógica de preferencias - con verificación
            $preferencias = collect([]);
            if (Schema::hasTable('preferencias')) {
                $preferencias = DB::table('preferencias')->get();
            }
            
            return view('usuario.preferencia', compact('objetivos', 'preferencias'));
            
        } catch (\Exception $e) {
            // Datos de ejemplo como fallback
            $objetivos = collect([
                (object) [
                    'id' => 1,
                    'nombre' => 'Perder Peso',
                    'descripcion' => 'Reducir peso corporal de forma saludable',
                    'color' => 'red',
                    'categoria' => 'Salud',
                    'icono' => '🎯',
                    'meta' => 'Bajar de peso'
                ],
                (object) [
                    'id' => 2,
                    'nombre' => 'Ganar Músculo',
                    'descripcion' => 'Aumentar masa muscular y fuerza',
                    'color' => 'blue',
                    'categoria' => 'Fitness',
                    'icono' => '💪',
                    'meta' => 'Aumentar masa muscular'
                ]
            ]);
            
            $preferencias = collect([]);
            
            return view('usuario.preferencia', compact('objetivos', 'preferencias'));
        }
    }
        


    public function guardarObjetivo(Request $request)
    {
        // Validar el objetivo_id
        $request->validate([
            'objetivo_id' => 'required|integer|exists:objetivos,id'
        ]);

        $usuarioId = Auth::id();

        // Verificar si ya existe una asignación
        $asignacionExistente = DB::table('asignacion_objetivo')
            ->where('id_usuario', $usuarioId)
            ->where('id_objetivo',$request->objetivo_id)
            ->first();

        if ($asignacionExistente) {
            // Actualizar objetivo existente
            DB::table('asignacion_objetivo')
                ->where('id_usuario', $usuarioId)
                ->where('id_objetivo',$request->objetivo_id)
                ->update([
                    'updated_at' => now(),
                    'fecha_asignacion' => now()
                ]);
        } else {
            // Insertar nueva asignación
            DB::table('asignacion_objetivo')->insert([
                'id_usuario' => $usuarioId,
                'id_objetivo' => $request->objetivo_id,
                'fecha_asignacion' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Redirigir con mensaje de éxito
        return redirect()->route('preferencia')->with('success', 'Objetivo seleccionado correctamente');
    }

}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ObjetivoController extends Controller
{
    // En tu ObjetivoController o similar
public function guardarObjetivo($usuarioId, $objetivoId)
{
    try {
        // Verificar si ya existe la asignación
        $asignacionExistente = DB::table('asignacion_objetivo')
            ->where('id_usuario', $usuarioId)
            ->where('id_objetivo', $objetivoId)
            ->first();

        if ($asignacionExistente) {
            return [
                'success' => false,
                'message' => 'El objetivo ya está asignado a este usuario'
            ];
        }

        // Crear nueva asignación
        $asignacionId = DB::table('asignacion_objetivo')->insertGetId([
            'id_usuario' => $usuarioId,
            'id_objetivo' => $objetivoId,
            'fecha_asignacion' => now(),
            'estado' => 'activo',
            'calificacion' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return [
            'success' => true,
            'message' => 'Objetivo asignado correctamente',
            'asignacion_id' => $asignacionId
        ];

    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => 'Error asignando objetivo: ' . $e->getMessage()
        ];
    }
}
}
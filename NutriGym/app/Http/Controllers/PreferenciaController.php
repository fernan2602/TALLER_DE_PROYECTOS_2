<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreferenciaController extends Controller
{
    /**
     * Asignar preferencia a un usuario
     */
    public function guardar_preferencia($usuarioId, $preferenciaId)
    {
        try {
            // Verificar si ya existe la asignación
            $asignacionExistente = DB::table('asignacion_preferencia')
                ->where('id_usuario', $usuarioId)
                ->where('id_preferencia', $preferenciaId)
                ->first();

            if ($asignacionExistente) {
                return [
                    'success' => false,
                    'message' => 'La preferencia ya está asignada a este usuario'
                ];
            }

            // Crear nueva asignación
            $asignacionId = DB::table('asignacion_preferencia')->insertGetId([
                'id_usuario' => $usuarioId,
                'id_preferencia' => $preferenciaId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Obtener nombre de la preferencia para el mensaje
            $preferencia = DB::table('preferencias')->find($preferenciaId);

            return [
                'success' => true,
                'message' => "Preferencia '{$preferencia->descripcion}' asignada correctamente",
                'asignacion_id' => $asignacionId
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error asignando preferencia: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtener preferencias de un usuario
     */
    public function obtener_preferencias_usuario($usuarioId)
    {
        try {
            $preferencias = DB::table('asignacion_preferencia')
                ->join('preferencias', 'asignacion_preferencia.id_preferencia', '=', 'preferencias.id')
                ->where('asignacion_preferencia.id_usuario', $usuarioId)
                ->select('preferencias.*')
                ->get();

            return [
                'success' => true,
                'preferencias' => $preferencias,
                'total' => $preferencias->count()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error obteniendo preferencias: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Eliminar preferencia de un usuario
     */
    public function eliminar_preferencia($asignacionId)
    {
        try {
            $eliminado = DB::table('asignacion_preferencia')
                ->where('id_asignacion', $asignacionId)
                ->delete();

            if ($eliminado) {
                return [
                    'success' => true,
                    'message' => 'Preferencia eliminada correctamente'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No se encontró la asignación de preferencia'
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error eliminando preferencia: ' . $e->getMessage()
            ];
        }
    }
}
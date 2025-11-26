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

    public function guardarPreferencias()
    {
        try {
            $usuarioId = auth()->id();
            $preferenciasSeleccionados = request()->input('preferencias', []);

            if (empty($preferenciasSeleccionados)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se seleccionaron preferencias'
                ]);
            }

            $resultados = [];
            $asignacionesExitosas = 0;

            foreach ($preferenciasSeleccionados as $preferenciaId) {
                $resultado = $this->guardarPreferencia($usuarioId, $preferenciaId);
                
                $resultados[] = [
                    'preferencia_id' => $preferenciaId,
                    'success' => $resultado['success'],
                    'message' => $resultado['message']
                ];
                
                if ($resultado['success']) {
                    $asignacionesExitosas++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Se asignaron {$asignacionesExitosas} preferencia(s) correctamente",
                'total_asignados' => $asignacionesExitosas,
                'detalles' => $resultados
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    // Tu función existente (se mantiene igual sin cambios)
    public function guardarPreferencia($usuarioId, $preferenciaId)
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
                'fecha' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return [
                'success' => true,
                'message' => 'Preferencia asignada correctamente',
                'asignacion_id' => $asignacionId
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error asignando preferencia: ' . $e->getMessage()
            ];
        }
    }

    public function index(Request $request)
    {
        try {
            // Verificar autenticación
            if (!auth()->check()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Usuario no autenticado'
                    ], 401);
                }
                return redirect()->route('login');
            }

            // Obtener solo las preferencias NO seleccionadas por el usuario
            $preferencias = DB::table('preferencias')
                ->select('preferencias.*')
                ->leftJoin('asignacion_preferencia', function($join) {
                    $join->on('preferencias.id', '=', 'asignacion_preferencia.id_preferencia')
                        ->where('asignacion_preferencia.id_usuario', auth()->id());
                })
                ->whereNull('asignacion_preferencia.id_preferencia') // Solo los que NO están en preferencias_usuario
                ->orderBy('preferencias.created_at', 'desc')
                ->get();
            
            // Contar preferencias del usuario actual
            $totalPreferenciasUsuario = DB::table('asignacion_preferencia')
                ->where('id_usuario', auth()->id())
                ->count();

            // Si es petición AJAX, retorna JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'preferencias' => $preferencias,
                    'total_preferencias_usuario' => $totalPreferenciasUsuario
                ]);
            }
            
            // Si es petición normal, retorna la vista
            return view('ui_dashboard.usuario', compact('preferencias', 'totalPreferenciasUsuario'));
            
        } catch (\Exception $e) {
            \Log::error('Error en PreferenciaController: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile());
            \Log::error('Line: ' . $e->getLine());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error interno del servidor: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error al cargar los preferencias');
        }
    }

    public function preferenciasSeleccionados()
    {
        try {
            $usuarioId = auth()->id();
            
            $preferencias = DB::table('asignacion_preferencia')
                ->join('preferencias', 'asignacion_preferencia.id_preferencia', '=', 'preferencias.id')
                ->select(
                    'preferencias.id',
                    'preferencias.tipo', 
                    'preferencias.descripcion',
                    'asignacion_preferencia.fecha'
                )
                ->where('asignacion_preferencia.id_usuario', $usuarioId)
                ->orderBy('asignacion_preferencia.fecha', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'preferencias' => $preferencias
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar preferencias: ' . $e->getMessage()
            ], 500);
        }
    }

    public function eliminarPreferencia($id)
    {
        try {
            $usuarioId = auth()->id();
            
            $eliminado = DB::table('asignacion_preferencia')
                ->where('id_preferencia', $id)
                ->where('id_usuario', $usuarioId)
                ->delete();

            if ($eliminado) {
                return response()->json([
                    'success' => true,
                    'message' => 'Preferencia eliminada correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo eliminar la preferencia'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error eliminando preferencia: ' . $e->getMessage()
            ], 500);
        }
    }
    
}
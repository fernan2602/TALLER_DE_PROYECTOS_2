<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ObjetivoController extends Controller
{
    // En tu ObjetivoController o similar
    public function guardarObjetivos()
    {
        try {
            $usuarioId = auth()->id();
            $objetivosSeleccionados = request()->input('objetivos', []);

            if (empty($objetivosSeleccionados)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se seleccionaron objetivos'
                ]);
            }

            $resultados = [];
            $asignacionesExitosas = 0;

            foreach ($objetivosSeleccionados as $objetivoId) {
                $resultado = $this->guardarObjetivo($usuarioId, $objetivoId);
                
                $resultados[] = [
                    'objetivo_id' => $objetivoId,
                    'success' => $resultado['success'],
                    'message' => $resultado['message']
                ];
                
                if ($resultado['success']) {
                    $asignacionesExitosas++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Se asignaron {$asignacionesExitosas} objetivo(s) correctamente",
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

            // Obtener solo los objetivos NO seleccionados por el usuario
            $objetivos = DB::table('objetivos')
                ->select('objetivos.*')
                ->leftJoin('asignacion_objetivo', function($join) {
                    $join->on('objetivos.id', '=', 'asignacion_objetivo.id_objetivo')
                        ->where('asignacion_objetivo.id_usuario', auth()->id());
                })
                ->whereNull('asignacion_objetivo.id_objetivo') // Solo los que NO están en objetivos_usuario
                ->orderBy('objetivos.created_at', 'desc')
                ->get();
            
            // Contar objetivos del usuario actual
            $totalObjetivosUsuario = DB::table('asignacion_objetivo')
                ->where('id_usuario', auth()->id())
                ->count();

            // Si es petición AJAX, retorna JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'objetivos' => $objetivos,
                    'total_objetivos_usuario' => $totalObjetivosUsuario
                ]);
            }
            
            // Si es petición normal, retorna la vista
            return view('ui_dashboard.usuario', compact('objetivos', 'totalObjetivosUsuario'));
            
        } catch (\Exception $e) {
            \Log::error('Error en ObjetivoController: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile());
            \Log::error('Line: ' . $e->getLine());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error interno del servidor: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error al cargar los objetivos');
        }
    }

    public function objetivosSeleccionados()
    {
        try {
            $usuarioId = auth()->id();
            
            $objetivos = DB::table('asignacion_objetivo')
                ->join('objetivos', 'asignacion_objetivo.id_objetivo', '=', 'objetivos.id')
                ->select(
                    'objetivos.id',
                    'objetivos.nombre', 
                    'objetivos.descripcion',
                    'asignacion_objetivo.fecha_asignacion',
                    'asignacion_objetivo.estado',
                    'asignacion_objetivo.calificacion'
                )
                ->where('asignacion_objetivo.id_usuario', $usuarioId)
                ->orderBy('asignacion_objetivo.fecha_asignacion', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'objetivos' => $objetivos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar objetivos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function eliminarObjetivo($id)
    {
        try {
            $usuarioId = auth()->id();
            
            $eliminado = DB::table('asignacion_objetivo')
                ->where('id_objetivo', $id)
                ->where('id_usuario', $usuarioId)
                ->delete();

            if ($eliminado) {
                return response()->json([
                    'success' => true,
                    'message' => 'Objetivo eliminado correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo eliminar el objetivo'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error eliminando objetivo: ' . $e->getMessage()
            ], 500);
        }
    }
}
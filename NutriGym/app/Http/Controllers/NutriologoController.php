<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NutriologoController extends Controller
{
    //
    public function index()
    {
        // Listar usuarios
        // Select con join para obtener información del rol
        $usuarios = DB::table('usuarios')
            ->join('roles', 'usuarios.id_rol', '=', 'roles.id')
            ->where('usuarios.id_rol', '=', 4)
            ->select(
                'usuarios.*',
                'roles.nombre_rol as nombre_rol'
            )
            ->get();
        return view('ui_dashboard.nutriologo', compact('usuarios'));
    }

    public function obtenerObjetivosUsuario($usuarioId)
    {
        try {
            $objetivos = DB::table('asignacion_objetivo')
                ->join('objetivos', 'asignacion_objetivo.id_objetivo', '=', 'objetivos.id')
                ->where('asignacion_objetivo.id_usuario', $usuarioId)
                ->select(
                    'objetivos.id',
                    'objetivos.nombre',
                    'objetivos.descripcion',
                    'asignacion_objetivo.estado',
                    'asignacion_objetivo.fecha_asignacion',
                    'asignacion_objetivo.calificacion'
                )
                ->get();

            return response()->json([
                'success' => true,
                'objetivos' => $objetivos,
                'total' => $objetivos->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener objetivos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerPreferenciasUsuario($usuarioId)
    {
        try {
            $preferencias = DB::table('asignacion_preferencia')
                ->join('preferencias', 'asignacion_preferencia.id_preferencia', '=', 'preferencias.id')
                ->where('asignacion_preferencia.id_usuario', $usuarioId)
                ->select(
                    'preferencias.id',
                    'preferencias.tipo',
                    'preferencias.descripcion',
                    'asignacion_preferencia.created_at as fecha_asignacion'
                )
                ->get();

            return response()->json([
                'success' => true,
                'preferencias' => $preferencias,
                'total' => $preferencias->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener preferencias: ' . $e->getMessage()
            ], 500);
        }
    }


}

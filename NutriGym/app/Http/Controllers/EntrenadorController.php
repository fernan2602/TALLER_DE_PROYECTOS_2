<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Medida;


class EntrenadorController extends Controller
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
        return view('ui_dashboard.entrenador', compact('usuarios'));
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

     public function obtenerMedidasUsuario($usuarioId)
    {
        try {
            $medidas = Medida::where('id_usuario', $usuarioId)
                ->orderBy('fecha_registro', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'medidas' => $medidas,
                'total' => $medidas->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener medidas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener la última medida de un usuario
     */
    public function obtenerUltimaMedida($usuarioId)
    {
        try {
            $medida = Medida::where('id_usuario', $usuarioId)
                ->orderBy('fecha_registro', 'desc')
                ->first();

            return response()->json([
                'success' => true,
                'medida' => $medida
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la medida: ' . $e->getMessage()
            ], 500);
        }
    }



}

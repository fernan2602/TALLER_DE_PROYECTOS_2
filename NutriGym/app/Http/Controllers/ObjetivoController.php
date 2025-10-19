<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObjetivoController extends Controller
{
    public function select()
    {
        // Lógica de objetivos
        $objetivos = DB::table('objetivos')->get();
        
        // Lógica de preferencias (si es necesaria)
        $preferencias = DB::table('preferencias')->get();
        
        return view('usuario.preferencia', compact('objetivos', 'preferencias'));
    }


    public function index()
    {
        $objetivosUsuario = DB::table('asignacion_objetivo')
            ->join('objetivos', 'asignacion_objetivo.id_objetivo', '=', 'objetivos.id')
            ->where('asignacion_objetivo.id_usuario', auth()->id())
            ->select('objetivos.*', 'asignacion_objetivos.created_at as fecha_asignacion')
            ->get();

        return view('tu-vista', compact('objetivosUsuario'));
    }

    public function seleccionar(Request $request)
    {
        $objetivoId = $request->objetivo_id;
        $usuarioId = auth()->id();

        // Lógica para manejar la selección del objetivo
        // Por ejemplo, marcar como objetivo activo

        return response()->json(['success' => true, 'message' => 'Objetivo seleccionado']);
    }
}
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Listar usuarios
        // Select con join para obtener información del rol
        $usuarios = DB::table('usuarios')
            ->join('roles', 'usuarios.id_rol', '=', 'roles.id')
            ->where('usuarios.id_rol', '!=', 1)
            ->select(
                'usuarios.*',
                'roles.nombre_rol as nombre_rol'
            )
            ->get();
        return view('ui_dashboard.admin', compact('usuarios'));
    }
}

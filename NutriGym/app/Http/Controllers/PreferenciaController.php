<?php

namespace App\Http\Controllers;

use App\Models\Preferencia;


class PreferenciaController extends Controller
{
    public function index()
    {
        $preferencias = Preferencia::orderBy('id', 'asc')
            ->get();
            
        return view('usuario.cuenta', compact('preferencias'));
    }
    

}
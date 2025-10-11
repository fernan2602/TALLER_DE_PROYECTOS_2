<?php

namespace App\Http\Controllers;

use App\Models\Medida;
use App\Models\Usuario;
use Illuminate\Http\Request;

class MedidaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'peso' => 'required|numeric|min:30|max:300',
            'talla' => 'required|numeric|min:100|max:250',
            'edad' => 'required|integer|min:15|max:100',
            'genero' => 'required|in:M,F',
            'circunferencia_brazo' => 'nullable|numeric|min:10|max:100',
            'circunferencia_antebrazo' => 'nullable|numeric|min:10|max:100',
            'circunferencia_cintura' => 'nullable|numeric|min:50|max:200',
            'circunferencia_caderas' => 'nullable|numeric|min:50|max:200',
            'circunferencia_muslos' => 'nullable|numeric|min:30|max:100',
            'circunferencia_pantorrilla' => 'nullable|numeric|min:20|max:80',
            'circunferencia_cuello' => 'nullable|numeric|min:20|max:60',
            'fecha_registro' => now(),
        ]);

        // Agregar el ID del usuario autenticado
        $validated['id_usuario'] = auth()->id();

        // Crear el registro usando el modelo
        Medida::create($validated);

        return back()->with('success', '¡Datos registrados correctamente!');
    }
}
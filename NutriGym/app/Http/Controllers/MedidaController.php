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
        ]);
        $estado = 1 ; 
        $validated['estado_fisico'] = $estado;

        // Agregar el ID del usuario autenticado
        $validated['id_usuario'] = auth()->id();

        // Crear el registro usando el modelo
        Medida::create($validated);

        return back()->with('success', '¡Datos registrados correctamente!');
    }

    public function update(Request $request , $id)
    {   
        // Validar la existencia del registro para el usuario
        $medida = Medida::where('id', $id)->where('id_usuario', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'peso' => 'required|numeric|min:30|max:300',
            'talla' => 'required|numeric|min:100|max:250',
            'edad' => 'required|integer|min:15|max:100',
            'circunferencia_brazo' => 'nullable|numeric|min:10|max:100',
            'circunferencia_antebrazo' => 'nullable|numeric|min:10|max:100',
            'circunferencia_cintura' => 'nullable|numeric|min:50|max:200',
            'circunferencia_caderas' => 'nullable|numeric|min:50|max:200',
            'circunferencia_muslos' => 'nullable|numeric|min:30|max:100',
            'circunferencia_pantorrilla' => 'nullable|numeric|min:20|max:80',
            'circunferencia_cuello' => 'nullable|numeric|min:20|max:60',
        ]);

        // condicional para no cambiar el estado inicial del sujeto 
        if($medida->estado_fisico == 1)
        {
            $nuevoestado = $medida->estado_fisico + 1 ;
            // se crea otro registro para no modificar el estado inicial 
            $nuevaMedida = Medida:: create ([
                'id_usuario' =>auth()->id(),
                'estado_fisico' => $nuevoestado ,
                'peso' => $validated['peso'],
                'talla' => $validated['talla'],
                'edad' => $validated['edad'],
                'genero' => $medida->genero,
                'circunferencia_brazo' => $validated['circunferencia_brazo'],
                'circunferencia_antebrazo' => $validated['circunferencia_antebrazo'],
                'circunferencia_cintura' => $validated['circunferencia_cintura'],
                'circunferencia_caderas' => $validated['circunferencia_caderas'],
                'circunferencia_muslos' => $validated['circunferencia_muslos'],
                'circunferencia_pantorrilla' => $validated['circunferencia_pantorrilla'],
                'circunferencia_cuello' => $validated['circunferencia_cuello'],
                'fecha_registro' => now(),

            ]);
            $mensaje = "Registro creado == sin modificar estado inicial";
        }else
        {

            $medida->update($validated);
            $mensaje = "Registro actualizado";
        }

        return back()->with('success',$mensaje);
          
    }
}
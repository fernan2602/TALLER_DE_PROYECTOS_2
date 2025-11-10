<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlimentosController extends Controller
{

        // En tu controller
    public function index()
    {
        $alimentos = DB::table('alimentos')
            ->select('*')
            ->orderBy('nombre')
            ->get();
        
        return view('nutriologo.alimentos', compact('alimentos'));
    }

    public function update(Request $request, $id)
    {
        try {
            $field = $request->field;
            $value = $request->value;
            
            // Validar que el campo existe
            $camposPermitidos = [
                'nombre', 'categoria', 'proteina', 'carbohidratos', 'grasas', 
                'fibra', 'azucar', 'calorias', 'sodio', 'potasio', 'calcio', 'hierro', 'unidad'
            ];
            
            if (!in_array($field, $camposPermitidos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Campo no permitido'
                ], 400);
            }
            
            // Actualizar en la base de datos
            DB::table('alimentos')
                ->where('id', $id)
                ->update([
                    $field => $value,
                    'updated_at' => now()
                ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Alimento actualizado correctamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }
}

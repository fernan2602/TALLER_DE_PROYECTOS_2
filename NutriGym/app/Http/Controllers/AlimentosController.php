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
            ->orderBy('created_at','desc')
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


    public function store(Request $request)
    {
        \Log::info('=== STORE METHOD CALLED ===');
        \Log::info('Datos recibidos:', $request->all());

        try {
            // Validación básica
            $request->validate([
                'nombre' => 'required|string|max:255',
                'categoria' => 'required|string|max:255', 
                'unidad_medida' => 'required|string|max:50'
            ]);

            \Log::info('Validación pasada');

            // Preparar datos con valores por defecto
            $datos = [
                'nombre' => $request->nombre,
                'categoria' => $request->categoria,
                'unidad_medida' => $request->unidad_medida,
                'calorias' => $request->calorias ?? 0,
                'proteina' => $request->proteina ?? 0,
                'carbohidratos' => $request->carbohidratos ?? 0,
                'grasas' => $request->grasas ?? 0,
                'fibra' => $request->fibra ?? 0,
                'azucar' => $request->azucar ?? 0,
                'sodio' => $request->sodio ?? 0,
                'potasio' => $request->potasio ?? 0,
                'calcio' => $request->calcio ?? 0,
                'hierro' => $request->hierro ?? 0,
                'created_at' => now(),
                'updated_at' => now()
            ];

            \Log::info('Datos preparados para insertar:', $datos);

            // Insertar en la base de datos
            $id = DB::table('alimentos')->insertGetId($datos);

            \Log::info('Alimento insertado con ID: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Alimento creado correctamente',
                'id' => $id
            ]);

        } catch (\Exception $e) {
            \Log::error('ERROR en store: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile());
            \Log::error('Line: ' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $eliminado = DB::table('alimentos')->where('id', $id)->delete();
            
            if ($eliminado) {
                return response()->json([
                    'success' => true,
                    'message' => 'Alimento eliminado correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el alimento'
                ], 404);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }
}

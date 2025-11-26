<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AsignacionMenu;
use App\Models\MenuAlimento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;


class MenuController extends Controller
{
    //
    public function calcularGET($usuarioId)
    {
        try {
            // Obtener el último progreso del usuario donde está el TMB
            $progreso = DB::table('progreso')
                ->join('medidas', 'progreso.id_medida', '=', 'medidas.id')
                ->where('medidas.id_usuario', $usuarioId)
                ->select('progreso.*')
                ->latest('progreso.fecha')
                ->first();

            if (!$progreso) {
                return [
                    'success' => false,
                    'message' => 'No se encontró progreso con TMB calculado'
                ];
            }

            // GET 
            $get = $progreso->tmb * 1.55;

            return [
                'success' => true,
                'get' => round($get, 2),
                'tmb' => $progreso->tmb,
                'factor_actividad' => 1.55,
                'nivel_actividad' => 'Moderado',
                'fecha_calculo' => $progreso->fecha
            ];

        } catch (\Exception $e) {
            return [
                'success' => false, 
                'message' => 'Error calculando GET: ' . $e->getMessage()
            ];
        }
    }

    public function pruebaGemini()
    {
        try {
            $geminiService = new \App\Services\GeminiService();
            
            $prompt = "Genera una frase motivacional corta sobre nutrición y ejercicio (máximo 10 palabras)";
            
            $respuesta = $geminiService->generateContent($prompt);

            return response()->json([
                'success' => true,
                'mensaje' => 'Gemini API funcionando correctamente',
                'respuesta' => $respuesta
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => '❌ Error con Gemini API: ' . $e->getMessage()
            ], 500);
        }
    }

    // Prueba de Gemini con datos de usuario
    public function generarDieta()
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
            $usuarioId = auth()->id();

            // Obtener datos del usuario
            $usuario = DB::table('usuarios')
                ->where('id', $usuarioId)
                ->select('id', 'nombre', 'email')
                ->first();

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Usuario no encontrado'
                ], 404);
            }

            // OBTENER ALIMENTOS CON ID Y CALORÍAS
            $alimentosCompletos = DB::table('alimentos')
                ->select('id', 'nombre', 'calorias')
                ->get();

            // Obtener preferencias del usuario
            $preferencias = DB::table('asignacion_preferencia')
                ->join('preferencias', 'asignacion_preferencia.id_preferencia', '=', 'preferencias.id')
                ->where('asignacion_preferencia.id_usuario', $usuarioId)
                ->select('preferencias.descripcion')
                ->get();

            // OBTENER OBJETIVOS DEL USUARIO
            $objetivos = DB::table('asignacion_objetivo')
                ->join('objetivos', 'asignacion_objetivo.id_objetivo', '=', 'objetivos.id')
                ->where('asignacion_objetivo.id_usuario', $usuarioId)
                ->select('objetivos.descripcion')
                ->get();

            $alimentosSeleccionados = $this->filtrarAlimentosConIA($alimentosCompletos, $preferencias, 9); // 9 para 3x3
        
            // DIVIDIR ALIMENTOS POR COMIDAS
            $alimentosPorComida = $this->dividirAlimentosPorComidas($alimentosSeleccionados);
            
            // CALCULAR CALORÍAS POR COMIDA Y TOTALES
            $caloriasPorComida = $this->calcularCaloriasPorComida($alimentosPorComida);
            $caloriasTotales = array_sum($caloriasPorComida);

            // CALCULAR GET Y AJUSTAR SEGÚN OBJETIVOS
            $resultadoGET = $this->calcularGET($usuarioId);
            $getValor = $resultadoGET['success'] ? $resultadoGET['get'] : 0;
            
            // AJUSTAR CALORÍAS SEGÚN OBJETIVOS
            $caloriasRecomendadas = $this->ajustarCaloriasPorObjetivo($getValor, $objetivos);
            $diferenciaCalorias = $caloriasRecomendadas - $caloriasTotales;

            // Generar menú con Gemini
            $geminiService = new \App\Services\GeminiService();
            
            $prompt = "Genera un menú diario con este formato exacto:

            Desayuno: [comida]
            Almuerzo: [comida] 
            Cena: [comida]

            Usuario: {$usuario->nombre}";

            if ($preferencias->isNotEmpty()) {
                $prefText = $preferencias->pluck('descripcion')->implode(', ');
                $prompt .= "\nPreferencias: {$prefText}";
            }

            // Incluir objetivos en el prompt
            if ($objetivos->isNotEmpty()) {
                $objText = $objetivos->pluck('descripcion')->implode(', ');
                $prompt .= "\nObjetivos: {$objText}";
                $prompt .= "\nCalorías objetivo: {$caloriasRecomendadas} kcal";
            }

            // Incluir alimentos organizados por comida en el prompt
            if (!empty($alimentosPorComida)) {
                $prompt .= "\n\nAlimentos organizados:";
                $prompt .= "\nDesayuno: " . $alimentosPorComida['desayuno']->pluck('nombre')->implode(', ');
                $prompt .= "\nAlmuerzo: " . $alimentosPorComida['almuerzo']->pluck('nombre')->implode(', ');
                $prompt .= "\nCena: " . $alimentosPorComida['cena']->pluck('nombre')->implode(', ');
            }

            $prompt .= "\n\nResponde solo con el menú en el formato solicitado.";

            $mensajeGemini = $geminiService->generateContent($prompt);
            
            return response()->json([
                'mensaje_personalizado' => $mensajeGemini,
                'alimentos_por_comida' => [
                    'desayuno' => $alimentosPorComida['desayuno']->map(function($alimento) {
                        return [
                            'id' => $alimento->id,
                            'nombre' => $alimento->nombre,
                            'calorias' => $alimento->calorias
                        ];
                    }),
                    'almuerzo' => $alimentosPorComida['almuerzo']->map(function($alimento) {
                        return [
                            'id' => $alimento->id,
                            'nombre' => $alimento->nombre,
                            'calorias' => $alimento->calorias
                        ];
                    }),
                    'cena' => $alimentosPorComida['cena']->map(function($alimento) {
                        return [
                            'id' => $alimento->id,
                            'nombre' => $alimento->nombre,
                            'calorias' => $alimento->calorias
                        ];
                    })
                ],
                'calorias_por_comida' => $caloriasPorComida,
                'calorias_totales' => $caloriasTotales,
                'GET' => $getValor,
                'objetivos_usuario' => $objetivos->pluck('descripcion'),
                'calorias_recomendadas' => $caloriasRecomendadas,
                'diferencia_calorias' => $diferenciaCalorias,
                'estado_calorias' => $this->evaluarEstadoCalorias($diferenciaCalorias)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }



    // AJUSTAR CALORÍAS SEGÚN OBJETIVOS
    private function ajustarCaloriasPorObjetivo($getBase, $objetivos)
    {
        $caloriasAjustadas = $getBase;
        
        foreach ($objetivos as $objetivo) {
            $descripcion = strtolower($objetivo->descripcion);
            
            if (strpos($descripcion, 'bajar') !== false || strpos($descripcion, 'perder') !== false || strpos($descripcion, 'reducir') !== false) {
                // Déficit calórico para bajar de peso (15-20% menos)
                $caloriasAjustadas = $getBase * 0.80; // 20% menos
            } elseif (strpos($descripcion, 'subir') !== false || strpos($descripcion, 'aumentar') !== false || strpos($descripcion, 'ganar') !== false) {
                // Superávit calórico para subir de peso (10-15% más)
                $caloriasAjustadas = $getBase * 1.15; // 15% más
            } elseif (strpos($descripcion, 'mantener') !== false || strpos($descripcion, 'conservar') !== false) {
                // Mantener peso (GET base)
                $caloriasAjustadas = $getBase;
            } elseif (strpos($descripcion, 'definir') !== false || strpos($descripcion, 'tonificar') !== false || strpos($descripcion,'desarrollar') !== false) {
                // Déficit moderado para definición muscular
                $caloriasAjustadas = $getBase * 0.85; // 15% menos
            }
        }
        
        return round($caloriasAjustadas);
    }

    // EVALUAR ESTADO DE CALORÍAS
    private function evaluarEstadoCalorias($diferencia)
    {
        if ($diferencia > 200) {
            return 'Por debajo del objetivo - considerar aumentar calorías';
        } elseif ($diferencia < -200) {
            return 'Por encima del objetivo - considerar reducir calorías';
        } else {
            return 'Dentro del rango objetivo';
        }
    }

    // DIVIDIR ALIMENTOS POR COMIDAS
    private function dividirAlimentosPorComidas($alimentosSeleccionados)
    {
        $alimentosArray = $alimentosSeleccionados->shuffle()->values();
        $count = $alimentosArray->count();
        
        $porComida = floor($count / 3);
        
        return [
            'desayuno' => $alimentosArray->slice(0, $porComida),
            'almuerzo' => $alimentosArray->slice($porComida, $porComida),
            'cena' => $alimentosArray->slice($porComida * 2)
        ];
    }

    // CALCULAR CALORÍAS POR COMIDA
    private function calcularCaloriasPorComida($alimentosPorComida)
    {
        return [
            'desayuno' => $alimentosPorComida['desayuno']->sum('calorias'),
            'almuerzo' => $alimentosPorComida['almuerzo']->sum('calorias'),
            'cena' => $alimentosPorComida['cena']->sum('calorias')
        ];
    }

    // FILTRADO INTELIGENTE CON IA (actualizado para trabajar con objetos completos)
    private function filtrarAlimentosConIA($alimentos, $preferencias, $cantidad = 9)
    {
        $geminiService = new \App\Services\GeminiService();
        
        $listaAlimentos = $alimentos->pluck('nombre')->implode(', ');
        
        $promptFiltrado = "Selecciona EXACTAMENTE {$cantidad} alimentos de esta lista que sean compatibles con: " . 
                        ($preferencias->isNotEmpty() ? $preferencias->pluck('descripcion')->implode(', ') : 'Ninguna') . 
                        "\n\nLista: {$listaAlimentos}\n\nRespuesta solo con nombres separados por coma:";

        $respuestaIA = $geminiService->generateContent($promptFiltrado);
        
        // DEBUG: Ver qué respondió la IA
        \Log::info("IA respondió: " . $respuestaIA);
        \Log::info("Número de alimentos encontrados: " . $alimentos->count());
        
        return $this->procesarRespuestaFiltrado($respuestaIA, $alimentos, $cantidad);
    }


    // PROCESAR RESPUESTA DE IA (actualizado para mantener objetos completos)
    private function procesarRespuestaFiltrado($respuestaIA, $alimentos, $cantidad)
    {
        // Limpiar y dividir la respuesta
        $nombresSeleccionados = array_map('trim', explode(',', $respuestaIA));
        
        // Buscar los objetos completos de alimentos
        $resultado = collect();
        
        foreach ($nombresSeleccionados as $nombreAlimento) {
            if (empty(trim($nombreAlimento))) continue;
            
            // Buscar el alimento completo en la colección original
            $alimentoEncontrado = $alimentos->first(function($alimento) use ($nombreAlimento) {
                return stripos($alimento->nombre, $nombreAlimento) !== false || 
                    stripos($nombreAlimento, $alimento->nombre) !== false;
            });
            
            if ($alimentoEncontrado && !$resultado->contains('id', $alimentoEncontrado->id)) {
                $resultado->push($alimentoEncontrado);
            }
            
            // Limitar a la cantidad deseada
            if ($resultado->count() >= $cantidad) {
                break;
            }
        }
        
        // Si la IA no seleccionó suficientes, agregar algunos aleatorios
        if ($resultado->count() < $cantidad) {
            $faltantes = $cantidad - $resultado->count();
            $alimentosRestantes = $alimentos->whereNotIn('id', $resultado->pluck('id'));
            
            if ($alimentosRestantes->count() >= $faltantes) {
                $resultado = $resultado->merge($alimentosRestantes->random($faltantes));
            } else {
                $resultado = $resultado->merge($alimentosRestantes);
            }
        }
        
        return $resultado;
    }




    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:desayuno,almuerzo,cena,otro',
            'calorias' =>'required|integer',
            'fecha_asignacion' => 'required|date',
            'alimentos' => 'required|array',
            'alimentos.*' => 'exists:alimentos,id'
        ]);

        DB::beginTransaction();

        try {
            // 1. Primero crear en asignacion_menus
            $asignacion = AsignacionMenu::create([
                'id_usuario' => auth()->id(),
                'tipo' => $request->tipo,
                'calorias'=>$request->calorias,
                'fecha_asignacion' => $request->fecha_asignacion
            ]);

            // 2. Luego guardar los alimentos en menu_alimentos
            $alimentosData = [];
            foreach ($request->alimentos as $idAlimento) {
                $alimentosData[] = [
                    'asignacion_menu_id' => $asignacion->id,
                    'id_alimento' => $idAlimento,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            MenuAlimento::insert($alimentosData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Menú asignado correctamente',
                'data' => [
                    'asignacion' => $asignacion,
                    'alimentos_count' => count($request->alimentos)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar el menú: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener menús por usuario
    public function getMyMenus()
    {
        $menus = AsignacionMenu::with('alimentos.alimento')
            ->where('id_usuario', auth()->id()) // ✅ Solo menús del usuario autenticado
            ->orderBy('fecha_asignacion', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $menus
        ]);
    }

    public function getByUsuario($id)
    {
        $menus = AsignacionMenu::with('alimentos.alimento')
            ->where('id_usuario', $id) // ✅ Solo menús del usuario autenticado
            ->orderBy('fecha_asignacion', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $menus
        ]);
    }

    public function toggleValidacion($id)
    {
        $menu = AsignacionMenu::findOrFail($id);
        
        // Toggle del campo validado
        $menu->validado = !$menu->validado;
        $menu->save();
        
        return response()->json([
            'success' => true,
            'menu' => $menu
        ]);
    }

}


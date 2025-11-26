<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medida;
use App\Models\Progreso;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProgresoController extends Controller
{
    public function obtenerProgresoUsuario($pacienteId = null)
    {
        try {
            $usuarioId = $pacienteId ?? auth()->id();
            
            if (!$usuarioId) {
                return $this->errorResponse('Usuario no autenticado', 401);
            }

            $usuario = Usuario::find($usuarioId);
            
            if (!$usuario) {
                return $this->errorResponse('Usuario no encontrado con ID: ' . $usuarioId, 404);
            }

            // Obtener datos del progreso
            $estadoInicial = $this->obtenerEstadoInicial($usuarioId);
            $estadoActual = $this->obtenerEstadoActual($usuarioId);
            $progresos = $this->obtenerProgresos($usuarioId);
            $historialPeso = $this->obtenerHistorialPeso($usuarioId);

            // Calcular métricas
            $metricas = $this->calcularMetricas($estadoInicial, $estadoActual, $usuarioId);
            $metas = $this->obtenerMetasUsuario($estadoInicial, $estadoActual, $metricas['sesionesMes']);

            return response()->json([
                'success' => true,
                'data' => [
                    'usuario' => $this->formatearUsuario($usuario),
                    'metricas' => $metricas,
                    'historialPeso' => $historialPeso,
                    'metas' => $metas,
                    'estadoInicial' => $estadoInicial,
                    'estadoActual' => $estadoActual,
                ],
                'debug' => $this->generarDebugInfo($usuario, $usuarioId, $estadoInicial, $estadoActual)
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse('Error al cargar el progreso: ' . $e->getMessage(), 500);
        }
    }


    public function obtenerMiProgreso()
    {
        return $this->obtenerProgresoUsuario(auth()->id());
    }

    // Los métodos privados se mantienen igual...
    private function obtenerEstadoInicial($usuarioId)
    {
        return Medida::where('id_usuario', $usuarioId)
            ->orderBy('fecha_registro', 'asc')
            ->first();
    }

    private function obtenerEstadoActual($usuarioId)
    {
        return Medida::where('id_usuario', $usuarioId)
            ->orderBy('fecha_registro', 'desc')
            ->first();
    }

    private function obtenerProgresos($usuarioId)
    {
        return Progreso::join('medidas', 'progreso.id_medida', '=', 'medidas.id')
            ->where('medidas.id_usuario', $usuarioId)
            ->select('progreso.*')
            ->orderBy('progreso.id_progreso', 'desc')  // Ordenar por ID en lugar de fecha
            ->get();
    }

    private function obtenerHistorialPeso($usuarioId)
    {
        return Medida::where('id_usuario', $usuarioId)
            ->orderBy('fecha_registro', 'desc')
            ->take(5)
            ->get(['peso', 'fecha_registro'])
            ->reverse()
            ->values();
    }

    private function calcularMetricas($estadoInicial, $estadoActual, $usuarioId)
    {
        $progresos = $this->obtenerProgresos($usuarioId);


        return [
            'pesoPerdido' => $this->calcularPesoPerdido($estadoInicial, $estadoActual),
            'sesionesMes' => $this->calcularSesionesMes($usuarioId),
            'asistencia' => $this->calcularAsistencia($this->calcularSesionesMes($usuarioId)),
            'incrementoFuerza' => $this->calcularIncrementoFuerza($estadoInicial, $estadoActual),

            'reduccionGrasa' => $this->calcularReduccionGrasa($progresos),
            'gananciaMuscular' => $this->calcularGananciaMuscular($progresos),
            'mejoraIMC' => $this->calcularMejoraIMC($progresos),
            'progresoGeneral' => $this->verificarProgreso($progresos),
            'consistencia' => $this->calcularConsistencia($progresos)
        ];
    }
    private function calcularReduccionGrasa($progresos)
{
    if ($progresos->count() < 2) return 0;
    
    $primero = $progresos->last();
    $ultimo = $progresos->first();
    
    return $primero->porcentaje_grasa - $ultimo->porcentaje_grasa;
}

private function calcularGananciaMuscular($progresos)
{
    
    $primero = $progresos->last();
    $ultimo = $progresos->first();
    
    return $ultimo->masa_muscular - $primero->masa_muscular;
}

private function calcularMejoraIMC($progresos)
{

    $ultimo = $progresos->first();
    return $ultimo->masa_muscular - 0 ;
}

private function verificarProgreso($progresos)
{
    if ($progresos->isEmpty()) return 0;
    
    return $progresos->avg('progreso'); // Usa el campo 'progreso' de la tabla
}

private function calcularConsistencia($progresos)
{
    if ($progresos->count() < 3) return 100;
    
    $progresosValores = $progresos->pluck('progreso');
    $mejoras = 0;
    
    for ($i = 1; $i < $progresosValores->count(); $i++) {
        if ($progresosValores[$i] >= $progresosValores[$i - 1]) {
            $mejoras++;
        }
    }
    
    return ($mejoras / ($progresosValores->count() - 1)) * 100;
}

    private function formatearUsuario($usuario)
    {
        return [
            'id' => $usuario->id,
            'nombre' => $usuario->name,
            'email' => $usuario->email
        ];
    }

    private function generarDebugInfo($usuario, $usuarioId, $estadoInicial, $estadoActual)
    {
        return [
            'usuario_consultado' => [
                'id' => $usuario->id,
                'nombre' => $usuario->name,
                'email' => $usuario->email
            ],
            'usuario_autenticado' => [
                'id' => Auth::id(),
                'nombre' => Auth::user()->name ?? 'N/A',
                'email' => Auth::user()->email ?? 'N/A'
            ],
            'total_medidas' => Medida::where('id_usuario', $usuarioId)->count(),
            'tiene_estado_inicial' => !is_null($estadoInicial),
            'tiene_estado_actual' => !is_null($estadoActual)
        ];
    }

    private function errorResponse($message, $status = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $status);
    }

    private function calcularPesoPerdido($inicial, $actual)
    {
        if (!$inicial || !$actual) return 0;
        return round($inicial->peso - $actual->peso, 1);
    }

    private function calcularSesionesMes($usuarioId)
    {
        return Medida::where('id_usuario', $usuarioId)
            ->whereMonth('fecha_registro', now()->month)
            ->whereYear('fecha_registro', now()->year)
            ->count();
    }

    private function calcularAsistencia($sesionesMes)
    {
        $sesionesEsperadas = 12;
        return $sesionesEsperadas > 0 ? 
            min(100, round(($sesionesMes / $sesionesEsperadas) * 100)) : 0;
    }

    private function calcularIncrementoFuerza($inicial, $actual)
    {
        if (!$inicial || !$actual) return 0;
        
        $fuerzaInicial = (
            ($inicial->circunferencia_brazo ?? 0) +
            ($inicial->circunferencia_muslos ?? 0) +
            ($inicial->circunferencia_pantorrilla ?? 0)
        ) / 3;

        $fuerzaActual = (
            ($actual->circunferencia_brazo ?? 0) +
            ($actual->circunferencia_muslos ?? 0) +
            ($actual->circunferencia_pantorrilla ?? 0)
        ) / 3;

        return $fuerzaInicial > 0 ? 
            round((($fuerzaActual - $fuerzaInicial) / $fuerzaInicial) * 100) : 0;
    }

private function obtenerMetasUsuario($inicial, $actual, $sesionesMes)
{
    $metas = [];

    // Obtener el ID del usuario autenticado
    $usuarioId = auth()->id();
    
    // Obtener historial de medidas para análisis de tendencia
    $historialPesos = $this->obtenerHistorialPesos($usuarioId);
    
    // 1. METAS BASADAS EN OBJETIVOS ASIGNADOS (MEJORADO CON TENDENCIA)
    $metasObjetivos = $this->generarMetasDesdeObjetivos($usuarioId, $inicial, $actual, $historialPesos);
    $metas = array_merge($metas, $metasObjetivos);

    // 2. META EXISTENTE: Pérdida de peso (MEJORADO - basado en tendencia real)
    if (!$this->tieneMetaPeso($metasObjetivos)) {
        $perdidaPeso = $this->calcularPesoPerdido($inicial, $actual);
        $metaPeso = $this->calcularMetaPesoInteligente($historialPesos, $actual->peso);
        $metas[] = [
            'descripcion' => "Bajar {$metaPeso}kg",
            'progreso' => $perdidaPeso,
            'objetivo' => $metaPeso,
            'completada' => $perdidaPeso >= $metaPeso,
            'tipo' => 'peso_general',
            'porcentaje' => min(100, ($perdidaPeso / $metaPeso) * 100)
        ];
    }

    // 3. META EXISTENTE: Sesiones mensuales
    $metaSesiones = 8;
    $metas[] = [
        'descripcion' => "Completar {$metaSesiones} sesiones",
        'progreso' => $sesionesMes,
        'objetivo' => $metaSesiones,
        'completada' => $sesionesMes >= $metaSesiones,
        'tipo' => 'asistencia',
        'porcentaje' => min(100, ($sesionesMes / $metaSesiones) * 100)
    ];

    // 4. META EXISTENTE: Mejora del estado físico
    if ($inicial && $actual) {
        $mejoraEstado = $actual->estado_fisico - $inicial->estado_fisico;
        $metas[] = [
            'descripcion' => "Mejorar estado físico",
            'progreso' => $mejoraEstado,
            'objetivo' => 1,
            'completada' => $mejoraEstado > 0,
            'tipo' => 'estado_fisico',
            'porcentaje' => min(100, $mejoraEstado * 100)
        ];
    }

    // 5. NUEVA META: Consistencia en el progreso
    $consistencia = $this->calcularConsistenciaProgreso($historialPesos);
    $metas[] = [
        'descripcion' => "Mantener progreso consistente",
        'progreso' => $consistencia,
        'objetivo' => 80,
        'completada' => $consistencia >= 80,
        'tipo' => 'consistencia',
        'porcentaje' => $consistencia
    ];

    return $metas;
}

/**
 * Obtener historial de pesos para análisis de tendencia
 */
private function obtenerHistorialPesos($usuarioId)
{
    return DB::table('medidas')
        ->where('id_usuario', $usuarioId)
        ->orderBy('fecha_registro', 'asc')
        ->select('peso', 'fecha_registro')
        ->get();
}

/**
 * Calcular meta de peso inteligente basada en tendencia
 */
private function calcularMetaPesoInteligente($historialPesos, $pesoActual)
{
    if ($historialPesos->count() < 3) {
        return 2; // Meta por defecto si no hay suficiente historial
    }

    // Calcular tendencia lineal
    $tendencia = $this->calcularTendenciaPeso($historialPesos);
    
    // Meta basada en la tendencia reciente
    if ($tendencia < -0.5) {
        // Tendencia de pérdida rápida (>0.5kg por mes)
        return 3; // Meta más ambiciosa
    } elseif ($tendencia < -0.2) {
        // Tendencia de pérdida moderada
        return 2; // Meta estándar
    } else {
        // Tendencia estable o aumento
        return 1; // Meta conservadora
    }
}

/**
 * Calcular tendencia de peso (kg por mes)
 */
private function calcularTendenciaPeso($historialPesos)
{
    $n = $historialPesos->count();
    if ($n < 2) return 0;

    $pesos = $historialPesos->pluck('peso')->toArray();
    
    // Usar solo los últimos 6 meses para la tendencia
    $pesosRecientes = array_slice($pesos, -6);
    $nRecientes = count($pesosRecientes);
    
    if ($nRecientes < 2) return 0;

    $primerPeso = $pesosRecientes[0];
    $ultimoPeso = end($pesosRecientes);
    
    // Cambio total en el período
    $cambioTotal = $ultimoPeso - $primerPeso;
    
    // Promedio de cambio por mes (asumiendo 1 mes entre mediciones)
    return $cambioTotal / ($nRecientes - 1);
}

/**
 * Calcular consistencia del progreso
 */
private function calcularConsistenciaProgreso($historialPesos)
{
    if ($historialPesos->count() < 3) return 50; // Valor por defecto

    $pesos = $historialPesos->pluck('peso')->toArray();
    $mejorasConsecutivas = 0;
    $totalComparaciones = 0;

    for ($i = 1; $i < count($pesos); $i++) {
        if ($pesos[$i] <= $pesos[$i - 1]) {
            $mejorasConsecutivas++;
        }
        $totalComparaciones++;
    }

    return ($mejorasConsecutivas / $totalComparaciones) * 100;
}

/**
 * Generar metas basadas en objetivos asignados al usuario (MEJORADO)
 */
private function generarMetasDesdeObjetivos($usuarioId, $medidaInicial, $medidaActual, $historialPesos = null)
{
    $objetivos = DB::table('asignacion_objetivo')
        ->join('objetivos', 'asignacion_objetivo.id_objetivo', '=', 'objetivos.id')
        ->select(
            'objetivos.id',
            'objetivos.nombre', 
            'objetivos.descripcion',
            'asignacion_objetivo.fecha_asignacion',
            'asignacion_objetivo.estado',
            'asignacion_objetivo.calificacion'
        )
        ->where('asignacion_objetivo.id_usuario', $usuarioId)
        ->where('asignacion_objetivo.estado', 1)
        ->orderBy('asignacion_objetivo.fecha_asignacion', 'desc')
        ->get();

    $metas = [];

    foreach ($objetivos as $objetivo) {
        $nombre = strtolower($objetivo->nombre);
        $descripcion = strtolower($objetivo->descripcion);

        // Pérdida de peso (MEJORADO)
        if (str_contains($nombre, 'pérdida') || str_contains($nombre, 'peso') || 
            str_contains($descripcion, 'reducir grasa') || str_contains($descripcion, 'peso saludable')) {
            
            $perdidaPeso = $this->calcularPesoPerdido($medidaInicial, $medidaActual);
            $metaPeso = $this->calcularMetaPesoInteligente($historialPesos, $medidaActual->peso);
            
            $metas[] = [
                'tipo' => 'peso',
                'descripcion' => "Bajar {$metaPeso}kg",
                'progreso' => $perdidaPeso,
                'objetivo' => $metaPeso,
                'completada' => $perdidaPeso >= $metaPeso,
                'porcentaje' => min(100, ($perdidaPeso / $metaPeso) * 100),
                'objetivo_origen' => $objetivo->nombre,
                'icono' => '📉'
            ];
        }

        // Ganancia muscular (MEJORADO)
        if (str_contains($nombre, 'ganancia') || str_contains($nombre, 'muscular') || 
            str_contains($descripcion, 'masa muscular') || str_contains($descripcion, 'aumentar')) {
            
            $gananciaBrazo = $this->calcularGananciaCircunferencia($medidaInicial, $medidaActual, 'circunferencia_brazo');
            $metaBrazo = 3;
            
            // Ajustar meta según progreso actual
            if ($gananciaBrazo > 2) {
                $metaBrazo = 4; // Meta más ambiciosa si ya hay buen progreso
            }
            
            $metas[] = [
                'tipo' => 'musculo',
                'descripcion' => "Aumentar brazo {$metaBrazo}cm",
                'progreso' => $gananciaBrazo,
                'objetivo' => $metaBrazo,
                'completada' => $gananciaBrazo >= $metaBrazo,
                'porcentaje' => min(100, ($gananciaBrazo / $metaBrazo) * 100),
                'objetivo_origen' => $objetivo->nombre,
                'icono' => '💪'
            ];
        }

        // Mejora cardiovascular (MEJORADO)
        if (str_contains($nombre, 'cardiovascular') || str_contains($nombre, 'resistencia') || 
            str_contains($descripcion, 'corazón') || str_contains($descripcion, 'aeróbico')) {
            
            $reduccionCintura = $this->calcularReduccionCintura($medidaInicial, $medidaActual);
            $metaCintura = 8;
            
            $metas[] = [
                'tipo' => 'cardiovascular',
                'descripcion' => "Reducir cintura {$metaCintura}cm",
                'progreso' => $reduccionCintura,
                'objetivo' => $metaCintura,
                'completada' => $reduccionCintura >= $metaCintura,
                'porcentaje' => min(100, ($reduccionCintura / $metaCintura) * 100),
                'objetivo_origen' => $objetivo->nombre,
                'icono' => '❤️'
            ];
        }

        // Aumento de fuerza (MEJORADO)
        if (str_contains($nombre, 'fuerza') || str_contains($descripcion, 'fuerza máxima') || 
            str_contains($descripcion, 'cargas progresivas')) {
            
            $gananciaMuslo = $this->calcularGananciaCircunferencia($medidaInicial, $medidaActual, 'circunferencia_muslos');
            $metaMuslo = 4;
            
            $metas[] = [
                'tipo' => 'fuerza',
                'descripcion' => "Aumentar muslos {$metaMuslo}cm",
                'progreso' => $gananciaMuslo,
                'objetivo' => $metaMuslo,
                'completada' => $gananciaMuslo >= $metaMuslo,
                'porcentaje' => min(100, ($gananciaMuslo / $metaMuslo) * 100),
                'objetivo_origen' => $objetivo->nombre,
                'icono' => '🏋️'
            ];
        }

        // Definición muscular (MEJORADO)
        if (str_contains($nombre, 'definición') || str_contains($descripcion, 'definición') || 
            str_contains($descripcion, 'porcentaje de grasa')) {
            
            $perdidaPeso = $this->calcularPesoPerdido($medidaInicial, $medidaActual);
            $reduccionCintura = $this->calcularReduccionCintura($medidaInicial, $medidaActual);
            $metaCombinada = 6;
            
            $progresoCombinado = ($perdidaPeso + $reduccionCintura) / 2;
            
            $metas[] = [
                'tipo' => 'definicion',
                'descripcion' => "Mejor definición muscular",
                'progreso' => $progresoCombinado,
                'objetivo' => $metaCombinada,
                'completada' => $progresoCombinado >= $metaCombinada,
                'porcentaje' => min(100, ($progresoCombinado / $metaCombinada) * 100),
                'objetivo_origen' => $objetivo->nombre,
                'icono' => '🔍'
            ];
        }

        // Salud y bienestar general (MEJORADO)
        if (str_contains($nombre, 'salud') || str_contains($nombre, 'bienestar') || 
            str_contains($descripcion, 'calidad de vida')) {
            
            $progresoGeneral = $this->calcularProgresoGeneral($medidaInicial, $medidaActual);
            
            $metas[] = [
                'tipo' => 'salud',
                'descripcion' => "Mejora general de salud",
                'progreso' => $progresoGeneral,
                'objetivo' => 80,
                'completada' => $progresoGeneral >= 80,
                'porcentaje' => $progresoGeneral,
                'objetivo_origen' => $objetivo->nombre,
                'icono' => '🌟'
            ];
        }

        // Mantenimiento (MEJORADO)
        if (str_contains($nombre, 'mantenimiento') || str_contains($descripcion, 'conservar logros')) {
            $estabilidad = $this->calcularEstabilidadPeso($medidaInicial, $medidaActual);
            
            $metas[] = [
                'tipo' => 'mantenimiento',
                'descripcion' => "Mantener peso estable",
                'progreso' => $estabilidad,
                'objetivo' => 90,
                'completada' => $estabilidad >= 90,
                'porcentaje' => $estabilidad,
                'objetivo_origen' => $objetivo->nombre,
                'icono' => '⚖️'
            ];
        }
    }

    return $metas;
}

    private function tieneMetaPeso($metasObjetivos)
    {
        foreach ($metasObjetivos as $meta) {
            if ($meta['tipo'] === 'peso') {
                return true;
            }
        }
        return false;
    }

// MÉTODOS AUXILIARES PARA CÁLCULOS

private function calcularPesoPerdido_2($medidaInicial, $medidaActual)
{
    if (!$medidaInicial || !$medidaActual) return 0;
    return max(0, $medidaInicial->peso - $medidaActual->peso);
}

private function calcularGananciaCircunferencia($medidaInicial, $medidaActual, $campo)
{
    if (!$medidaInicial || !$medidaActual) return 0;
    return max(0, $medidaActual->$campo - $medidaInicial->$campo);
}

private function calcularReduccionCintura($medidaInicial, $medidaActual)
{
    if (!$medidaInicial || !$medidaActual) return 0;
    return max(0, $medidaInicial->circunferencia_cintura - $medidaActual->circunferencia_cintura);
}

private function calcularProgresoGeneral($medidaInicial, $medidaActual)
{
    if (!$medidaInicial || !$medidaActual) return 0;
    
    $mejoras = 0;
    $totalMetricas = 0;

    // Peso (mejor si baja)
    if ($medidaActual->peso < $medidaInicial->peso) $mejoras++;
    $totalMetricas++;

    // Cintura (mejor si baja)
    if ($medidaActual->circunferencia_cintura < $medidaInicial->circunferencia_cintura) $mejoras++;
    $totalMetricas++;

    // Brazo (mejor si sube - músculo)
    if ($medidaActual->circunferencia_brazo > $medidaInicial->circunferencia_brazo) $mejoras++;
    $totalMetricas++;

    return ($mejoras / $totalMetricas) * 100;
}

private function calcularEstabilidadPeso($medidaInicial, $medidaActual)
{
    if (!$medidaInicial || !$medidaActual) return 0;
    
    $variacion = abs($medidaInicial->peso - $medidaActual->peso);
    $porcentajeVariacion = ($variacion / $medidaInicial->peso) * 100;
    
    // 100% - variación (mientras menos variación, más estabilidad)
    return max(0, 100 - $porcentajeVariacion);
}
}
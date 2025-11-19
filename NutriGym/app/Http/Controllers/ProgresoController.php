<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medida;
use App\Models\Progreso;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class ProgresoController extends Controller
{
    public function obtenerProgresoUsuario($pacienteId = null)
    {
        try {
            if (!$pacienteId) {
                return $this->errorResponse('Se requiere el ID del paciente', 400);
            }

            $usuarioId = $pacienteId;
            $usuario = Usuario::find($usuarioId);
            
            if (!$usuario) {
                return $this->errorResponse('Paciente no encontrado con ID: ' . $usuarioId, 404);
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
                    'progresos' => $progresos
                ],
                'debug' => $this->generarDebugInfo($usuario, $usuarioId, $estadoInicial, $estadoActual)
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse('Error al cargar el progreso: ' . $e->getMessage(), 500);
        }
    }

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
        return Progreso::whereHas('medida', function($query) use ($usuarioId) {
                $query->where('id_usuario', $usuarioId);
            })
            ->with('medida')
            ->orderBy('fecha', 'desc')
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
        return [
            'pesoPerdido' => $this->calcularPesoPerdido($estadoInicial, $estadoActual),
            'sesionesMes' => $this->calcularSesionesMes($usuarioId),
            'asistencia' => $this->calcularAsistencia($this->calcularSesionesMes($usuarioId)),
            'incrementoFuerza' => $this->calcularIncrementoFuerza($estadoInicial, $estadoActual)
        ];
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

        // Meta 1: Pérdida de peso
        $perdidaPeso = $this->calcularPesoPerdido($inicial, $actual);
        $metaPeso = 2;
        $metas[] = [
            'descripcion' => "Bajar {$metaPeso}kg",
            'progreso' => $perdidaPeso,
            'objetivo' => $metaPeso,
            'completada' => $perdidaPeso >= $metaPeso
        ];

        // Meta 2: Sesiones mensuales
        $metaSesiones = 8;
        $metas[] = [
            'descripcion' => "Completar {$metaSesiones} sesiones",
            'progreso' => $sesionesMes,
            'objetivo' => $metaSesiones,
            'completada' => $sesionesMes >= $metaSesiones
        ];

        // Meta 3: Mejora del estado físico
        if ($inicial && $actual) {
            $mejoraEstado = $actual->estado_fisico - $inicial->estado_fisico;
            $metas[] = [
                'descripcion' => "Mejorar estado físico",
                'progreso' => $mejoraEstado,
                'objetivo' => 1,
                'completada' => $mejoraEstado > 0
            ];
        }

        return $metas;
    }
}
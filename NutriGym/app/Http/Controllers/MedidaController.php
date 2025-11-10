<?php

namespace App\Http\Controllers;

use App\Models\Medida;
use App\Models\Progreso;
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
        
        $estado = 1; 
        $validated['estado_fisico'] = $estado;

        // Agregar el ID del usuario autenticado
        $validated['id_usuario'] = auth()->id();

        // Crear el registro usando el modelo
        $medida = Medida::create($validated);

        // Calcular y guardar progreso automáticamente
        $this->calcularYGuardarProgreso($medida);

        return back()->with('success', '¡Datos registrados correctamente!');
    }

    public function update(Request $request, $id)
    {
        $medida = Medida::where('id', $id)->where('id_usuario', auth()->id())->firstOrFail();

        try {
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        if($medida->estado_fisico == 1)
        {
            $nuevoestado = $medida->estado_fisico + 1;
            
            $nuevaMedida = Medida::create([
                'id_usuario' => auth()->id(),
                'estado_fisico' => $nuevoestado,
                'peso' => $validated['peso'],
                'talla' => $validated['talla'],
                'edad' => $validated['edad'],
                'circunferencia_brazo' => $validated['circunferencia_brazo'],
                'circunferencia_antebrazo' => $validated['circunferencia_antebrazo'],
                'circunferencia_cintura' => $validated['circunferencia_cintura'],
                'circunferencia_caderas' => $validated['circunferencia_caderas'],
                'circunferencia_muslos' => $validated['circunferencia_muslos'],
                'circunferencia_pantorrilla' => $validated['circunferencia_pantorrilla'],
                'circunferencia_cuello' => $validated['circunferencia_cuello'],
                'fecha_registro' => now(),
            ]);

            $this->calcularYGuardarProgreso($nuevaMedida);

            $mensaje = "Registro creado - sin modificar estado inicial";
        } else {
            $medida->update($validated);

            $this->calcularYGuardarProgreso($medida);

            $mensaje = "Registro actualizado";
        }

        return back()->with('success', $mensaje);
    }
    /**
     * Calcula todas las métricas y guarda en la tabla progreso
     */
    public function calcularYGuardarProgreso(Medida $medida)
    {
        // Convertir talla de cm a metros para IMC
        $talla_metros = $medida->talla / 100;
        
        // 1. Cálculo de IMC
        $imc = $medida->peso / ($talla_metros * $talla_metros);
        
        // 2. Cálculo de TMB (Tasa Metabólica Basal)
        if ($medida->genero === 'M') {
            $tmb = (10 * $medida->peso) + (6.25 * $medida->talla) - (5 * $medida->edad) + 5;
        } else {
            $tmb = (10 * $medida->peso) + (6.25 * $medida->talla) - (5 * $medida->edad) - 161;
        }
        
        // 3. Cálculo de % de Grasa Corporal
        $sexo_valor = $medida->genero === 'F' ? 1 : 0;
        $porcentaje_grasa = -44.988 + (0.503 * $medida->edad) + (10.689 * $sexo_valor) + 
                           (3.172 * $imc) - (0.026 * pow($imc, 2)) + 
                           (0.181 * $imc * $sexo_valor) - (0.02 * $imc * $medida->edad) - 
                           (0.005 * pow($imc, 2) * $sexo_valor) + 
                           (0.00021 * pow($imc, 2) * $medida->edad);
        
        // Asegurar que el porcentaje no sea negativo
        $porcentaje_grasa = max(0, $porcentaje_grasa);
        
        // 4. Cálculo de Masa Grasa (kg)
        if ($medida->genero === 'M') {
            // Para hombres: MG = [495 / (1.0324 - 0.19077 × log10(cintura - cuello) + 0.15456 × log10(altura)) - 450] × peso
            if ($medida->circunferencia_cintura && $medida->circunferencia_cuello) {
                $masa_grasa = (495 / (1.0324 - 0.19077 * log10($medida->circunferencia_cintura - $medida->circunferencia_cuello) + 0.15456 * log10($medida->talla)) - 450) * $medida->peso / 100;

            } else {
                $masa_grasa = ($porcentaje_grasa / 100) * $medida->peso;
            }
        } else {
            // Para mujeres: MG = [495 / (1.29579 - 0.35004 × log10(cintura + caderas - cuello) + 0.22100 × log10(altura)) - 450] × peso
            if ($medida->circunferencia_cintura && $medida->circunferencia_caderas && $medida->circunferencia_cuello) {
                $masa_grasa = (495 / (1.0324 - 0.19077 * log10($medida->circunferencia_cintura - $medida->circunferencia_cuello) + 0.15456 * log10($medida->talla)) - 450) * $medida->peso / 100;
            } else {
                $masa_grasa = ($porcentaje_grasa / 100) * $medida->peso;
            }
        }
        
        // 5. Cálculo de Masa Magra (kg)
        $masa_magra = $medida->peso - $masa_grasa;
        
        // 6. Cálculo de Masa Muscular (kg)
        $masa_muscular = $masa_magra * 0.55;
        
        // 7. Cálculo de % de Músculo
        $porcentaje_musculo = ($masa_muscular / $medida->peso) * 100;
        
        // 8. Cálculo de ICC (Índice Cintura/Cadera)
        $icc = null;
        if ($medida->circunferencia_cintura && $medida->circunferencia_caderas) {
            $icc = $medida->circunferencia_cintura / $medida->circunferencia_caderas;
        }

        // Buscar si ya existe un progreso para esta medida
        $progresoExistente = Progreso::where('id_medida', $medida->id)->first();
        
        if ($progresoExistente) {
            // Actualizar progreso existente
            $progresoExistente->update([
                'fecha' => now(),
                'imc' => round($imc, 2),
                'tmb' => round($tmb, 2),
                'porcentaje_grasa' => round($porcentaje_grasa, 2),
                'masa_grasa' => round($masa_grasa, 2),
                'masa_magra' => round($masa_magra, 2),
                'masa_muscular' => round($masa_muscular, 2),
                'porcentaje_musculo' => round($porcentaje_musculo, 2),
                'progreso' => $medida->estado_fisico, // Usar estado_fisico como progreso
            ]);
        } else {
            // Crear nuevo registro de progreso
            Progreso::create([
                'id_medida' => $medida->id,
                'fecha' => now(),
                'imc' => round($imc, 2),
                'tmb' => round($tmb, 2),
                'porcentaje_grasa' => round($porcentaje_grasa, 2),
                'masa_grasa' => round($masa_grasa, 2),
                'masa_magra' => round($masa_magra, 2),
                'masa_muscular' => round($masa_muscular, 2),
                'porcentaje_musculo' => round($porcentaje_musculo, 2),
                'progreso' => $medida->estado_fisico, // Usar estado_fisico como progreso
            ]);
        }
    }
}
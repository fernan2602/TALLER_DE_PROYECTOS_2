<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObjetivosSeeder extends Seeder
{
    public function run()  
    {
      // En tu seeder o migración
        DB::table('objetivos')->insert([
            // OBJETIVOS FÍSICOS
            [
                'nombre' => 'Pérdida de peso',
                'descripcion' => 'Reducir grasa corporal y alcanzar un peso saludable mediante ejercicio y dieta balanceada',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nombre' => 'Ganancia muscular',
                'descripcion' => 'Aumentar masa muscular mediante entrenamiento de fuerza y superávit calórico controlado',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nombre' => 'Mejora cardiovascular',
                'descripcion' => 'Incrementar resistencia y salud del corazón con ejercicio aeróbico regular',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nombre' => 'Aumento de fuerza',
                'descripcion' => 'Desarrollar fuerza máxima mediante entrenamiento con cargas progresivas',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nombre' => 'Definición muscular',
                'descripcion' => 'Reducir porcentaje de grasa manteniendo masa muscular para mayor definición',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            
            // OBJETIVOS NUTRICIONALES
            [
                'nombre' => 'Control de calorías',
                'descripcion' => 'Manejar consumo calórico según objetivos de peso y composición corporal',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nombre' => 'Balance de macronutrientes',
                'descripcion' => 'Optimizar proporción de proteínas, carbohidratos y grasas según necesidades',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nombre' => 'Hidratación óptima',
                'descripcion' => 'Mantener adecuada hidratación para rendimiento físico y salud general',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nombre' => 'Suplementación deportiva',
                'descripcion' => 'Uso estratégico de suplementos para potenciar resultados deportivos',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nombre' => 'Alimentación limpia',
                'descripcion' => 'Enfoque en alimentos naturales y mínimamente procesados para mejor salud',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            
            // OBJETIVOS MIXTOS
            [
                'nombre' => 'Recomposición corporal',
                'descripcion' => 'Perder grasa y ganar músculo simultáneamente mediante dieta y ejercicio precisos',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nombre' => 'Rendimiento deportivo',
                'descripcion' => 'Mejorar capacidades físicas específicas para deporte o disciplina particular',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nombre' => 'Salud y bienestar',
                'descripcion' => 'Enfoque integral en salud física, mental y nutricional para calidad de vida',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nombre' => 'Preparación competitiva',
                'descripcion' => 'Programa específico para competencias, shows o eventos deportivos',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'nombre' => 'Mantenimiento',
                'descripcion' => 'Conservar logros alcanzados mediante hábitos sostenibles de ejercicio y nutrición',
                'created_at' => now(), 
                'updated_at' => now()
            ]
        ]);  
    }

}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlimentosSeeder extends Seeder
{
    public function run()
    {
        DB::table('alimentos')->insert([
            // ==================== PROTEÍNAS ====================
            [
                'nombre' => 'Pechuga de pollo',
                'categoria' => 'carne',
                'proteina' => 31.0, 'carbohidratos' => 0.0, 'grasas' => 3.6, 'fibra' => 0.0, 'azucar' => 0.0,
                'calorias' => 165, 'sodio' => 74, 'potasio' => 256, 'calcio' => 15, 'hierro' => 1.0,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Salmón',
                'categoria' => 'carne',
                'proteina' => 25.0, 'carbohidratos' => 0.0, 'grasas' => 13.0, 'fibra' => 0.0, 'azucar' => 0.0,
                'calorias' => 208, 'sodio' => 59, 'potasio' => 384, 'calcio' => 12, 'hierro' => 0.8,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Atún en agua',
                'categoria' => 'carne',
                'proteina' => 25.0, 'carbohidratos' => 0.0, 'grasas' => 1.0, 'fibra' => 0.0, 'azucar' => 0.0,
                'calorias' => 116, 'sodio' => 50, 'potasio' => 237, 'calcio' => 8, 'hierro' => 1.0,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Carne de res magra',
                'categoria' => 'carne',
                'proteina' => 26.0, 'carbohidratos' => 0.0, 'grasas' => 15.0, 'fibra' => 0.0, 'azucar' => 0.0,
                'calorias' => 250, 'sodio' => 65, 'potasio' => 318, 'calcio' => 12, 'hierro' => 2.6,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Huevo entero',
                'categoria' => 'otro',
                'proteina' => 13.0, 'carbohidratos' => 1.1, 'grasas' => 11.0, 'fibra' => 0.0, 'azucar' => 1.1,
                'calorias' => 155, 'sodio' => 124, 'potasio' => 126, 'calcio' => 50, 'hierro' => 1.8,
                'unidad_medida' => 'unidad', 'tamanio_porcion' => 50, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Clara de huevo',
                'categoria' => 'otro',
                'proteina' => 11.0, 'carbohidratos' => 0.7, 'grasas' => 0.2, 'fibra' => 0.0, 'azucar' => 0.7,
                'calorias' => 52, 'sodio' => 166, 'potasio' => 163, 'calcio' => 7, 'hierro' => 0.1,
                'unidad_medida' => 'unidad', 'tamanio_porcion' => 33, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Queso cottage',
                'categoria' => 'lacteo',
                'proteina' => 11.0, 'carbohidratos' => 3.4, 'grasas' => 4.3, 'fibra' => 0.0, 'azucar' => 2.7,
                'calorias' => 98, 'sodio' => 364, 'potasio' => 104, 'calcio' => 83, 'hierro' => 0.1,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Yogur griego natural',
                'categoria' => 'lacteo',
                'proteina' => 10.0, 'carbohidratos' => 3.6, 'grasas' => 0.4, 'fibra' => 0.0, 'azucar' => 3.6,
                'calorias' => 59, 'sodio' => 36, 'potasio' => 141, 'calcio' => 110, 'hierro' => 0.1,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Leche desnatada',
                'categoria' => 'lacteo',
                'proteina' => 3.4, 'carbohidratos' => 5.0, 'grasas' => 0.2, 'fibra' => 0.0, 'azucar' => 5.0,
                'calorias' => 34, 'sodio' => 42, 'potasio' => 156, 'calcio' => 125, 'hierro' => 0.0,
                'unidad_medida' => 'ml', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Tofu firme',
                'categoria' => 'otro',
                'proteina' => 8.0, 'carbohidratos' => 1.9, 'grasas' => 4.8, 'fibra' => 0.3, 'azucar' => 0.6,
                'calorias' => 76, 'sodio' => 7, 'potasio' => 121, 'calcio' => 350, 'hierro' => 1.1,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],

            // ==================== CARBOHIDRATOS ====================
            [
                'nombre' => 'Arroz integral cocido',
                'categoria' => 'cereal',
                'proteina' => 2.6, 'carbohidratos' => 23.0, 'grasas' => 0.9, 'fibra' => 1.8, 'azucar' => 0.4,
                'calorias' => 112, 'sodio' => 1, 'potasio' => 43, 'calcio' => 10, 'hierro' => 0.4,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Avena en hojuelas',
                'categoria' => 'cereal',
                'proteina' => 13.0, 'carbohidratos' => 66.0, 'grasas' => 7.0, 'fibra' => 10.0, 'azucar' => 1.0,
                'calorias' => 379, 'sodio' => 6, 'potasio' => 362, 'calcio' => 52, 'hierro' => 4.3,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Pan integral',
                'categoria' => 'cereal',
                'proteina' => 13.0, 'carbohidratos' => 41.0, 'grasas' => 3.5, 'fibra' => 7.0, 'azucar' => 5.0,
                'calorias' => 247, 'sodio' => 455, 'potasio' => 254, 'calcio' => 107, 'hierro' => 2.5,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Quinoa cocida',
                'categoria' => 'cereal',
                'proteina' => 4.4, 'carbohidratos' => 21.0, 'grasas' => 1.9, 'fibra' => 2.8, 'azucar' => 0.9,
                'calorias' => 120, 'sodio' => 7, 'potasio' => 172, 'calcio' => 17, 'hierro' => 1.5,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Pasta integral cocida',
                'categoria' => 'cereal',
                'proteina' => 5.0, 'carbohidratos' => 25.0, 'grasas' => 0.5, 'fibra' => 3.5, 'azucar' => 1.0,
                'calorias' => 124, 'sodio' => 2, 'potasio' => 44, 'calcio' => 10, 'hierro' => 1.0,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Batata cocida',
                'categoria' => 'verdura',
                'proteina' => 1.6, 'carbohidratos' => 20.0, 'grasas' => 0.1, 'fibra' => 3.0, 'azucar' => 6.5,
                'calorias' => 86, 'sodio' => 55, 'potasio' => 337, 'calcio' => 30, 'hierro' => 0.6,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Maíz dulce',
                'categoria' => 'verdura',
                'proteina' => 3.3, 'carbohidratos' => 19.0, 'grasas' => 1.4, 'fibra' => 2.4, 'azucar' => 3.2,
                'calorias' => 86, 'sodio' => 15, 'potasio' => 270, 'calcio' => 2, 'hierro' => 0.5,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Garbanzos cocidos',
                'categoria' => 'otro',
                'proteina' => 8.0, 'carbohidratos' => 27.0, 'grasas' => 2.4, 'fibra' => 8.0, 'azucar' => 4.8,
                'calorias' => 139, 'sodio' => 7, 'potasio' => 291, 'calcio' => 49, 'hierro' => 2.9,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Lentejas cocidas',
                'categoria' => 'otro',
                'proteina' => 9.0, 'carbohidratos' => 20.0, 'grasas' => 0.4, 'fibra' => 8.0, 'azucar' => 1.8,
                'calorias' => 116, 'sodio' => 2, 'potasio' => 369, 'calcio' => 19, 'hierro' => 3.3,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Plátano',
                'categoria' => 'fruta',
                'proteina' => 1.1, 'carbohidratos' => 22.8, 'grasas' => 0.3, 'fibra' => 2.6, 'azucar' => 12.2,
                'calorias' => 89, 'sodio' => 1, 'potasio' => 358, 'calcio' => 5, 'hierro' => 0.3,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],

            // ==================== GRASAS ====================
            [
                'nombre' => 'Aguacate',
                'categoria' => 'fruta',
                'proteina' => 2.0, 'carbohidratos' => 8.5, 'grasas' => 14.7, 'fibra' => 6.7, 'azucar' => 0.7,
                'calorias' => 160, 'sodio' => 7, 'potasio' => 485, 'calcio' => 12, 'hierro' => 0.6,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Almendras',
                'categoria' => 'otro',
                'proteina' => 21.0, 'carbohidratos' => 22.0, 'grasas' => 50.0, 'fibra' => 12.0, 'azucar' => 4.4,
                'calorias' => 579, 'sodio' => 1, 'potasio' => 733, 'calcio' => 269, 'hierro' => 3.7,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Nueces',
                'categoria' => 'otro',
                'proteina' => 15.0, 'carbohidratos' => 14.0, 'grasas' => 65.0, 'fibra' => 7.0, 'azucar' => 2.6,
                'calorias' => 654, 'sodio' => 2, 'potasio' => 441, 'calcio' => 98, 'hierro' => 2.9,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Aceite de oliva',
                'categoria' => 'otro',
                'proteina' => 0.0, 'carbohidratos' => 0.0, 'grasas' => 100.0, 'fibra' => 0.0, 'azucar' => 0.0,
                'calorias' => 884, 'sodio' => 0, 'potasio' => 1, 'calcio' => 1, 'hierro' => 0.6,
                'unidad_medida' => 'ml', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Mantequilla de maní',
                'categoria' => 'otro',
                'proteina' => 25.0, 'carbohidratos' => 20.0, 'grasas' => 50.0, 'fibra' => 6.0, 'azucar' => 9.0,
                'calorias' => 588, 'sodio' => 459, 'potasio' => 649, 'calcio' => 49, 'hierro' => 1.9,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Semillas de chía',
                'categoria' => 'otro',
                'proteina' => 17.0, 'carbohidratos' => 42.0, 'grasas' => 31.0, 'fibra' => 34.0, 'azucar' => 0.0,
                'calorias' => 486, 'sodio' => 16, 'potasio' => 407, 'calcio' => 631, 'hierro' => 7.7,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Coco rallado',
                'categoria' => 'fruta',
                'proteina' => 3.3, 'carbohidratos' => 15.0, 'grasas' => 33.0, 'fibra' => 9.0, 'azucar' => 6.2,
                'calorias' => 354, 'sodio' => 20, 'potasio' => 356, 'calcio' => 14, 'hierro' => 2.4,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Queso cheddar',
                'categoria' => 'lacteo',
                'proteina' => 25.0, 'carbohidratos' => 1.3, 'grasas' => 33.0, 'fibra' => 0.0, 'azucar' => 0.5,
                'calorias' => 404, 'sodio' => 621, 'potasio' => 98, 'calcio' => 721, 'hierro' => 0.7,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Salmón ahumado',
                'categoria' => 'carne',
                'proteina' => 22.0, 'carbohidratos' => 0.0, 'grasas' => 12.0, 'fibra' => 0.0, 'azucar' => 0.0,
                'calorias' => 117, 'sodio' => 784, 'potasio' => 175, 'calcio' => 11, 'hierro' => 0.9,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Aceitunas verdes',
                'categoria' => 'verdura',
                'proteina' => 1.0, 'carbohidratos' => 6.0, 'grasas' => 11.0, 'fibra' => 3.2, 'azucar' => 0.0,
                'calorias' => 115, 'sodio' => 872, 'potasio' => 8, 'calcio' => 52, 'hierro' => 0.5,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],

            // ==================== VEGETALES ====================
            [
                'nombre' => 'Brócoli cocido',
                'categoria' => 'verdura',
                'proteina' => 2.4, 'carbohidratos' => 7.0, 'grasas' => 0.4, 'fibra' => 3.3, 'azucar' => 1.4,
                'calorias' => 35, 'sodio' => 41, 'potasio' => 293, 'calcio' => 40, 'hierro' => 0.7,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Espinaca cruda',
                'categoria' => 'verdura',
                'proteina' => 2.9, 'carbohidratos' => 3.6, 'grasas' => 0.4, 'fibra' => 2.2, 'azucar' => 0.4,
                'calorias' => 23, 'sodio' => 79, 'potasio' => 558, 'calcio' => 99, 'hierro' => 2.7,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Zanahoria cruda',
                'categoria' => 'verdura',
                'proteina' => 0.9, 'carbohidratos' => 10.0, 'grasas' => 0.2, 'fibra' => 2.8, 'azucar' => 4.7,
                'calorias' => 41, 'sodio' => 69, 'potasio' => 320, 'calcio' => 33, 'hierro' => 0.3,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Tomate',
                'categoria' => 'verdura',
                'proteina' => 0.9, 'carbohidratos' => 3.9, 'grasas' => 0.2, 'fibra' => 1.2, 'azucar' => 2.6,
                'calorias' => 18, 'sodio' => 5, 'potasio' => 237, 'calcio' => 10, 'hierro' => 0.3,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Pimiento rojo',
                'categoria' => 'verdura',
                'proteina' => 0.9, 'carbohidratos' => 6.0, 'grasas' => 0.3, 'fibra' => 2.1, 'azucar' => 4.2,
                'calorias' => 31, 'sodio' => 4, 'potasio' => 211, 'calcio' => 7, 'hierro' => 0.4,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Cebolla',
                'categoria' => 'verdura',
                'proteina' => 1.1, 'carbohidratos' => 9.0, 'grasas' => 0.1, 'fibra' => 1.7, 'azucar' => 4.2,
                'calorias' => 40, 'sodio' => 4, 'potasio' => 146, 'calcio' => 23, 'hierro' => 0.2,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Ajo',
                'categoria' => 'verdura',
                'proteina' => 6.4, 'carbohidratos' => 33.0, 'grasas' => 0.5, 'fibra' => 2.1, 'azucar' => 1.0,
                'calorias' => 149, 'sodio' => 17, 'potasio' => 401, 'calcio' => 181, 'hierro' => 1.7,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Coliflor',
                'categoria' => 'verdura',
                'proteina' => 1.9, 'carbohidratos' => 5.0, 'grasas' => 0.3, 'fibra' => 2.0, 'azucar' => 1.9,
                'calorias' => 25, 'sodio' => 30, 'potasio' => 299, 'calcio' => 22, 'hierro' => 0.4,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Calabacín',
                'categoria' => 'verdura',
                'proteina' => 1.2, 'carbohidratos' => 3.1, 'grasas' => 0.3, 'fibra' => 1.0, 'azucar' => 2.5,
                'calorias' => 17, 'sodio' => 8, 'potasio' => 261, 'calcio' => 16, 'hierro' => 0.4,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Berenjena',
                'categoria' => 'verdura',
                'proteina' => 1.0, 'carbohidratos' => 6.0, 'grasas' => 0.2, 'fibra' => 3.0, 'azucar' => 3.5,
                'calorias' => 25, 'sodio' => 2, 'potasio' => 229, 'calcio' => 9, 'hierro' => 0.2,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],

            // ==================== FRUTAS ====================
            [
                'nombre' => 'Manzana',
                'categoria' => 'fruta',
                'proteina' => 0.3, 'carbohidratos' => 14.0, 'grasas' => 0.2, 'fibra' => 2.4, 'azucar' => 10.4,
                'calorias' => 52, 'sodio' => 1, 'potasio' => 107, 'calcio' => 6, 'hierro' => 0.1,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Naranja',
                'categoria' => 'fruta',
                'proteina' => 0.9, 'carbohidratos' => 11.8, 'grasas' => 0.1, 'fibra' => 2.4, 'azucar' => 9.4,
                'calorias' => 47, 'sodio' => 0, 'potasio' => 181, 'calcio' => 40, 'hierro' => 0.1,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Fresa',
                'categoria' => 'fruta',
                'proteina' => 0.7, 'carbohidratos' => 7.7, 'grasas' => 0.3, 'fibra' => 2.0, 'azucar' => 4.9,
                'calorias' => 32, 'sodio' => 1, 'potasio' => 153, 'calcio' => 16, 'hierro' => 0.4,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Uvas rojas',
                'categoria' => 'fruta',
                'proteina' => 0.6, 'carbohidratos' => 18.0, 'grasas' => 0.4, 'fibra' => 0.9, 'azucar' => 16.0,
                'calorias' => 69, 'sodio' => 2, 'potasio' => 191, 'calcio' => 10, 'hierro' => 0.4,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Piña',
                'categoria' => 'fruta',
                'proteina' => 0.5, 'carbohidratos' => 13.0, 'grasas' => 0.1, 'fibra' => 1.4, 'azucar' => 10.0,
                'calorias' => 50, 'sodio' => 1, 'potasio' => 109, 'calcio' => 13, 'hierro' => 0.3,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Mango',
                'categoria' => 'fruta',
                'proteina' => 0.8, 'carbohidratos' => 15.0, 'grasas' => 0.4, 'fibra' => 1.6, 'azucar' => 14.0,
                'calorias' => 60, 'sodio' => 1, 'potasio' => 168, 'calcio' => 11, 'hierro' => 0.2,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Pera',
                'categoria' => 'fruta',
                'proteina' => 0.4, 'carbohidratos' => 15.0, 'grasas' => 0.1, 'fibra' => 3.1, 'azucar' => 10.0,
                'calorias' => 57, 'sodio' => 1, 'potasio' => 116, 'calcio' => 9, 'hierro' => 0.2,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Melón',
                'categoria' => 'fruta',
                'proteina' => 0.8, 'carbohidratos' => 8.0, 'grasas' => 0.2, 'fibra' => 0.9, 'azucar' => 8.0,
                'calorias' => 34, 'sodio' => 16, 'potasio' => 267, 'calcio' => 9, 'hierro' => 0.2,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Sandía',
                'categoria' => 'fruta',
                'proteina' => 0.6, 'carbohidratos' => 7.6, 'grasas' => 0.2, 'fibra' => 0.4, 'azucar' => 6.2,
                'calorias' => 30, 'sodio' => 1, 'potasio' => 112, 'calcio' => 7, 'hierro' => 0.2,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Kiwi',
                'categoria' => 'fruta',
                'proteina' => 1.1, 'carbohidratos' => 14.7, 'grasas' => 0.5, 'fibra' => 3.0, 'azucar' => 9.0,
                'calorias' => 61, 'sodio' => 3, 'potasio' => 312, 'calcio' => 34, 'hierro' => 0.3,
                'unidad_medida' => 'g', 'tamanio_porcion' => 100, 'created_at' => now(), 'updated_at' => now()
            ]
        ]);
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('alimentos', function (Blueprint $table) {
        $table->id('id');
        $table->string('nombre', 100);
        $table->enum('categoria', ['fruta','verdura','carne','cereal','lacteo','otro']);
        
        // Macronutrientes (por 100g)
        $table->decimal('proteina', 8, 2)->default(0)->comment('Gramos de proteína por 100g');
        $table->decimal('carbohidratos', 8, 2)->default(0)->comment('Gramos de carbohidratos por 100g');
        $table->decimal('grasas', 8, 2)->default(0)->comment('Gramos de grasas por 100g');
        $table->decimal('fibra', 8, 2)->default(0)->comment('Gramos de fibra por 100g');
        $table->decimal('azucar', 8, 2)->default(0)->comment('Gramos de azúcar por 100g');
        
        // Energía
        $table->decimal('calorias', 8, 2)->default(0)->comment('Calorías por 100g');
        
        // Micronutrientes importantes
        $table->decimal('sodio', 8, 2)->default(0)->comment('Miligramos de sodio por 100g');
        $table->decimal('potasio', 8, 2)->default(0)->comment('Miligramos de potasio por 100g');
        $table->decimal('calcio', 8, 2)->default(0)->comment('Miligramos de calcio por 100g');
        $table->decimal('hierro', 8, 2)->default(0)->comment('Miligramos de hierro por 100g');
        
        // Información adicional
        $table->string('unidad_medida')->default('g')->comment('Unidad de medida (g, ml, unidad)');
        $table->decimal('tamanio_porcion', 8, 2)->default(100)->comment('Tamaño de porción estándar');
        
        $table->timestamps();
    });
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::dropIfExists('alimentos'); // Corregí esto - debe ser 'alimentos' no 'alimento'
}
};

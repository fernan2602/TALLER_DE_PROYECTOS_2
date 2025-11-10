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
    Schema::create('progreso', function (Blueprint $table) {
        $table->id('id_progreso');
        $table->foreignId('id_medida')
            ->constrained('medidas')
            ->onDelete('cascade');
        $table->date('fecha');
        $table->decimal('imc', 5,2)->nullable();
        $table->decimal('tmb', 7,2)->nullable();
        $table->decimal('porcentaje_grasa', 5,2)->nullable();
        $table->decimal('masa_grasa', 5,2)->nullable();
        $table->decimal('masa_magra', 5,2)->nullable();
        $table->decimal('masa_muscular', 5,2)->nullable();
        $table->decimal('porcentaje_musculo', 5,2)->nullable();
        $table->integer('progreso');
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progreso');
    }
};

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
        Schema::create('asignacion_objetivo', function (Blueprint $table) {
            $table->id('id_asignacion');
            $table->foreignId('id_usuario')
                ->constrained('usuarios')
                ->onDelete('cascade');
            $table->foreignId('id_objetivo')
                ->constrained('objetivos')
                ->onDelete('cascade');
            $table->date('fecha_asignacion');
            $table->enum('estado', ['activo','completado','pendiente'])->default('pendiente');
            $table->string('calificacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_objetivo');
    }
};

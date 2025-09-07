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
        Schema::create('promedio', function (Blueprint $table) {
            $table->id();
            $table->string('edad'); 
            $table->enum('genero', ['masculino', 'femenino']);
            $table->decimal('pliegue_tricipital');
            $table->decimal('fuerza')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promedio');
    }
};

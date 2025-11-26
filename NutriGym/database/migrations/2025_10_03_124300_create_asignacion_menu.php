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
    Schema::create('asignacion_menus', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('id_usuario')
            ->constrained('usuarios')
            ->onDelete('cascade');
        $table->integer('calorias');
        $table->enum('tipo', ['desayuno', 'almuerzo', 'cena', 'otro']); 
        $table->date('fecha_asignacion');
        $table->boolean('validado')->default(false); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_menus'); 
    }
};
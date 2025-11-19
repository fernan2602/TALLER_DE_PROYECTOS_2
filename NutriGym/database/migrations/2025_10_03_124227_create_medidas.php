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
    Schema::create('medidas', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('id_usuario')
            ->constrained('usuarios')
            ->onDelete('cascade');
        $table->decimal('peso', 5,2);
        $table->decimal('talla', 5,2);
        $table->integer('edad');
        $table->enum('genero', ['M','F']);
        $table->decimal('circunferencia_brazo', 5,2)->nullable();
        $table->decimal('circunferencia_antebrazo', 5,2)->nullable();
        $table->decimal('circunferencia_cintura', 5,2)->nullable();
        $table->decimal('circunferencia_caderas', 5,2)->nullable();
        $table->decimal('circunferencia_muslos', 5,2)->nullable();
        $table->decimal('circunferencia_pantorrilla', 5,2)->nullable();
        $table->decimal('circunferencia_cuello', 5,2)->nullable();
        $table->timestamp('fecha_registro')->useCurrent();
        $table->integer('estado_fisico');
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medidas');
    }
};

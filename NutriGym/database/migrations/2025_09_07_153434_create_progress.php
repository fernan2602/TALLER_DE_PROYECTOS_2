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
            Schema::create('progresos', function (Blueprint $table) {
                $table->id();
                $table->decimal('area_muscular_brazo');
                $table->decimal('area_muscular_pantorrilla');
                $table->decimal('area_muscular_muslos');
                $table->decimal('masa_muscular');
                $table->decimal('indice_cintura_cadera');
                $table->decimal('indice_fuerza');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};

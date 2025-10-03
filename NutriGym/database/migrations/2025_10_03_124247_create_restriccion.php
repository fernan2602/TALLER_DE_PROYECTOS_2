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
        Schema::create('restriccion', function (Blueprint $table) {
            $table->id('id_restriccion');
            $table->foreignId('id_preferencia')
                ->constrained('preferencias')
                ->onDelete('cascade');
            $table->foreignId('id_alimento')
                ->constrained('alimentos')
                ->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restriccion');
    }
};

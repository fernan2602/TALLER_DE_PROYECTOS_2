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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id');
            $table->string('nombre', 100);
            $table->string('email')->unique();
            $table->date('fecha_nacimiento');
            $table->string('contrasena');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamp('fecha_cese')->nullable();
            $table->foreignId('id_rol')->default(4)
                ->constrained('roles')
                ->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};

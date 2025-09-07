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
        Schema::create('registers', function (Blueprint $table) {
        $table->id();
        $table->decimal('peso'); // 
        $table->decimal('talla'); //
        $table->integer('edad');
        $table->enum('genero', ['masculino', 'femenino', 'otro']);
        $table->timestamps();
    });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('register');
    }
};

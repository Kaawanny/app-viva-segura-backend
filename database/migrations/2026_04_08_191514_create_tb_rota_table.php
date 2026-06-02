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
        Schema::create('tbRota', function (Blueprint $table) {
            $table->id('id_rota');
            $table->string('origemLatitude');
            $table->string('origemLongitude');
            $table->string('destinoLatitude');
            $table->string('destinoLongitude');
            $table->string('tempoPrevisto');
            $table->foreignId('id_usuaria')->constrained('tbUsuaria', 'id_usuaria');
            
            $table->date('dataCriacao');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbRota');
    }
};

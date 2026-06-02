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
        Schema::create('tbPontosRota', function (Blueprint $table) {
            $table->id('id_pontosRota');
            $table->string('latitude');
            $table->string('longitude');
            $table->foreignId('id_rota')->constrained('tbRota', 'id_rota');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbPontosRota');
    }
};

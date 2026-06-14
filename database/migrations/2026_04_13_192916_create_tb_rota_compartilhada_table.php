<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_rota_compartilhada', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuaria');
            $table->unsignedBigInteger('id_guardiao');
            $table->decimal('origemLatitude', 10, 7);
            $table->decimal('origemLongitude', 10, 7);
            $table->decimal('destinoLatitude', 10, 7);
            $table->decimal('destinoLongitude', 10, 7);
            $table->string('endereco_destino')->nullable();
            $table->enum('status', ['ativa', 'encerrada', 'chegou'])->default('ativa'); // Status da usuária 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_rota_compartilhada');
    }
};

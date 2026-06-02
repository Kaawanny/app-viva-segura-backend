<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_local_seguro', function (Blueprint $table) {
            $table->id('id_localSeguro');
            $table->string('nome');
            $table->string('tipo'); // delegacia | saude | apoio
            $table->string('endereco');
            $table->decimal('latitude',  10, 7);
            $table->decimal('longitude', 10, 7);
            $table->boolean('ativo')->default(true);
            // $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_local_seguro');
    }
};

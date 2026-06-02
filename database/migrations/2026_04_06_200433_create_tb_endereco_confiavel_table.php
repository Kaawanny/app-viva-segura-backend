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
        Schema::create('tbEnderecoConfiavel', function (Blueprint $table) {
            $table->id('id_endereco');
            $table->string('nomeLocal');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('raioSeguro');
            $table->string('logradouro')->nullable();
            $table->string('numLogra')->nullable();
            $table->string('cep')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('estado')->nullable();
            $table->foreignId('id_usuaria')->constrained('tbUsuaria', 'id_usuaria');
            $table->date('dataCriacao');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbEnderecoConfiavel');
    }
};

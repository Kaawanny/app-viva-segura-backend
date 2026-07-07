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
        Schema::create('tbUsuaria', function (Blueprint $table) {
            $table->id('id_usuaria');
            $table->string('nome', 60);
            $table->string('cpf', 11)->unique();
            $table->string('email')->unique();
            $table->string('senha');
            $table->date('dataNasc');
            $table->string('telefone')->unique();
            $table->string('codigo_convite')->unique();
            $table->foreignId('id_role')->constrained('tbRole', 'id_role');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbUsuaria');
    }
};

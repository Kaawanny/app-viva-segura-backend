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
        Schema::create('tb_usuaria_role', function (Blueprint $table) {
            $table->id('id_usuaria_role');

            // Indica que id_role aponta para a tabela tbRole na coluna id_role
            $table->foreignId('id_role')->constrained('tbRole', 'id_role');

            // Indica que id_usuaria aponta para a tabela tbUsuaria na coluna id_usuaria
            $table->foreignId('id_usuaria')->constrained('tbUsuaria', 'id_usuaria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_usuaria_role');
    }
};

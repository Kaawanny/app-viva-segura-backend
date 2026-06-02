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
        Schema::create('tbVinculo', function (Blueprint $table) {
            $table->id('id');
            $table->date('dataSolicitacao');
            $table->date('dataResposta');
            $table->enum('statusVinculo', ['pendente', 'aceito', 'recusado'])->default('pendente');

            $table->foreignId('id_usuaria')->constrained('tbUsuaria', 'id_usuaria');
            $table->foreignId('id_guardiao')->constrained('tbUsuaria', 'id_usuaria');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbVinculo');
    }
};

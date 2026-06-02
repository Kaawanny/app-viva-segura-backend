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
        Schema::create('tbSuportePergunta', function (Blueprint $table) {
            $table->id('idSuportePergunta');
            $table->string('assunto');
            $table->string('mensagemUsuaria');
            $table->string('respostaAdm')->nullable();
            $table->enum('status', ['não-respondida', 'spam', 'respondida'])->default('não-respondida');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_suporte_pergunta');
    }
};

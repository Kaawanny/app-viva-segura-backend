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
        Schema::create('tbAlerta', function (Blueprint $table) {
            $table->id('id_alerta');
            $table->enum('statusAlerta', ['pendente', 'ativo', 'resolvido'])->default('pendente');
            $table->string('desc');
            $table->string('latitude');
            $table->string('longitude');
            $table->dateTime('dataHoraAlerta');

            $table->foreignId('id_tipoAlerta')->constrained('tbTipoAlerta', 'id_tipoAlerta');
            $table->foreignId('id_usuaria')->constrained('tbUsuaria', 'id_usuaria');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbAlerta');
    }
};

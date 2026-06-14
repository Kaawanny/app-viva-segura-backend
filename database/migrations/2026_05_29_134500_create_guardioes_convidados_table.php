<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guardioes_convidados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuaria');
            $table->string('nome');
            $table->string('email');
            $table->string('status', 20)->default('pendente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guardioes_convidados');
    }
};

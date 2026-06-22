<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('tb_endereco_usuaria', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_usuaria');
            $table->string('endereco');
            $table->string('complemento')->nullable();
            $table->string('descricao');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
            $table->foreign('id_usuaria')->references('id_usuaria')->on('tbusuaria')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('tb_endereco_usuaria');
    }
};
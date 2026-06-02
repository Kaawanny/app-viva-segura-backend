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
        Schema::create('tb_endereco_usuaria', function (Blueprint $table) {
             $table->id('idEnderecoUsuaria'); 
           $table->string('enderecoUsuaria'); 
               $table->string('complementoEnderecoUsuaria'); 
               $table->string('descricaoEnderecoUsuaria');  
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_endereco_usuaria');
    }
};

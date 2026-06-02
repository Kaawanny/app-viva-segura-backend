<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tbUsuaria', function (Blueprint $table) {
            $table->string('cpf')->nullable()->change();
            $table->date('dataNasc')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tbUsuaria', function (Blueprint $table) {
            $table->string('cpf')->nullable(false)->change();
            $table->date('dataNasc')->nullable(false)->change();
        });
    }
};

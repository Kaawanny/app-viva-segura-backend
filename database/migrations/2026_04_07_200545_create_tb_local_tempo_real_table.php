<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbLocalTempoReal', function (Blueprint $table) {
            $table->id('id_localTempoReal');

            $table->string('latitude');
            $table->string('longitude');

            $table->foreignId('id_usuaria')
                ->unique()
                ->constrained('tbUsuaria', 'id_usuaria');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbLocalTempoReal');
    }
};
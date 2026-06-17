<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tbusuaria', function (Blueprint $table) {
            $table->string('push_token')->nullable()->after('codigo_convite');
        });
    }

    public function down()
    {
        Schema::table('tbusuaria', function (Blueprint $table) {
            $table->dropColumn('push_token');
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->text('pj')->nullable()->change(); // Changer en TEXT pour stocker JSON
        });
    }

    public function down()
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->string('pj')->nullable()->change(); // Retour à STRING
        });
    }
};


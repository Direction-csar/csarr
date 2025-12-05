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
        Schema::table('demandes', function (Blueprint $table) {
            $table->enum('statut', ['en_attente', 'en_cours', 'traitee', 'rejetee'])->default('en_attente')->after('type_demande');
            $table->text('reponse')->nullable()->after('statut');
            $table->timestamp('date_traitement')->nullable()->after('reponse');
            $table->unsignedBigInteger('traite_par')->nullable()->after('date_traitement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropColumn(['statut', 'reponse', 'date_traitement', 'traite_par']);
        });
    }
};

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
        Schema::table('personnel', function (Blueprint $table) {
            // Change enum fields to string for more flexibility
            $table->string('statut')->change();
            $table->string('diplome_academique')->change();
            $table->string('taille_vetements')->change();
            $table->string('direction_service')->change();
            $table->string('sexe')->change();
            $table->string('situation_matrimoniale')->change();
            $table->string('localisation_region')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personnel', function (Blueprint $table) {
            // Revert back to enum if needed
            $table->enum('sexe', ['Masculin', 'Feminin'])->change();
            $table->enum('situation_matrimoniale', ['Celibataire', 'Marie', 'Divorce', 'Veuf', 'Veuve'])->change();
            $table->enum('statut', ['Fonctionnaire', 'Contractuel', 'Stagiaire', 'Journalier', 'Autre'])->change();
            $table->enum('diplome_academique', [
                'Doctorat', 'Master', 'DESS', 'Maitrise', 'Licence', 'DEUG',
                'Baccalaureat', 'BFEM', 'CFEE', 'Sans diplome', 'Autre'
            ])->change();
            $table->enum('taille_vetements', ['S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'Autre'])->change();
            $table->enum('direction_service', [
                'Conseil administration', 'Direction Generale', 'Secretariat general',
                'DSAR', 'DFC', 'DPSE', 'DRH', 'DTL', 'CCG', 'CPM', 'CI', 'CIA', 'AC', 'IR'
            ])->change();
            $table->enum('localisation_region', [
                'Dakar', 'Thies', 'Diourbel', 'Fatick', 'Kaffrine', 'Matam',
                'Kaolack', 'Kedougou', 'Louga', 'Saint-Louis', 'Tambacounda',
                'Kolda Sedhiou', 'Ziguinchor'
            ])->nullable()->change();
        });
    }
};

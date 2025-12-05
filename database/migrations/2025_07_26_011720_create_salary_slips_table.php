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
        Schema::create('salary_slips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personnel_id');
            $table->string('numero_bulletin')->unique();
            $table->date('periode_debut');
            $table->date('periode_fin');
            $table->decimal('salaire_brut', 12, 2);
            $table->decimal('salaire_net', 12, 2);
            
            // Déductions
            $table->decimal('cnss', 12, 2)->default(0);
            $table->decimal('impot', 12, 2)->default(0);
            $table->decimal('autres_deductions', 12, 2)->default(0);
            
            // Indemnités
            $table->decimal('indemnite_logement', 12, 2)->default(0);
            $table->decimal('indemnite_transport', 12, 2)->default(0);
            $table->decimal('indemnite_fonction', 12, 2)->default(0);
            $table->decimal('autres_indemnites', 12, 2)->default(0);
            
            // Jours travaillés
            $table->integer('jours_travailles');
            $table->integer('jours_conges');
            $table->integer('jours_absences');
            
            // Statut
            $table->enum('statut', ['brouillon', 'valide', 'paye', 'annule'])->default('brouillon');
            $table->string('fichier_pdf')->nullable();
            $table->text('commentaires')->nullable();
            
            // Audit
            $table->unsignedBigInteger('cree_par');
            $table->unsignedBigInteger('valide_par')->nullable();
            $table->timestamp('date_validation')->nullable();
            $table->timestamp('date_paiement')->nullable();
            
            $table->timestamps();
            
            // Relations
            $table->foreign('personnel_id')->references('id')->on('personnel')->onDelete('cascade');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('valide_par')->references('id')->on('users')->onDelete('set null');
            
            // Index
            $table->index(['personnel_id', 'periode_debut']);
            $table->index('statut');
            $table->index('date_validation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_slips');
    }
};

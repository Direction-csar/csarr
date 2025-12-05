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
        Schema::create('hr_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personnel_id');
            $table->enum('type', [
                'contrat_travail',
                'bulletin_salaire', 
                'certificat_medical',
                'arret_maladie',
                'attestation_travail',
                'certificat_formation',
                'autre'
            ]);
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('fichier');
            $table->string('extension');
            $table->integer('taille_fichier'); // en bytes
            $table->date('date_emission');
            $table->date('date_expiration')->nullable();
            $table->enum('statut', ['actif', 'expire', 'archivé'])->default('actif');
            $table->text('commentaires')->nullable();
            $table->unsignedBigInteger('cree_par');
            $table->timestamps();
            
            // Relations
            $table->foreign('personnel_id')->references('id')->on('personnel')->onDelete('cascade');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('cascade');
            
            // Index
            $table->index(['personnel_id', 'type']);
            $table->index('date_emission');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_documents');
    }
};

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
        Schema::create('personnel', function (Blueprint $table) {
            $table->id();
            
            // I. Informations personnelles
            $table->string('prenoms_nom');
            $table->date('date_naissance');
            $table->string('lieu_naissance');
            $table->enum('tranche_age', ['18-25', '26-35', '36-45', '46-55', '56-60']);
            $table->string('nationalite');
            $table->string('numero_cni');
            $table->enum('sexe', ['Masculin', 'Feminin']);
            $table->enum('situation_matrimoniale', ['Celibataire', 'Marie', 'Divorce', 'Veuf', 'Veuve']);
            $table->integer('nombre_enfants')->default(0);
            $table->string('contact_telephonique');
            $table->string('email');
            $table->enum('groupe_sanguin', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->text('adresse_complete');
            
            // II. Situation administrative
            $table->string('matricule')->unique();
            $table->date('date_recrutement_csar');
            $table->date('date_prise_service_csar');
            $table->enum('statut', ['Fonctionnaire', 'Contractuel', 'Stagiaire', 'Journalier', 'Autre']);
            $table->string('poste_actuel');
            $table->enum('direction_service', [
                'Conseil administration', 'Direction Generale', 'Secretariat general',
                'DSAR', 'DFC', 'DPSE', 'DRH', 'DTL', 'CCG', 'CPM', 'CI', 'CIA', 'AC', 'IR'
            ]);
            $table->enum('localisation_region', [
                'Dakar', 'Thies', 'Diourbel', 'Fatick', 'Kaffrine', 'Matam',
                'Kaolack', 'Kedougou', 'Louga', 'Saint-Louis', 'Tambacounda',
                'Kolda Sedhiou', 'Ziguinchor'
            ])->nullable();
            
            // III. Parcours professionnel
            $table->text('dernier_poste_avant_csar')->nullable();
            $table->text('formations_professionnelles')->nullable();
            $table->enum('diplome_academique', [
                'Doctorat', 'Master', 'DESS', 'Maitrise', 'Licence', 'DEUG',
                'Baccalaureat', 'BFEM', 'CFEE', 'Sans diplome', 'Autre'
            ]);
            $table->text('autres_diplomes_certifications')->nullable();
            
            // IV. Compétences spécifiques
            $table->json('logiciels_maitrises')->nullable();
            $table->json('langues_parlees')->nullable();
            $table->text('autres_aptitudes')->nullable();
            
            // V. Aspirations professionnelles
            $table->text('aspirations_professionnelles')->nullable();
            $table->enum('interet_nouvelles_responsabilites', ['Oui', 'Non', 'Neutre']);
            
            // VI. Photo personnelle
            $table->string('photo_personnelle')->nullable();
            
            // VII. Taille vêtements
            $table->enum('taille_vetements', ['S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'Autre']);
            
            // VIII. Notification d'urgence
            $table->string('contact_urgence_nom');
            $table->string('contact_urgence_telephone');
            $table->string('contact_urgence_lien_parente');
            
            // IX. Observations personnelles
            $table->text('observations_personnelles')->nullable();
            
            // Statut de validation
            $table->enum('statut_validation', ['En attente', 'Valide', 'Rejete'])->default('En attente');
            $table->text('commentaire_validation')->nullable();
            $table->unsignedBigInteger('valide_par')->nullable();
            $table->timestamp('date_validation')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index(['direction_service', 'poste_actuel']);
            $table->index('statut_validation');
            $table->index('localisation_region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel');
    }
};

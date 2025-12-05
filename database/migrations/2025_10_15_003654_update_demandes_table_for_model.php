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
            // Ajouter les champs manquants pour le modèle Demande
            $table->string('code_suivi')->nullable()->after('id');
            $table->string('nom_demandeur')->nullable()->after('code_suivi');
            $table->string('statut')->default('en_attente')->after('nom_demandeur');
            $table->string('region')->nullable()->after('statut');
            $table->string('commune')->nullable()->after('region');
            $table->string('departement')->nullable()->after('commune');
            $table->text('adresse')->nullable()->after('departement');
            $table->date('date_demande')->nullable()->after('adresse');
            $table->date('date_traitement')->nullable()->after('date_demande');
            $table->text('commentaire_admin')->nullable()->after('date_traitement');
            $table->unsignedBigInteger('assignee_id')->nullable()->after('commentaire_admin');
            $table->string('priorite')->default('moyenne')->after('assignee_id');
            $table->decimal('latitude', 10, 8)->nullable()->after('priorite');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->boolean('sms_envoye')->default(false)->after('longitude');
            $table->string('sms_message_id')->nullable()->after('sms_envoye');
            $table->timestamp('sms_envoye_at')->nullable()->after('sms_message_id');
            
            // Ajouter les index
            $table->index('code_suivi');
            $table->index('statut');
            $table->index('region');
            $table->index('assignee_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            // Supprimer les champs ajoutés
            $table->dropColumn([
                'code_suivi', 'nom_demandeur', 'statut', 'region', 'commune', 
                'departement', 'adresse', 'date_demande', 'date_traitement', 
                'commentaire_admin', 'assignee_id', 'priorite', 'latitude', 
                'longitude', 'sms_envoye', 'sms_message_id', 'sms_envoye_at'
            ]);
        });
    }
};

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
        // Supprimer l'ancienne table si elle existe
        Schema::dropIfExists('sim_reports');
        
        // Créer la nouvelle table avec la structure complète
        Schema::create('sim_reports', function (Blueprint $table) {
            $table->id();
            
            // Informations de base
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('report_type', ['daily', 'weekly', 'monthly', 'quarterly', 'annual', 'special'])->default('monthly');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->string('region')->nullable();
            $table->enum('market_sector', ['agriculture', 'livestock', 'fisheries', 'manufacturing', 'services', 'trade', 'all'])->default('all');
            
            // Contenu du rapport
            $table->longText('summary')->nullable();
            $table->longText('context_objectives')->nullable();
            $table->json('supply_level')->nullable(); // Niveau d'approvisionnement
            $table->json('price_analysis')->nullable(); // Analyse des prix
            $table->json('supply_analysis')->nullable(); // Analyse de l'approvisionnement
            $table->json('regional_distribution')->nullable(); // Répartition régionale
            $table->json('regional_analysis')->nullable(); // Analyse régionale
            $table->json('key_trends')->nullable(); // Tendances clés
            $table->longText('recommendations')->nullable(); // Recommandations
            $table->json('annexes')->nullable(); // Annexes (tableaux, cartes)
            $table->text('methodology')->nullable(); // Note méthodologique
            
            // Données techniques
            $table->json('data_sources')->nullable(); // Sources de données
            $table->json('indicators_data')->nullable(); // Données d'indicateurs
            $table->json('attachments')->nullable(); // Pièces jointes
            
            // Statut et publication
            $table->enum('status', ['draft', 'generating', 'completed', 'published', 'archived'])->default('draft');
            $table->boolean('is_published')->default(false);
            $table->boolean('is_public')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('generated_at')->nullable();
            
            // Statistiques
            $table->integer('view_count')->default(0);
            $table->integer('download_count')->default(0);
            
            // Fichiers
            $table->string('document_file')->nullable(); // PDF du rapport
            $table->string('file_path')->nullable(); // Chemin du fichier
            $table->string('cover_image')->nullable(); // Image de couverture
            
            // Relations
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->foreign('generated_by')->references('id')->on('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Index pour les performances
            $table->index(['status', 'is_published']);
            $table->index(['report_type', 'market_sector']);
            $table->index(['period_start', 'period_end']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sim_reports');
    }
};









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
        Schema::create('sim_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('report_date');
            $table->string('period'); // ex: "Juillet 2025"
            $table->text('summary')->nullable();
            $table->longText('context_objectives')->nullable();
            $table->json('supply_level')->nullable(); // Niveau d'approvisionnement
            $table->json('price_analysis')->nullable(); // Analyse des prix
            $table->json('regional_distribution')->nullable(); // Répartition régionale
            $table->json('key_trends')->nullable(); // Tendances clés
            $table->json('recommendations')->nullable(); // Recommandations
            $table->json('annexes')->nullable(); // Annexes (tableaux, cartes)
            $table->text('methodology')->nullable(); // Note méthodologique
            $table->enum('status', ['draft', 'review', 'published', 'archived'])->default('draft');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('document_file')->nullable(); // PDF du rapport
            $table->string('cover_image')->nullable(); // Image de couverture
            $table->timestamps();
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
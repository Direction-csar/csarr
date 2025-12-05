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
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Clé unique pour identifier la statistique
            $table->string('title'); // Titre affiché
            $table->string('description'); // Description/label
            $table->string('value'); // Valeur (peut être numérique ou texte)
            $table->string('icon')->nullable(); // Icône FontAwesome
            $table->string('color')->default('#22c55e'); // Couleur de l'icône
            $table->string('section')->default('about'); // Section (about, home, etc.)
            $table->integer('order')->default(0); // Ordre d'affichage
            $table->boolean('is_active')->default(true); // Actif/inactif
            $table->text('notes')->nullable(); // Notes additionnelles
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('speeches', function (Blueprint $table) {
            $table->id();
            $table->string('author'); // DG, Ministre, etc.
            $table->string('title'); // Titre du discours
            $table->text('excerpt')->nullable(); // Extrait ou citation
            $table->text('content'); // Texte complet
            $table->string('portrait')->nullable(); // Photo de l'auteur
            $table->date('date')->nullable();
            $table->string('function')->nullable(); // Fonction de l'auteur
            $table->string('location')->nullable(); // Lieu du discours
            $table->text('summary')->nullable(); // Résumé du discours
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('speeches');
    }
};

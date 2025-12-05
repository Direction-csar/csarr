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
        Schema::create('entrepots', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('adresse');
            $table->string('ville');
            $table->string('region');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('capacite', 10, 2);
            $table->string('unite_capacite', 50);
            $table->decimal('taux_occupation', 5, 2)->default(0);
            $table->enum('type', ['principal', 'secondaire', 'depot']);
            $table->enum('statut', ['actif', 'inactif', 'maintenance']);
            $table->string('responsable');
            $table->string('telephone_responsable', 20);
            $table->string('email_responsable');
            $table->text('description')->nullable();
            $table->date('date_creation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrepots');
    }
};

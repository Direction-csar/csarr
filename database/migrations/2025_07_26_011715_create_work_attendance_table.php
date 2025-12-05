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
        Schema::create('work_attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personnel_id');
            $table->date('date');
            $table->time('heure_arrivee')->nullable();
            $table->time('heure_depart')->nullable();
            $table->enum('statut', [
                'present',
                'absent',
                'retard',
                'congé',
                'maladie',
                'formation',
                'mission'
            ])->default('present');
            $table->text('justification')->nullable();
            $table->integer('heures_travaillees')->default(0); // en minutes
            $table->boolean('valide')->default(true);
            $table->unsignedBigInteger('valide_par')->nullable();
            $table->timestamp('date_validation')->nullable();
            $table->timestamps();
            
            // Relations
            $table->foreign('personnel_id')->references('id')->on('personnel')->onDelete('cascade');
            $table->foreign('valide_par')->references('id')->on('users')->onDelete('set null');
            
            // Index
            $table->index(['personnel_id', 'date']);
            $table->index('date');
            $table->index('statut');
            $table->unique(['personnel_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_attendance');
    }
};

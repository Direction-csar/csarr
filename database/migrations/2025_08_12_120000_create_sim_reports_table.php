<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sim_reports')) {
            return; // La table existe déjà (environnement SQLite) -> ignorer
        }
        Schema::create('sim_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('report_date')->nullable();
            $table->text('summary')->nullable();
            $table->json('price_analysis')->nullable();
            $table->json('supply_analysis')->nullable();
            $table->json('regional_analysis')->nullable();
            $table->text('recommendations')->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sim_reports');
    }
};



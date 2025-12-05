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
        Schema::table('sim_reports', function (Blueprint $table) {
            // Ajouter les colonnes manquantes
            if (!Schema::hasColumn('sim_reports', 'cover_image')) {
                $table->string('cover_image')->nullable();
            }
            if (!Schema::hasColumn('sim_reports', 'is_public')) {
                $table->boolean('is_public')->default(false);
            }
            if (!Schema::hasColumn('sim_reports', 'view_count')) {
                $table->integer('view_count')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sim_reports', function (Blueprint $table) {
            $table->dropColumn([
                'cover_image',
                'is_public',
                'view_count'
            ]);
        });
    }
};
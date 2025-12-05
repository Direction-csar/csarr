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
            if (!Schema::hasColumn('sim_reports', 'is_public')) {
                $table->boolean('is_public')->default(false)->after('status');
            }
            if (!Schema::hasColumn('sim_reports', 'view_count')) {
                $table->integer('view_count')->default(0)->after('is_public');
            }
            if (!Schema::hasColumn('sim_reports', 'download_count')) {
                $table->integer('download_count')->default(0)->after('view_count');
            }
            if (!Schema::hasColumn('sim_reports', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('download_count');
            }
            if (!Schema::hasColumn('sim_reports', 'generated_at')) {
                $table->timestamp('generated_at')->nullable()->after('published_at');
            }
            if (!Schema::hasColumn('sim_reports', 'generated_by')) {
                $table->unsignedBigInteger('generated_by')->nullable()->after('generated_at');
            }
            if (!Schema::hasColumn('sim_reports', 'data_sources')) {
                $table->json('data_sources')->nullable()->after('generated_by');
            }
            if (!Schema::hasColumn('sim_reports', 'indicators_data')) {
                $table->json('indicators_data')->nullable()->after('data_sources');
            }
            if (!Schema::hasColumn('sim_reports', 'summary')) {
                $table->longText('summary')->nullable()->after('indicators_data');
            }
            if (!Schema::hasColumn('sim_reports', 'recommendations')) {
                $table->longText('recommendations')->nullable()->after('summary');
            }
            if (!Schema::hasColumn('sim_reports', 'attachments')) {
                $table->json('attachments')->nullable()->after('recommendations');
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
                'is_public',
                'view_count', 
                'download_count',
                'published_at',
                'generated_at',
                'generated_by',
                'data_sources',
                'indicators_data',
                'summary',
                'recommendations',
                'attachments'
            ]);
        });
    }
};

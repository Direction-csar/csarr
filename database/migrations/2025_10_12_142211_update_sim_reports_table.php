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
            $table->enum('report_type', ['financial', 'operational', 'inventory', 'personnel', 'general'])->default('general')->after('description');
            $table->date('period_start')->nullable()->after('report_type');
            $table->date('period_end')->nullable()->after('period_start');
            $table->enum('status', ['pending', 'generating', 'completed', 'published', 'failed', 'scheduled'])->default('pending')->after('period_end');
            $table->string('document_file')->nullable()->after('status');
            $table->string('cover_image')->nullable()->after('document_file');
            $table->unsignedBigInteger('created_by')->nullable()->after('is_public');
            $table->unsignedBigInteger('generated_by')->nullable()->after('created_by');
            $table->timestamp('generated_at')->nullable()->after('generated_by');
            $table->timestamp('scheduled_at')->nullable()->after('generated_at');
            $table->integer('download_count')->default(0)->after('scheduled_at');
            $table->integer('view_count')->default(0)->after('download_count');
            $table->bigInteger('file_size')->nullable()->after('view_count');
            $table->json('metadata')->nullable()->after('file_size');
            
            // Renommer file_url en document_file si nécessaire
            if (Schema::hasColumn('sim_reports', 'file_url')) {
                $table->renameColumn('file_url', 'document_file');
            }
            
            // Ajouter les index
            $table->index(['status', 'is_public']);
            $table->index(['report_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sim_reports', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropColumn([
                'report_type', 'period_start', 'period_end', 'status',
                'document_file', 'cover_image', 'created_by', 'generated_by',
                'generated_at', 'scheduled_at', 'download_count', 'view_count',
                'file_size', 'metadata'
            ]);
            
            // Supprimer les index
            $table->dropIndex(['status', 'is_public']);
            $table->dropIndex(['report_type', 'status']);
        });
    }
};

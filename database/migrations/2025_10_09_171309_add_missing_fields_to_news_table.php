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
        Schema::table('news', function (Blueprint $table) {
            // Ajouter les colonnes manquantes
            if (!Schema::hasColumn('news', 'featured_image')) {
                $table->string('featured_image')->nullable();
            }
            if (!Schema::hasColumn('news', 'cover_image')) {
                $table->string('cover_image')->nullable();
            }
            if (!Schema::hasColumn('news', 'youtube_url')) {
                $table->string('youtube_url')->nullable();
            }
            if (!Schema::hasColumn('news', 'document_file')) {
                $table->string('document_file')->nullable();
            }
            if (!Schema::hasColumn('news', 'excerpt')) {
                $table->text('excerpt')->nullable();
            }
            if (!Schema::hasColumn('news', 'slug')) {
                $table->string('slug')->nullable();
            }
            if (!Schema::hasColumn('news', 'meta_title')) {
                $table->string('meta_title')->nullable();
            }
            if (!Schema::hasColumn('news', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }
            if (!Schema::hasColumn('news', 'tags')) {
                $table->json('tags')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn([
                'featured_image',
                'cover_image', 
                'youtube_url',
                'document_file',
                'excerpt',
                'slug',
                'meta_title',
                'meta_description',
                'tags'
            ]);
        });
    }
};
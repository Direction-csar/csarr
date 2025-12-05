<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('sim_reports')) {
            Schema::table('sim_reports', function (Blueprint $table) {
                if (! $this->hasIndex('sim_reports', 'sim_reports_status_is_public_index')) {
                    $table->index(['status', 'is_public'], 'sim_reports_status_is_public_index');
                }
                if (! $this->hasIndex('sim_reports', 'sim_reports_published_at_index')) {
                    $table->index('published_at', 'sim_reports_published_at_index');
                }
                if (! $this->hasIndex('sim_reports', 'sim_reports_report_type_index')) {
                    $table->index('report_type', 'sim_reports_report_type_index');
                }
            });
        }

        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                if (! $this->hasIndex('news', 'news_is_published_index')) {
                    $table->index('is_published', 'news_is_published_index');
                }
                if (! $this->hasIndex('news', 'news_published_at_index')) {
                    $table->index('published_at', 'news_published_at_index');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sim_reports')) {
            Schema::table('sim_reports', function (Blueprint $table) {
                $table->dropIndex('sim_reports_status_is_public_index');
                $table->dropIndex('sim_reports_published_at_index');
                $table->dropIndex('sim_reports_report_type_index');
            });
        }

        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropIndex('news_is_published_index');
                $table->dropIndex('news_published_at_index');
            });
        }
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        try {
            $connection = Schema::getConnection();
            $schemaManager = $connection->getDoctrineSchemaManager();
            $indexes = $schemaManager->listTableIndexes($table);
            return array_key_exists($indexName, $indexes);
        } catch (\Throwable $e) {
            return false;
        }
    }
};















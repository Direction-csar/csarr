<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('demandes')) {
            Schema::table('demandes', function (Blueprint $table) {
                // Vérifier si les colonnes existent avant de créer les index
                if (Schema::hasColumn('demandes', 'tracking_code') && ! $this->hasIndex('demandes', 'demandes_tracking_code_index')) {
                    $table->index('tracking_code', 'demandes_tracking_code_index');
                }
                
                // Ces colonnes n'existent pas dans la table demandes actuelle, on les ignore
                // if (! $this->hasIndex('demandes', 'demandes_region_commune_departement_index')) {
                //     $table->index(['region','commune','departement'], 'demandes_region_commune_departement_index');
                // }
                
                if (! $this->hasIndex('demandes', 'demandes_created_at_index')) {
                    $table->index('created_at', 'demandes_created_at_index');
                }
            });
        }

        if (Schema::hasTable('warehouses')) {
            Schema::table('warehouses', function (Blueprint $table) {
                if (! $this->hasIndex('warehouses', 'warehouses_region_city_index')) {
                    $table->index(['region','city'], 'warehouses_region_city_index');
                }
                if (! $this->hasIndex('warehouses', 'warehouses_is_active_index')) {
                    $table->index('is_active', 'warehouses_is_active_index');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('demandes')) {
            Schema::table('demandes', function (Blueprint $table) {
                if ($this->hasIndex('demandes', 'demandes_tracking_code_index')) {
                    $table->dropIndex('demandes_tracking_code_index');
                }
                // $table->dropIndex('demandes_region_commune_departement_index'); // Index non créé
                if ($this->hasIndex('demandes', 'demandes_created_at_index')) {
                    $table->dropIndex('demandes_created_at_index');
                }
            });
        }

        if (Schema::hasTable('warehouses')) {
            Schema::table('warehouses', function (Blueprint $table) {
                $table->dropIndex('warehouses_region_city_index');
                $table->dropIndex('warehouses_is_active_index');
            });
        }
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        try {
            $connection = Schema::getConnection();
            $result = $connection->select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
            return count($result) > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }
};















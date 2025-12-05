<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\Message;
use App\Models\StockMovement;
use App\Models\Demande;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class CleanAllData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:all-data {--force : Force le nettoyage sans confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprime TOUTES les données de la base de données pour permettre les tests manuels';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  ATTENTION: Cette commande va supprimer TOUTES les données de la base de données. Êtes-vous sûr ?')) {
                $this->info('❌ Nettoyage annulé.');
                return 0;
            }
        }

        $this->info('🧹 Nettoyage complet de la base de données...');
        $this->newLine();

        try {
            // Désactiver les contraintes de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Supprimer toutes les données dans l'ordre correct
            $tables = [
                'notifications' => 'App\Models\Notification',
                'messages' => 'App\Models\Message',
                'stock_movements' => 'App\Models\StockMovement',
                'demandes' => 'App\Models\Demande',
                'products' => 'App\Models\Product',
                'warehouses' => 'App\Models\Warehouse',
                'users' => 'App\Models\User'
            ];

            foreach ($tables as $table => $model) {
                if (class_exists($model)) {
                    $count = $model::count();
                    $model::truncate();
                    $this->info("✅ Table {$table}: {$count} enregistrements supprimés");
                } else {
                    $this->warn("⚠️  Modèle {$model} non trouvé, suppression directe de la table {$table}");
                    DB::table($table)->truncate();
                }
            }

            // Réactiver les contraintes de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->newLine();
            $this->info('🎉 Base de données complètement nettoyée !');
            $this->info('📊 La plateforme est maintenant prête pour vos tests manuels.');
            $this->info('💡 Tous les compteurs et graphiques afficheront 0.');

        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du nettoyage : ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
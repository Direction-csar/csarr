<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\Message;
use App\Models\StockMovement;
use App\Models\Demande;

class CleanTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:test-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprime toutes les données de test de la base de données';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Nettoyage de toutes les données de test...');
        $this->newLine();

        try {
            // Supprimer toutes les notifications
            if (class_exists('App\Models\Notification')) {
                $count = Notification::count();
                Notification::truncate();
                $this->info("✅ {$count} notifications supprimées");
            }

            // Supprimer tous les messages
            if (class_exists('App\Models\Message')) {
                $count = Message::count();
                Message::truncate();
                $this->info("✅ {$count} messages supprimés");
            }

            // Supprimer tous les mouvements de stock
            if (class_exists('App\Models\StockMovement')) {
                $count = StockMovement::count();
                StockMovement::truncate();
                $this->info("✅ {$count} mouvements de stock supprimés");
            }

            // Supprimer toutes les demandes
            if (class_exists('App\Models\Demande')) {
                $count = Demande::count();
                Demande::truncate();
                $this->info("✅ {$count} demandes supprimées");
            }

            $this->newLine();
            $this->info('🎉 Base de données nettoyée avec succès !');
            $this->info('📊 La plateforme est maintenant prête pour vos tests manuels.');

        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du nettoyage : ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}



<?php

namespace App\Console\Commands;

use App\Models\PriceAlert;
use App\Models\PriceData;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanupPriceData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prices:cleanup {--price-data-days=365 : Jours de rétention pour les données de prix} {--alerts-days=90 : Jours de rétention pour les alertes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoyer les anciennes données de prix et alertes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $priceDataDays = $this->option('price-data-days');
        $alertsDays = $this->option('alerts-days');
        
        $this->info("Nettoyage des données de prix et alertes...");
        $this->info("Rétention des données de prix: {$priceDataDays} jours");
        $this->info("Rétention des alertes: {$alertsDays} jours");

        // Nettoyer les données de prix anciennes
        $priceDataCutoff = Carbon::now()->subDays($priceDataDays);
        $deletedPriceData = PriceData::where('recorded_at', '<', $priceDataCutoff)->delete();
        
        $this->info("{$deletedPriceData} enregistrements de données de prix supprimés.");

        // Nettoyer les alertes anciennes résolues
        $alertsCutoff = Carbon::now()->subDays($alertsDays);
        $deletedAlerts = PriceAlert::where('is_resolved', true)
            ->where('resolved_at', '<', $alertsCutoff)
            ->delete();
        
        $this->info("{$deletedAlerts} alertes résolues supprimées.");

        // Afficher les statistiques actuelles
        $priceDataStats = [
            'total' => PriceData::count(),
            'recent' => PriceData::where('recorded_at', '>=', Carbon::now()->subDays(30))->count(),
        ];

        $alertStats = [
            'total' => PriceAlert::count(),
            'unresolved' => PriceAlert::where('is_resolved', false)->count(),
            'resolved' => PriceAlert::where('is_resolved', true)->count(),
        ];

        $this->info("Statistiques actuelles:");
        $this->table(
            ['Type', 'Total', 'Récent (30j)', 'Non résolu'],
            [
                ['Données de prix', $priceDataStats['total'], $priceDataStats['recent'], '-'],
                ['Alertes', $alertStats['total'], '-', $alertStats['unresolved']],
            ]
        );

        return 0;
    }
}

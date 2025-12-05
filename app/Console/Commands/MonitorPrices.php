<?php

namespace App\Console\Commands;

use App\Services\PriceMonitoringService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MonitorPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prices:monitor {--source= : Source spécifique à surveiller}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Surveiller les prix et générer des alertes automatiques';

    protected $priceMonitoringService;

    public function __construct(PriceMonitoringService $priceMonitoringService)
    {
        parent::__construct();
        $this->priceMonitoringService = $priceMonitoringService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = $this->option('source');
        
        $this->info('Début de la surveillance des prix...');
        
        if ($source) {
            $this->info("Surveillance de la source spécifique: {$source}");
        } else {
            $this->info('Surveillance de toutes les sources de données');
        }

        try {
            $alertsCount = $this->priceMonitoringService->monitorAllPrices();
            
            $this->info("Surveillance terminée avec succès.");
            $this->info("Nombre d'alertes générées: {$alertsCount}");

            // Afficher les statistiques
            $stats = $this->priceMonitoringService->getMonitoringStats();
            
            $this->table(
                ['Statistique', 'Valeur'],
                [
                    ['Total alertes', $stats['total_alerts']],
                    ['Alertes non résolues', $stats['unresolved_alerts']],
                    ['Alertes critiques', $stats['critical_alerts']],
                    ['Alertes élevées', $stats['high_alerts']],
                    ['Alertes récentes (7j)', $stats['recent_alerts']],
                    ['Hausses de prix', $stats['price_increases']],
                    ['Baisses de prix', $stats['price_decreases']],
                ]
            );

            Log::info('Surveillance des prix terminée avec succès', [
                'alerts_generated' => $alertsCount,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            $this->error('Erreur lors de la surveillance des prix: ' . $e->getMessage());
            
            Log::error('Erreur lors de la surveillance des prix', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return 1;
        }

        return 0;
    }
}

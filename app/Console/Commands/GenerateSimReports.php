<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SimReportService;
use App\Models\SimReport;
use Carbon\Carbon;

class GenerateSimReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sim:generate {--type=monthly : Type de rapport (daily, weekly, monthly, quarterly, annual)} {--region= : Région spécifique} {--sector=all : Secteur de marché}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Générer automatiquement des rapports SIM';

    protected $simReportService;

    public function __construct(SimReportService $simReportService)
    {
        parent::__construct();
        $this->simReportService = $simReportService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $region = $this->option('region');
        $sector = $this->option('sector');

        // Valider le type de rapport
        $validTypes = ['daily', 'weekly', 'monthly', 'quarterly', 'annual'];
        if (!in_array($type, $validTypes)) {
            $this->error("Type de rapport invalide. Types valides: " . implode(', ', $validTypes));
            return 1;
        }

        // Déterminer la période selon le type
        $period = $this->getPeriodForType($type);
        
        $this->info("Génération d'un rapport {$type} pour la période {$period['start']->format('d/m/Y')} - {$period['end']->format('d/m/Y')}");

        try {
            // Créer le rapport
            $reportData = [
                'title' => $this->generateTitle($type, $period),
                'description' => $this->generateDescription($type, $period),
                'report_type' => $type,
                'period_start' => $period['start'],
                'period_end' => $period['end'],
                'region' => $region,
                'market_sector' => $sector,
                'data_sources' => $this->getDataSources($type)
            ];

            $report = $this->simReportService->generateReport($reportData);

            $this->info("✅ Rapport généré avec succès !");
            $this->info("📊 ID: {$report->id}");
            $this->info("📝 Titre: {$report->title}");
            $this->info("📅 Période: {$report->formatted_period}");
            $this->info("🌍 Région: " . ($report->region ?: 'Toutes'));
            $this->info("🏢 Secteur: {$report->sector_label}");

            // Afficher les statistiques
            $stats = $this->simReportService->getReportStats();
            $this->info("\n📈 Statistiques actuelles:");
            $this->info("   • Total rapports: {$stats['total_reports']}");
            $this->info("   • Rapports publiés: {$stats['published_reports']}");
            $this->info("   • Rapports en brouillon: {$stats['draft_reports']}");

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de la génération du rapport: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Obtenir la période selon le type de rapport
     */
    protected function getPeriodForType($type)
    {
        $now = Carbon::now();

        switch ($type) {
            case 'daily':
                return [
                    'start' => $now->copy()->subDay(),
                    'end' => $now->copy()->subDay()
                ];
            case 'weekly':
                return [
                    'start' => $now->copy()->subWeek()->startOfWeek(),
                    'end' => $now->copy()->subWeek()->endOfWeek()
                ];
            case 'monthly':
                return [
                    'start' => $now->copy()->subMonth()->startOfMonth(),
                    'end' => $now->copy()->subMonth()->endOfMonth()
                ];
            case 'quarterly':
                $quarter = $now->copy()->subQuarter();
                return [
                    'start' => $quarter->copy()->startOfQuarter(),
                    'end' => $quarter->copy()->endOfQuarter()
                ];
            case 'annual':
                return [
                    'start' => $now->copy()->subYear()->startOfYear(),
                    'end' => $now->copy()->subYear()->endOfYear()
                ];
            default:
                return [
                    'start' => $now->copy()->subMonth(),
                    'end' => $now->copy()
                ];
        }
    }

    /**
     * Générer le titre du rapport
     */
    protected function generateTitle($type, $period)
    {
        $typeLabels = [
            'daily' => 'Quotidien',
            'weekly' => 'Hebdomadaire',
            'monthly' => 'Mensuel',
            'quarterly' => 'Trimestriel',
            'annual' => 'Annuel'
        ];

        $typeLabel = $typeLabels[$type] ?? ucfirst($type);
        $periodStr = $period['start']->format('d/m/Y');
        
        if (!$period['start']->isSameDay($period['end'])) {
            $periodStr .= ' - ' . $period['end']->format('d/m/Y');
        }

        return "Rapport {$typeLabel} - Surveillance des Indicateurs de Marché ({$periodStr})";
    }

    /**
     * Générer la description du rapport
     */
    protected function generateDescription($type, $period)
    {
        $descriptions = [
            'daily' => 'Surveillance quotidienne des prix et indicateurs de marché pour un suivi en temps réel.',
            'weekly' => 'Analyse hebdomadaire des tendances de prix et de l\'état des stocks dans les entrepôts.',
            'monthly' => 'Rapport mensuel complet sur l\'évolution des indicateurs de sécurité alimentaire et de résilience.',
            'quarterly' => 'Évaluation trimestrielle de la performance du système de surveillance et des tendances de marché.',
            'annual' => 'Rapport annuel de synthèse sur l\'état de la sécurité alimentaire et les recommandations stratégiques.'
        ];

        return $descriptions[$type] ?? 'Rapport automatique généré par le système de surveillance.';
    }

    /**
     * Obtenir les sources de données selon le type
     */
    protected function getDataSources($type)
    {
        $baseSources = [
            'Système de surveillance CSAR',
            'Données des marchés',
            'Entrepôts régionaux'
        ];

        if ($type === 'daily') {
            $baseSources[] = 'Collecteurs de données terrain';
        } elseif ($type === 'monthly') {
            $baseSources[] = 'Direction du Commerce';
            $baseSources[] = 'Enquêtes terrain';
        } elseif (in_array($type, ['quarterly', 'annual'])) {
            $baseSources[] = 'Données statistiques nationales';
            $baseSources[] = 'Rapports des partenaires';
        }

        return $baseSources;
    }
}
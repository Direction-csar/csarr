<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SimReportService;
use App\Models\SimReport;
use Carbon\Carbon;

class ScheduleSimReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sim:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Générer automatiquement les rapports SIM selon la planification';

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
        $this->info("🔄 Démarrage de la génération automatique des rapports SIM...");

        $generatedReports = [];

        // Générer le rapport quotidien (tous les jours à 18h)
        if ($this->shouldGenerateDaily()) {
            $this->info("📅 Génération du rapport quotidien...");
            $dailyReport = $this->generateReport('daily');
            if ($dailyReport) {
                $generatedReports[] = $dailyReport;
            }
        }

        // Générer le rapport hebdomadaire (tous les lundis)
        if ($this->shouldGenerateWeekly()) {
            $this->info("📊 Génération du rapport hebdomadaire...");
            $weeklyReport = $this->generateReport('weekly');
            if ($weeklyReport) {
                $generatedReports[] = $weeklyReport;
            }
        }

        // Générer le rapport mensuel (le 1er de chaque mois)
        if ($this->shouldGenerateMonthly()) {
            $this->info("📈 Génération du rapport mensuel...");
            $monthlyReport = $this->generateReport('monthly');
            if ($monthlyReport) {
                $generatedReports[] = $monthlyReport;
            }
        }

        // Générer le rapport trimestriel (le 1er de chaque trimestre)
        if ($this->shouldGenerateQuarterly()) {
            $this->info("📋 Génération du rapport trimestriel...");
            $quarterlyReport = $this->generateReport('quarterly');
            if ($quarterlyReport) {
                $generatedReports[] = $quarterlyReport;
            }
        }

        // Résumé
        if (empty($generatedReports)) {
            $this->info("ℹ️  Aucun rapport à générer pour le moment.");
        } else {
            $this->info("✅ Génération terminée ! Rapports créés:");
            foreach ($generatedReports as $report) {
                $this->info("   • {$report->title} (ID: {$report->id})");
            }
        }

        return 0;
    }

    /**
     * Vérifier si un rapport quotidien doit être généré
     */
    protected function shouldGenerateDaily()
    {
        // Vérifier s'il n'y a pas déjà un rapport quotidien pour aujourd'hui
        $today = Carbon::today();
        $existingReport = SimReport::where('report_type', 'daily')
            ->whereDate('period_start', $today)
            ->whereDate('period_end', $today)
            ->first();

        return !$existingReport;
    }

    /**
     * Vérifier si un rapport hebdomadaire doit être généré
     */
    protected function shouldGenerateWeekly()
    {
        // Générer le lundi pour la semaine précédente
        if (Carbon::now()->isMonday()) {
            $lastWeek = Carbon::now()->subWeek();
            $existingReport = SimReport::where('report_type', 'weekly')
                ->whereDate('period_start', $lastWeek->startOfWeek())
                ->whereDate('period_end', $lastWeek->endOfWeek())
                ->first();

            return !$existingReport;
        }

        return false;
    }

    /**
     * Vérifier si un rapport mensuel doit être généré
     */
    protected function shouldGenerateMonthly()
    {
        // Générer le 1er de chaque mois pour le mois précédent
        if (Carbon::now()->day === 1) {
            $lastMonth = Carbon::now()->subMonth();
            $existingReport = SimReport::where('report_type', 'monthly')
                ->whereDate('period_start', $lastMonth->startOfMonth())
                ->whereDate('period_end', $lastMonth->endOfMonth())
                ->first();

            return !$existingReport;
        }

        return false;
    }

    /**
     * Vérifier si un rapport trimestriel doit être généré
     */
    protected function shouldGenerateQuarterly()
    {
        // Générer le 1er jour du trimestre pour le trimestre précédent
        $now = Carbon::now();
        if (in_array($now->day, [1]) && in_array($now->month, [1, 4, 7, 10])) {
            $lastQuarter = $now->copy()->subQuarter();
            $existingReport = SimReport::where('report_type', 'quarterly')
                ->whereDate('period_start', $lastQuarter->startOfQuarter())
                ->whereDate('period_end', $lastQuarter->endOfQuarter())
                ->first();

            return !$existingReport;
        }

        return false;
    }

    /**
     * Générer un rapport
     */
    protected function generateReport($type)
    {
        try {
            $period = $this->getPeriodForType($type);
            
            $reportData = [
                'title' => $this->generateTitle($type, $period),
                'description' => $this->generateDescription($type, $period),
                'report_type' => $type,
                'period_start' => $period['start'],
                'period_end' => $period['end'],
                'region' => null, // Toutes les régions
                'market_sector' => 'all',
                'data_sources' => $this->getDataSources($type)
            ];

            return $this->simReportService->generateReport($reportData);

        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de la génération du rapport {$type}: " . $e->getMessage());
            return null;
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
        } elseif ($type === 'quarterly') {
            $baseSources[] = 'Données statistiques nationales';
            $baseSources[] = 'Rapports des partenaires';
        }

        return $baseSources;
    }
}









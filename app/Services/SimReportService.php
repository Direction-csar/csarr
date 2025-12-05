<?php

namespace App\Services;

use App\Models\SimReport;
use App\Models\PriceAlert;
use App\Models\PriceData;
use App\Models\StockAlert;
use App\Models\PublicRequest;
use App\Models\Newsletter;
use App\Models\Task;
use App\Models\WeeklyAgenda;
use App\Models\SmsNotification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class SimReportService
{
    /**
     * Générer un rapport SIM
     */
    public function generateReport(array $data)
    {
        try {
            $report = SimReport::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'report_type' => $data['report_type'],
                'period_start' => $data['period_start'],
                'period_end' => $data['period_end'],
                'region' => $data['region'] ?? null,
                'market_sector' => $data['market_sector'] ?? 'all',
                'created_by' => auth()->id(),
                'generated_by' => auth()->id(),
                'status' => SimReport::STATUS_GENERATING,
                'data_sources' => $data['data_sources'] ?? [],
                'document_file' => $data['document_file'] ?? null
            ]);

            $report->markAsGenerating();

            // Générer les données du rapport
            $indicatorsData = $this->collectIndicatorsData($report);
            $summary = $this->generateSummary($indicatorsData, $report);
            $recommendations = $this->generateRecommendations($indicatorsData, $report);

            $report->update([
                'indicators_data' => $indicatorsData,
                'summary' => $summary,
                'recommendations' => $recommendations,
                'status' => SimReport::STATUS_COMPLETED,
                'generated_at' => now()
            ]);

            Log::info("Rapport SIM généré avec succès", [
                'report_id' => $report->id,
                'type' => $report->report_type,
                'period' => $report->formatted_period
            ]);

            return $report;

        } catch (Exception $e) {
            Log::error("Erreur lors de la génération du rapport SIM", [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Collecter les données des indicateurs
     */
    protected function collectIndicatorsData(SimReport $report)
    {
        $startDate = $report->period_start;
        $endDate = $report->period_end;

        return [
            'price_indicators' => $this->getPriceIndicators($startDate, $endDate, $report->region, $report->market_sector),
            'stock_indicators' => $this->getStockIndicators($startDate, $endDate),
            'request_indicators' => $this->getRequestIndicators($startDate, $endDate, $report->region),
            'communication_indicators' => $this->getCommunicationIndicators($startDate, $endDate),
            'operational_indicators' => $this->getOperationalIndicators($startDate, $endDate),
            'market_trends' => $this->getMarketTrends($startDate, $endDate, $report->region, $report->market_sector),
            'performance_metrics' => $this->getPerformanceMetrics($startDate, $endDate),
        ];
    }

    /**
     * Indicateurs de prix
     */
    protected function getPriceIndicators($startDate, $endDate, $region = null, $sector = null)
    {
        try {
            if (!\Schema::hasTable('price_alerts')) {
                return $this->getEmptyPriceIndicators();
            }

            $query = PriceAlert::whereBetween('created_at', [$startDate, $endDate]);

            if ($region) {
                $query->where('region', $region);
            }

            $alerts = $query->get();

            return [
                'total_alerts' => $alerts->count(),
                'price_increases' => $alerts->where('price_change_type', 'increase')->count(),
                'price_decreases' => $alerts->where('price_change_type', 'decrease')->count(),
                'critical_alerts' => $alerts->where('severity', PriceAlert::SEVERITY_CRITICAL)->count(),
                'high_alerts' => $alerts->where('severity', PriceAlert::SEVERITY_HIGH)->count(),
                'average_price_change' => $alerts->avg('price_change_percentage'),
                'max_price_increase' => $alerts->where('price_change_type', 'increase')->max('price_change_percentage'),
                'max_price_decrease' => $alerts->where('price_change_type', 'decrease')->min('price_change_percentage'),
                'most_affected_products' => $alerts->groupBy('product_name')
                    ->map->count()
                    ->sortDesc()
                    ->take(10)
                    ->toArray(),
                'regional_distribution' => $alerts->groupBy('region')
                    ->map->count()
                    ->toArray(),
            ];
        } catch (\Exception $e) {
            Log::warning('Erreur lors de la collecte des indicateurs de prix', ['error' => $e->getMessage()]);
            return $this->getEmptyPriceIndicators();
        }
    }

    protected function getEmptyPriceIndicators()
    {
        return [
            'total_alerts' => 0,
            'price_increases' => 0,
            'price_decreases' => 0,
            'critical_alerts' => 0,
            'high_alerts' => 0,
            'average_price_change' => 0,
            'max_price_increase' => 0,
            'max_price_decrease' => 0,
            'most_affected_products' => [],
            'regional_distribution' => [],
        ];
    }

    /**
     * Indicateurs de stock
     */
    protected function getStockIndicators($startDate, $endDate)
    {
        try {
            if (!\Schema::hasTable('stock_alerts')) {
                return [
                    'total_stock_alerts' => 0,
                    'resolved_alerts' => 0,
                    'unresolved_alerts' => 0,
                    'low_stock_alerts' => 0,
                    'expired_alerts' => 0,
                    'critical_stock_alerts' => 0,
                    'warehouse_distribution' => [],
                ];
            }

            $alerts = StockAlert::whereBetween('created_at', [$startDate, $endDate])->get();

            return [
                'total_stock_alerts' => $alerts->count(),
                'resolved_alerts' => $alerts->where('is_resolved', true)->count(),
                'unresolved_alerts' => $alerts->where('is_resolved', false)->count(),
                'low_stock_alerts' => $alerts->where('alert_type', 'low_stock')->count(),
                'expired_alerts' => $alerts->where('alert_type', 'expired')->count(),
                'critical_stock_alerts' => $alerts->where('severity', 'critical')->count(),
                'warehouse_distribution' => $alerts->groupBy('warehouse_id')
                    ->map->count()
                    ->toArray(),
            ];
        } catch (\Exception $e) {
            Log::warning('Erreur lors de la collecte des indicateurs de stock', ['error' => $e->getMessage()]);
            return [
                'total_stock_alerts' => 0,
                'resolved_alerts' => 0,
                'unresolved_alerts' => 0,
                'low_stock_alerts' => 0,
                'expired_alerts' => 0,
                'critical_stock_alerts' => 0,
                'warehouse_distribution' => [],
            ];
        }
    }

    /**
     * Indicateurs de demandes
     */
    protected function getRequestIndicators($startDate, $endDate, $region = null)
    {
        try {
            if (!\Schema::hasTable('public_requests')) {
                return [
                    'total_requests' => 0,
                    'pending_requests' => 0,
                    'approved_requests' => 0,
                    'rejected_requests' => 0,
                    'requests_by_type' => [],
                    'requests_by_region' => [],
                    'average_processing_time' => 0,
                ];
            }

            $query = PublicRequest::whereBetween('created_at', [$startDate, $endDate]);

            if ($region) {
                $query->where('region', $region);
            }

            $requests = $query->get();

            return [
                'total_requests' => $requests->count(),
                'pending_requests' => $requests->where('status', 'pending')->count(),
                'approved_requests' => $requests->where('status', 'approved')->count(),
                'rejected_requests' => $requests->where('status', 'rejected')->count(),
                'requests_by_type' => $requests->groupBy('type')
                    ->map->count()
                    ->toArray(),
                'requests_by_region' => $requests->groupBy('region')
                    ->map->count()
                    ->toArray(),
                'average_processing_time' => $this->calculateAverageProcessingTime($requests),
            ];
        } catch (\Exception $e) {
            Log::warning('Erreur lors de la collecte des indicateurs de demandes', ['error' => $e->getMessage()]);
            return [
                'total_requests' => 0,
                'pending_requests' => 0,
                'approved_requests' => 0,
                'rejected_requests' => 0,
                'requests_by_type' => [],
                'requests_by_region' => [],
                'average_processing_time' => 0,
            ];
        }
    }

    /**
     * Indicateurs de communication
     */
    protected function getCommunicationIndicators($startDate, $endDate)
    {
        try {
            $smsNotifications = collect([]);
            $newsletterSubscribers = collect([]);

            if (\Schema::hasTable('sms_notifications')) {
                $smsNotifications = SmsNotification::whereBetween('created_at', [$startDate, $endDate])->get();
            }

            if (\Schema::hasTable('newsletters')) {
                $newsletterSubscribers = Newsletter::whereBetween('created_at', [$startDate, $endDate])->get();
            }

            return [
                'total_sms_sent' => $smsNotifications->count(),
                'delivered_sms' => $smsNotifications->where('status', SmsNotification::STATUS_DELIVERED)->count(),
                'failed_sms' => $smsNotifications->where('status', SmsNotification::STATUS_FAILED)->count(),
                'sms_by_type' => $smsNotifications->groupBy('type')
                    ->map->count()
                    ->toArray(),
                'new_newsletter_subscribers' => $newsletterSubscribers->count(),
                'sms_delivery_rate' => $this->calculateDeliveryRate($smsNotifications),
            ];
        } catch (\Exception $e) {
            Log::warning('Erreur lors de la collecte des indicateurs de communication', ['error' => $e->getMessage()]);
            return [
                'total_sms_sent' => 0,
                'delivered_sms' => 0,
                'failed_sms' => 0,
                'sms_by_type' => [],
                'new_newsletter_subscribers' => 0,
                'sms_delivery_rate' => 0,
            ];
        }
    }

    /**
     * Indicateurs opérationnels
     */
    protected function getOperationalIndicators($startDate, $endDate)
    {
        try {
            $tasks = collect([]);
            $agendaEvents = collect([]);

            if (\Schema::hasTable('tasks')) {
                $tasks = Task::whereBetween('created_at', [$startDate, $endDate])->get();
            }

            if (\Schema::hasTable('weekly_agendas')) {
                $agendaEvents = WeeklyAgenda::whereBetween('created_at', [$startDate, $endDate])->get();
            }

            return [
                'total_tasks' => $tasks->count(),
                'completed_tasks' => $tasks->where('status', 'completed')->count(),
                'pending_tasks' => $tasks->where('status', 'todo')->count(),
                'in_progress_tasks' => $tasks->where('status', 'in_progress')->count(),
                'tasks_by_priority' => $tasks->groupBy('priority')
                    ->map->count()
                    ->toArray(),
                'total_agenda_events' => $agendaEvents->count(),
                'completed_events' => $agendaEvents->where('status', 'completed')->count(),
                'task_completion_rate' => $this->calculateTaskCompletionRate($tasks),
            ];
        } catch (\Exception $e) {
            Log::warning('Erreur lors de la collecte des indicateurs opérationnels', ['error' => $e->getMessage()]);
            return [
                'total_tasks' => 0,
                'completed_tasks' => 0,
                'pending_tasks' => 0,
                'in_progress_tasks' => 0,
                'tasks_by_priority' => [],
                'total_agenda_events' => 0,
                'completed_events' => 0,
                'task_completion_rate' => 0,
            ];
        }
    }

    /**
     * Tendances de marché
     */
    protected function getMarketTrends($startDate, $endDate, $region = null, $sector = null)
    {
        try {
            if (!\Schema::hasTable('price_data')) {
                return [
                    'price_trends' => [],
                    'market_volatility' => 0,
                    'seasonal_patterns' => [],
                    'emerging_products' => [],
                ];
            }

            $priceData = PriceData::whereBetween('recorded_at', [$startDate, $endDate]);

            if ($region) {
                $priceData->where('region', $region);
            }

            $data = $priceData->get();

            return [
                'price_trends' => $this->analyzePriceTrends($data),
                'market_volatility' => $this->calculateMarketVolatility($data),
                'seasonal_patterns' => $this->identifySeasonalPatterns($data),
                'emerging_products' => $this->identifyEmergingProducts($data),
            ];
        } catch (\Exception $e) {
            Log::warning('Erreur lors de la collecte des tendances de marché', ['error' => $e->getMessage()]);
            return [
                'price_trends' => [],
                'market_volatility' => 0,
                'seasonal_patterns' => [],
                'emerging_products' => [],
            ];
        }
    }

    /**
     * Métriques de performance
     */
    protected function getPerformanceMetrics($startDate, $endDate)
    {
        return [
            'system_uptime' => $this->calculateSystemUptime($startDate, $endDate),
            'response_times' => $this->calculateResponseTimes($startDate, $endDate),
            'user_engagement' => $this->calculateUserEngagement($startDate, $endDate),
            'data_quality_score' => $this->calculateDataQualityScore($startDate, $endDate),
        ];
    }

    /**
     * Générer le résumé du rapport
     */
    protected function generateSummary(array $indicatorsData, SimReport $report)
    {
        $summary = "Rapport SIM - {$report->report_type_label} du {$report->formatted_period}\n\n";
        
        $summary .= "RÉSUMÉ EXÉCUTIF\n";
        $summary .= "================\n\n";
        
        // Indicateurs clés
        $summary .= "INDICATEURS CLÉS:\n";
        $summary .= "• Alertes de prix: {$indicatorsData['price_indicators']['total_alerts']}\n";
        $summary .= "• Demandes publiques: {$indicatorsData['request_indicators']['total_requests']}\n";
        $summary .= "• Alertes de stock: {$indicatorsData['stock_indicators']['total_stock_alerts']}\n";
        $summary .= "• SMS envoyés: {$indicatorsData['communication_indicators']['total_sms_sent']}\n";
        $summary .= "• Tâches complétées: {$indicatorsData['operational_indicators']['completed_tasks']}\n\n";
        
        // Tendances principales
        $summary .= "TENDANCES PRINCIPALES:\n";
        if ($indicatorsData['price_indicators']['price_increases'] > $indicatorsData['price_indicators']['price_decreases']) {
            $summary .= "• Hausse générale des prix observée\n";
        } elseif ($indicatorsData['price_indicators']['price_decreases'] > $indicatorsData['price_indicators']['price_increases']) {
            $summary .= "• Baisse générale des prix observée\n";
        } else {
            $summary .= "• Stabilité des prix observée\n";
        }
        
        $summary .= "• Taux de livraison SMS: " . number_format($indicatorsData['communication_indicators']['sms_delivery_rate'], 1) . "%\n";
        $summary .= "• Taux de completion des tâches: " . number_format($indicatorsData['operational_indicators']['task_completion_rate'], 1) . "%\n\n";
        
        return $summary;
    }

    /**
     * Générer les recommandations
     */
    protected function generateRecommendations(array $indicatorsData, SimReport $report)
    {
        $recommendations = "RECOMMANDATIONS\n";
        $recommendations .= "===============\n\n";
        
        // Recommandations basées sur les alertes de prix
        if ($indicatorsData['price_indicators']['critical_alerts'] > 0) {
            $recommendations .= "1. PRIORITÉ HAUTE: {$indicatorsData['price_indicators']['critical_alerts']} alertes critiques de prix nécessitent une attention immédiate\n";
        }
        
        if ($indicatorsData['price_indicators']['average_price_change'] > 10) {
            $recommendations .= "2. Surveiller de près les fluctuations de prix (moyenne: " . number_format($indicatorsData['price_indicators']['average_price_change'], 1) . "%)\n";
        }
        
        // Recommandations basées sur les stocks
        if ($indicatorsData['stock_indicators']['unresolved_alerts'] > 0) {
            $recommendations .= "3. Résoudre {$indicatorsData['stock_indicators']['unresolved_alerts']} alertes de stock non résolues\n";
        }
        
        // Recommandations basées sur les demandes
        if ($indicatorsData['request_indicators']['pending_requests'] > 0) {
            $recommendations .= "4. Traiter {$indicatorsData['request_indicators']['pending_requests']} demandes en attente\n";
        }
        
        // Recommandations basées sur la communication
        if ($indicatorsData['communication_indicators']['sms_delivery_rate'] < 90) {
            $recommendations .= "5. Améliorer le taux de livraison des SMS (actuellement: " . number_format($indicatorsData['communication_indicators']['sms_delivery_rate'], 1) . "%)\n";
        }
        
        // Recommandations basées sur les opérations
        if ($indicatorsData['operational_indicators']['task_completion_rate'] < 80) {
            $recommendations .= "6. Améliorer le taux de completion des tâches (actuellement: " . number_format($indicatorsData['operational_indicators']['task_completion_rate'], 1) . "%)\n";
        }
        
        return $recommendations;
    }

    /**
     * Calculer le temps de traitement moyen des demandes
     */
    protected function calculateAverageProcessingTime($requests)
    {
        $processedRequests = $requests->whereIn('status', ['approved', 'rejected']);
        
        if ($processedRequests->isEmpty()) {
            return 0;
        }
        
        $totalDays = $processedRequests->sum(function($request) {
            return $request->created_at->diffInDays($request->updated_at);
        });
        
        return $totalDays / $processedRequests->count();
    }

    /**
     * Calculer le taux de livraison des SMS
     */
    protected function calculateDeliveryRate($smsNotifications)
    {
        if ($smsNotifications->isEmpty()) {
            return 0;
        }
        
        $delivered = $smsNotifications->where('status', SmsNotification::STATUS_DELIVERED)->count();
        return ($delivered / $smsNotifications->count()) * 100;
    }

    /**
     * Calculer le taux de completion des tâches
     */
    protected function calculateTaskCompletionRate($tasks)
    {
        if ($tasks->isEmpty()) {
            return 0;
        }
        
        $completed = $tasks->where('status', 'completed')->count();
        return ($completed / $tasks->count()) * 100;
    }

    /**
     * Analyser les tendances de prix
     */
    protected function analyzePriceTrends($priceData)
    {
        // Logique d'analyse des tendances de prix
        return [
            'trend_direction' => 'stable',
            'volatility_level' => 'low',
            'key_insights' => []
        ];
    }

    /**
     * Calculer la volatilité du marché
     */
    protected function calculateMarketVolatility($priceData)
    {
        // Logique de calcul de la volatilité
        return 0.15; // Exemple: 15% de volatilité
    }

    /**
     * Identifier les patterns saisonniers
     */
    protected function identifySeasonalPatterns($priceData)
    {
        // Logique d'identification des patterns saisonniers
        return [];
    }

    /**
     * Identifier les produits émergents
     */
    protected function identifyEmergingProducts($priceData)
    {
        // Logique d'identification des produits émergents
        return [];
    }

    /**
     * Calculer le temps de fonctionnement du système
     */
    protected function calculateSystemUptime($startDate, $endDate)
    {
        // Logique de calcul du temps de fonctionnement
        return 99.9; // Exemple: 99.9% de disponibilité
    }

    /**
     * Calculer les temps de réponse
     */
    protected function calculateResponseTimes($startDate, $endDate)
    {
        // Logique de calcul des temps de réponse
        return [
            'average' => 1.2, // secondes
            'p95' => 2.5,
            'p99' => 5.0
        ];
    }

    /**
     * Calculer l'engagement utilisateur
     */
    protected function calculateUserEngagement($startDate, $endDate)
    {
        // Logique de calcul de l'engagement utilisateur
        return 75.5; // Exemple: 75.5% d'engagement
    }

    /**
     * Calculer le score de qualité des données
     */
    protected function calculateDataQualityScore($startDate, $endDate)
    {
        // Logique de calcul de la qualité des données
        return 92.3; // Exemple: 92.3% de qualité
    }

    /**
     * Obtenir les statistiques des rapports
     */
    public function getReportStats()
    {
        try {
            if (!\Schema::hasTable('sim_reports')) {
                return [
                    'total_reports' => 0,
                    'published_reports' => 0,
                    'draft_reports' => 0,
                    'generating_reports' => 0,
                    'total_views' => 0,
                    'total_downloads' => 0,
                    'recent_reports' => 0,
                ];
            }

            return [
                'total_reports' => SimReport::count(),
                'published_reports' => SimReport::where('status', SimReport::STATUS_PUBLISHED)->count(),
                'draft_reports' => SimReport::where('status', SimReport::STATUS_DRAFT)->count(),
                'generating_reports' => SimReport::where('status', SimReport::STATUS_GENERATING)->count(),
                'total_views' => SimReport::sum('view_count') ?? 0,
                'total_downloads' => SimReport::sum('download_count') ?? 0,
                'recent_reports' => SimReport::recent(7)->count(),
            ];
        } catch (\Exception $e) {
            Log::warning('Erreur lors de la collecte des statistiques de rapports', ['error' => $e->getMessage()]);
            return [
                'total_reports' => 0,
                'published_reports' => 0,
                'draft_reports' => 0,
                'generating_reports' => 0,
                'total_views' => 0,
                'total_downloads' => 0,
                'recent_reports' => 0,
            ];
        }
    }
}

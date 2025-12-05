<?php

namespace App\Services;

use App\Models\PublicRequest;
use App\Models\Stock;
use App\Models\PriceAlert;
use App\Models\Personnel;
use App\Models\Warehouse;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getDashboardMetrics($period = '30d')
    {
        $startDate = $this->getStartDate($period);
        $endDate = Carbon::now();

        return [
            'requests' => $this->getRequestMetrics($startDate, $endDate),
            'stocks' => $this->getStockMetrics($startDate, $endDate),
            'alerts' => $this->getAlertMetrics($startDate, $endDate),
            'personnel' => $this->getPersonnelMetrics($startDate, $endDate),
            'performance' => $this->getPerformanceMetrics($startDate, $endDate),
            'trends' => $this->getTrendAnalysis($startDate, $endDate)
        ];
    }

    public function getRequestMetrics($startDate, $endDate)
    {
        $total = PublicRequest::whereBetween('created_at', [$startDate, $endDate])->count();
        $processed = PublicRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'processed')->count();
        $pending = PublicRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'pending')->count();
        $rejected = PublicRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'rejected')->count();

        $processingRate = $total > 0 ? round(($processed / $total) * 100, 2) : 0;
        $avgProcessingTime = $this->getAverageProcessingTime($startDate, $endDate);

        return [
            'total' => $total,
            'processed' => $processed,
            'pending' => $pending,
            'rejected' => $rejected,
            'processing_rate' => $processingRate,
            'avg_processing_time' => $avgProcessingTime,
            'daily_average' => round($total / $startDate->diffInDays($endDate), 2)
        ];
    }

    public function getStockMetrics($startDate, $endDate)
    {
        $totalStock = Stock::sum('quantity');
        $warehouses = Warehouse::count();
        $lowStockAlerts = PriceAlert::where('type', 'stock')
            ->where('status', 'active')
            ->count();

        $stockMovements = $this->getStockMovements($startDate, $endDate);
        $topProducts = $this->getTopProducts($startDate, $endDate);

        return [
            'total_quantity' => $totalStock,
            'warehouses_count' => $warehouses,
            'low_stock_alerts' => $lowStockAlerts,
            'movements' => $stockMovements,
            'top_products' => $topProducts,
            'avg_warehouse_stock' => $warehouses > 0 ? round($totalStock / $warehouses, 2) : 0
        ];
    }

    public function getAlertMetrics($startDate, $endDate)
    {
        $total = PriceAlert::whereBetween('created_at', [$startDate, $endDate])->count();
        $active = PriceAlert::where('status', 'active')->count();
        $resolved = PriceAlert::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'resolved')->count();

        $avgResolutionTime = $this->getAverageResolutionTime($startDate, $endDate);

        return [
            'total' => $total,
            'active' => $active,
            'resolved' => $resolved,
            'resolution_rate' => $total > 0 ? round(($resolved / $total) * 100, 2) : 0,
            'avg_resolution_time' => $avgResolutionTime
        ];
    }

    public function getPersonnelMetrics($startDate, $endDate)
    {
        $total = Personnel::count();
        $active = Personnel::where('is_active', true)->count();
        $attendanceRate = $this->getAttendanceRate($startDate, $endDate);

        return [
            'total' => $total,
            'active' => $active,
            'attendance_rate' => $attendanceRate,
            'inactive' => $total - $active
        ];
    }

    public function getPerformanceMetrics($startDate, $endDate)
    {
        $requests = PublicRequest::whereBetween('created_at', [$startDate, $endDate])->get();
        
        $responseTime = $this->getAverageResponseTime($startDate, $endDate);
        $satisfactionRate = $this->getSatisfactionRate($startDate, $endDate);
        $efficiency = $this->getEfficiencyScore($startDate, $endDate);

        return [
            'avg_response_time' => $responseTime,
            'satisfaction_rate' => $satisfactionRate,
            'efficiency_score' => $efficiency,
            'peak_hours' => $this->getPeakHours($startDate, $endDate)
        ];
    }

    public function getTrendAnalysis($startDate, $endDate)
    {
        $requests = PublicRequest::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $trend = $this->calculateTrend($requests->pluck('count')->toArray());
        $seasonality = $this->detectSeasonality($requests->pluck('count')->toArray());

        return [
            'trend_direction' => $trend['direction'],
            'trend_strength' => $trend['strength'],
            'seasonality' => $seasonality,
            'forecast' => $this->generateForecast($requests->pluck('count')->toArray())
        ];
    }

    public function getRegionalDistribution()
    {
        return PublicRequest::selectRaw('region, COUNT(*) as count')
            ->whereNotNull('region')
            ->groupBy('region')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'region' => $item->region,
                    'count' => $item->count,
                    'percentage' => 0 // Sera calculé côté frontend
                ];
            });
    }

    public function getTimeSeriesData($period = '30d', $metric = 'requests')
    {
        $startDate = $this->getStartDate($period);
        $endDate = Carbon::now();

        switch ($metric) {
            case 'requests':
                return $this->getRequestTimeSeries($startDate, $endDate);
            case 'stocks':
                return $this->getStockTimeSeries($startDate, $endDate);
            case 'alerts':
                return $this->getAlertTimeSeries($startDate, $endDate);
            default:
                return $this->getRequestTimeSeries($startDate, $endDate);
        }
    }

    private function getRequestTimeSeries($startDate, $endDate)
    {
        $data = PublicRequest::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total, 
                        SUM(CASE WHEN status = "processed" THEN 1 ELSE 0 END) as processed,
                        SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $data->map(function ($item) {
            return [
                'date' => $item->date,
                'total' => $item->total,
                'processed' => $item->processed,
                'pending' => $item->pending
            ];
        });
    }

    private function getStockTimeSeries($startDate, $endDate)
    {
        // Simulation - à adapter selon votre structure de données
        return collect();
    }

    private function getAlertTimeSeries($startDate, $endDate)
    {
        $data = PriceAlert::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total,
                        SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
                        SUM(CASE WHEN status = "resolved" THEN 1 ELSE 0 END) as resolved')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $data->map(function ($item) {
            return [
                'date' => $item->date,
                'total' => $item->total,
                'active' => $item->active,
                'resolved' => $item->resolved
            ];
        });
    }

    private function getAverageProcessingTime($startDate, $endDate)
    {
        $requests = PublicRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'processed')
            ->whereNotNull('processed_at')
            ->get();

        if ($requests->isEmpty()) {
            return 0;
        }

        $totalTime = $requests->sum(function ($request) {
            return $request->created_at->diffInHours($request->processed_at);
        });

        return round($totalTime / $requests->count(), 2);
    }

    private function getStockMovements($startDate, $endDate)
    {
        // Simulation - à adapter selon votre structure de données
        return [
            'inbound' => 150,
            'outbound' => 120,
            'net_change' => 30
        ];
    }

    private function getTopProducts($startDate, $endDate)
    {
        // Simulation - à adapter selon votre structure de données
        return [
            ['product' => 'Riz', 'quantity' => 500],
            ['product' => 'Mil', 'quantity' => 300],
            ['product' => 'Maïs', 'quantity' => 200]
        ];
    }

    private function getAverageResolutionTime($startDate, $endDate)
    {
        $alerts = PriceAlert::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'resolved')
            ->whereNotNull('resolved_at')
            ->get();

        if ($alerts->isEmpty()) {
            return 0;
        }

        $totalTime = $alerts->sum(function ($alert) {
            return $alert->created_at->diffInHours($alert->resolved_at);
        });

        return round($totalTime / $alerts->count(), 2);
    }

    private function getAttendanceRate($startDate, $endDate)
    {
        // Simulation - à adapter selon votre système de présence
        return 95.5;
    }

    private function getAverageResponseTime($startDate, $endDate)
    {
        // Simulation - à adapter selon votre système de suivi
        return 2.5; // heures
    }

    private function getSatisfactionRate($startDate, $endDate)
    {
        // Simulation - à adapter selon votre système de feedback
        return 87.3; // pourcentage
    }

    private function getEfficiencyScore($startDate, $endDate)
    {
        // Calcul basé sur plusieurs métriques
        $processingRate = $this->getRequestMetrics($startDate, $endDate)['processing_rate'];
        $responseTime = $this->getAverageResponseTime($startDate, $endDate);
        $satisfaction = $this->getSatisfactionRate($startDate, $endDate);

        // Score composite (0-100)
        $efficiency = ($processingRate * 0.4) + ((100 - $responseTime * 10) * 0.3) + ($satisfaction * 0.3);
        return round(max(0, min(100, $efficiency)), 2);
    }

    private function getPeakHours($startDate, $endDate)
    {
        $peakHours = PublicRequest::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get();

        return $peakHours->pluck('hour')->toArray();
    }

    private function calculateTrend($data)
    {
        if (count($data) < 2) {
            return ['direction' => 'stable', 'strength' => 0];
        }

        $n = count($data);
        $x = range(1, $n);
        $y = $data;

        // Calcul de la pente (méthode des moindres carrés)
        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = array_sum(array_map(function($a, $b) { return $a * $b; }, $x, $y));
        $sumXX = array_sum(array_map(function($a) { return $a * $a; }, $x));

        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumXX - $sumX * $sumX);

        if ($slope > 0.1) {
            return ['direction' => 'increasing', 'strength' => min(100, abs($slope) * 10)];
        } elseif ($slope < -0.1) {
            return ['direction' => 'decreasing', 'strength' => min(100, abs($slope) * 10)];
        } else {
            return ['direction' => 'stable', 'strength' => 0];
        }
    }

    private function detectSeasonality($data)
    {
        // Détection simple de saisonnalité
        if (count($data) < 7) {
            return false;
        }

        $weeklyPattern = [];
        for ($i = 0; $i < 7; $i++) {
            $weeklyPattern[$i] = 0;
            $count = 0;
            for ($j = $i; $j < count($data); $j += 7) {
                $weeklyPattern[$i] += $data[$j];
                $count++;
            }
            if ($count > 0) {
                $weeklyPattern[$i] /= $count;
            }
        }

        $variance = $this->calculateVariance($weeklyPattern);
        return $variance > 0.1; // Seuil de saisonnalité
    }

    private function calculateVariance($data)
    {
        $mean = array_sum($data) / count($data);
        $variance = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $data)) / count($data);
        
        return $variance;
    }

    private function generateForecast($data)
    {
        if (count($data) < 3) {
            return [];
        }

        // Prévision simple basée sur la moyenne mobile
        $forecast = [];
        $window = min(7, count($data));
        $recent = array_slice($data, -$window);
        $average = array_sum($recent) / count($recent);

        for ($i = 1; $i <= 7; $i++) {
            $forecast[] = round($average + ($i * 0.1), 2);
        }

        return $forecast;
    }

    private function getStartDate($period)
    {
        switch ($period) {
            case '1d':
                return Carbon::now()->subDay();
            case '7d':
                return Carbon::now()->subWeek();
            case '30d':
                return Carbon::now()->subMonth();
            case '90d':
                return Carbon::now()->subMonths(3);
            case '1y':
                return Carbon::now()->subYear();
            default:
                return Carbon::now()->subMonth();
        }
    }
}







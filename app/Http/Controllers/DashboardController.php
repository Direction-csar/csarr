<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Models\Stock;
use App\Models\SimReport;
use App\Models\SmsNotification;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard principal
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        // Statistiques générales
        $stats = $this->getGeneralStats();

        // Données spécifiques au rôle
        $roleData = $this->getRoleSpecificData($role);

        // Notifications récentes
        $recentNotifications = SmsNotification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Graphiques
        $charts = $this->getChartData($role);

        return view('dashboard.index', compact('stats', 'roleData', 'recentNotifications', 'charts', 'role'));
    }

    /**
     * Obtenir les statistiques générales
     */
    private function getGeneralStats()
    {
        return [
            'total_demandes' => PublicRequest::count(),
            'pending_demandes' => PublicRequest::where('status', 'pending')->count(),
            'total_stocks' => Stock::sum('quantity'),
            'low_stock_alerts' => Stock::where('quantity', '<', 100)->count(),
            'total_warehouses' => Warehouse::count(),
            'recent_reports' => SimReport::where('created_at', '>=', now()->subDays(7))->count()
        ];
    }

    /**
     * Obtenir les données spécifiques au rôle
     */
    private function getRoleSpecificData($role)
    {
        switch ($role) {
            case 'admin':
                return $this->getAdminData();
            case 'dg':
                return $this->getDGData();
            case 'agent':
                return $this->getAgentData();
            case 'responsable':
                return $this->getResponsableData();
            default:
                return [];
        }
    }

    /**
     * Données pour l'admin
     */
    private function getAdminData()
    {
        return [
            'users_count' => \App\Models\User::count(),
            'warehouses_count' => Warehouse::count(),
            'total_stock_value' => Stock::join('stock_types', 'stocks.stock_type_id', '=', 'stock_types.id')
                ->selectRaw('SUM(stocks.quantity * stock_types.unit_price) as total_value')
                ->value('total_value') ?? 0,
            'monthly_demandes' => PublicRequest::whereMonth('created_at', now()->month)->count(),
            'system_health' => $this->getSystemHealth()
        ];
    }

    /**
     * Données pour le DG
     */
    private function getDGData()
    {
        return [
            'regional_distribution' => $this->getRegionalDistribution(),
            'monthly_reports' => SimReport::whereMonth('created_at', now()->month)->count(),
            'budget_utilization' => $this->getBudgetUtilization(),
            'performance_metrics' => $this->getPerformanceMetrics()
        ];
    }

    /**
     * Données pour l'agent
     */
    private function getAgentData()
    {
        $user = Auth::user();
        return [
            'my_demandes' => PublicRequest::where('assigned_to', $user->id)->count(),
            'pending_tasks' => \App\Models\Task::where('assigned_to', $user->id)
                ->where('status', 'pending')->count(),
            'warehouse_stocks' => Stock::where('warehouse_id', $user->warehouse_id ?? 1)->sum('quantity'),
            'recent_activities' => $this->getRecentActivities($user->id)
        ];
    }

    /**
     * Données pour le responsable
     */
    private function getResponsableData()
    {
        $user = Auth::user();
        return [
            'warehouse_stocks' => Stock::where('warehouse_id', $user->warehouse_id ?? 1)->get(),
            'stock_movements' => \App\Models\StockMovement::where('warehouse_id', $user->warehouse_id ?? 1)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'low_stock_items' => Stock::where('warehouse_id', $user->warehouse_id ?? 1)
                ->where('quantity', '<', 50)
                ->get(),
            'monthly_movements' => \App\Models\StockMovement::where('warehouse_id', $user->warehouse_id ?? 1)
                ->whereMonth('created_at', now()->month)
                ->count()
        ];
    }

    /**
     * Obtenir les données des graphiques
     */
    private function getChartData($role)
    {
        return [
            'demandes_trend' => $this->getDemandesTrend(),
            'stock_levels' => $this->getStockLevels(),
            'regional_data' => $this->getRegionalData(),
            'monthly_activity' => $this->getMonthlyActivity()
        ];
    }

    /**
     * Tendance des demandes
     */
    private function getDemandesTrend()
    {
        return PublicRequest::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Niveaux de stock
     */
    private function getStockLevels()
    {
        return Stock::join('stock_types', 'stocks.stock_type_id', '=', 'stock_types.id')
            ->selectRaw('stock_types.name, SUM(stocks.quantity) as total_quantity')
            ->groupBy('stock_types.name')
            ->get();
    }

    /**
     * Distribution régionale
     */
    private function getRegionalDistribution()
    {
        return PublicRequest::selectRaw('region, COUNT(*) as count')
            ->whereNotNull('region')
            ->groupBy('region')
            ->get();
    }

    /**
     * Données régionales
     */
    private function getRegionalData()
    {
        return Warehouse::selectRaw('region, COUNT(*) as warehouses_count')
            ->groupBy('region')
            ->get();
    }

    /**
     * Activité mensuelle
     */
    private function getMonthlyActivity()
    {
        return DB::table('audit_logs')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Santé du système
     */
    private function getSystemHealth()
    {
        return [
            'database_status' => 'healthy',
            'storage_usage' => $this->getStorageUsage(),
            'last_backup' => $this->getLastBackupDate(),
            'error_rate' => $this->getErrorRate()
        ];
    }

    /**
     * Utilisation du stockage
     */
    private function getStorageUsage()
    {
        $totalSize = 0;
        $path = storage_path('app');
        
        if (is_dir($path)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path)
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $totalSize += $file->getSize();
                }
            }
        }
        
        return round($totalSize / 1024 / 1024, 2); // MB
    }

    /**
     * Date de la dernière sauvegarde
     */
    private function getLastBackupDate()
    {
        $backupPath = storage_path('app/backups');
        if (!is_dir($backupPath)) {
            return null;
        }
        
        $files = glob($backupPath . '/*.sql');
        if (empty($files)) {
            return null;
        }
        
        $latestFile = max($files);
        return date('Y-m-d H:i:s', filemtime($latestFile));
    }

    /**
     * Taux d'erreur
     */
    private function getErrorRate()
    {
        $totalLogs = DB::table('audit_logs')->count();
        $errorLogs = DB::table('audit_logs')
            ->where('action', 'like', '%error%')
            ->count();
            
        return $totalLogs > 0 ? round(($errorLogs / $totalLogs) * 100, 2) : 0;
    }

    /**
     * Utilisation du budget
     */
    private function getBudgetUtilization()
    {
        // Simulation des données budgétaires
        return [
            'total_budget' => 50000000000, // 50 milliards FCFA
            'used_budget' => 35000000000,  // 35 milliards FCFA
            'remaining_budget' => 15000000000, // 15 milliards FCFA
            'utilization_percentage' => 70
        ];
    }

    /**
     * Métriques de performance
     */
    private function getPerformanceMetrics()
    {
        return [
            'response_time' => '2.3s',
            'uptime' => '99.9%',
            'user_satisfaction' => '4.8/5',
            'processing_efficiency' => '95%'
        ];
    }

    /**
     * Activités récentes
     */
    private function getRecentActivities($userId)
    {
        return DB::table('audit_logs')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }
}

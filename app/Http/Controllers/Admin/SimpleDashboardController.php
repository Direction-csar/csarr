<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SimpleDashboardController extends Controller
{
    /**
     * Afficher le tableau de bord Admin simplifié
     */
    public function index()
    {
        try {
            // Statistiques de base
            $stats = $this->getBasicStats();
            
            // Activités récentes
            $recentActivities = $this->getBasicActivities();
            
            // Graphiques simples
            $chartsData = $this->getBasicCharts();
            
            // Alertes basiques
            $alerts = $this->getBasicAlerts();
            
            return view('admin.dashboard.index', compact('stats', 'recentActivities', 'chartsData', 'alerts'));
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du dashboard simplifié', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'timestamp' => Carbon::now()
            ]);
            
            // Retourner des données par défaut en cas d'erreur
            return view('admin.dashboard.index', [
                'stats' => $this->getDefaultStats(),
                'recentActivities' => $this->getDefaultActivities(),
                'chartsData' => $this->getDefaultCharts(),
                'alerts' => []
            ]);
        }
    }

    /**
     * Obtenir les statistiques de base
     */
    private function getBasicStats()
    {
        // Retourner des statistiques à 0 pour permettre les tests manuels
        return [
            'total_users' => 0,
            'active_users' => 0,
            'total_requests' => 0,
            'pending_requests' => 0,
            'approved_requests' => 0,
            'rejected_requests' => 0,
            'total_warehouses' => 0,
            'active_warehouses' => 0,
            'total_stocks' => 0,
            'low_stock_items' => 0,
            'total_personnel' => 0,
            'active_personnel' => 0,
            'today_requests' => 0,
            'month_requests' => 0,
            'fuel_available' => 0,
            'total_stock' => 0,
            'unread_notifications' => 0,
            'unread_messages' => 0
        ];
    }

    /**
     * Obtenir les activités de base
     */
    private function getBasicActivities()
    {
        // Retourner des activités vides pour permettre les tests manuels
        return [
            'recent_requests' => collect(),
            'recent_users' => collect(),
            'recent_movements' => collect()
        ];
    }

    /**
     * Obtenir les graphiques de base
     */
    private function getBasicCharts()
    {
        // Retourner des graphiques vides pour permettre les tests manuels
        return [
            'last7Days' => [
                ['label' => 'Lun', 'requests' => 0],
                ['label' => 'Mar', 'requests' => 0],
                ['label' => 'Mer', 'requests' => 0],
                ['label' => 'Jeu', 'requests' => 0],
                ['label' => 'Ven', 'requests' => 0],
                ['label' => 'Sam', 'requests' => 0],
                ['label' => 'Dim', 'requests' => 0]
            ],
            'entrepotsByRegion' => [],
            'fuelByEntrepot' => [],
            'stockByCategory' => []
        ];
    }

    /**
     * Obtenir les alertes de base
     */
    private function getBasicAlerts()
    {
        // Retourner des alertes vides pour permettre les tests manuels
        return [];
    }

    /**
     * Statistiques par défaut
     */
    private function getDefaultStats()
    {
        return [
            'total_users' => 0,
            'active_users' => 0,
            'total_requests' => 0,
            'pending_requests' => 0,
            'approved_requests' => 0,
            'rejected_requests' => 0,
            'total_warehouses' => 0,
            'active_warehouses' => 0,
            'total_stocks' => 0,
            'low_stock_items' => 0,
            'total_personnel' => 0,
            'active_personnel' => 0,
            'today_requests' => 0,
            'month_requests' => 0,
            'fuel_available' => 0,
            'total_stock' => 0,
            'unread_notifications' => 0,
            'unread_messages' => 0
        ];
    }

    /**
     * Activités par défaut
     */
    private function getDefaultActivities()
    {
        return [
            'recent_requests' => collect(),
            'recent_users' => collect(),
            'recent_movements' => collect()
        ];
    }

    /**
     * Graphiques par défaut
     */
    private function getDefaultCharts()
    {
        return [
            'last7Days' => [],
            'entrepotsByRegion' => [],
            'fuelByEntrepot' => [],
            'stockByCategory' => []
        ];
    }
}



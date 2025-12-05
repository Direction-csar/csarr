<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EmptyDashboardController extends Controller
{
    /**
     * Afficher le tableau de bord Admin complètement vide
     */
    public function index()
    {
        try {
            // Toutes les données à 0 pour permettre les tests manuels
            $stats = [
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
            
            // Activités vides
            $recentActivities = [
                'recent_requests' => collect(),
                'recent_users' => collect(),
                'recent_movements' => collect()
            ];
            
            // Graphiques vides
            $chartsData = [
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
            
            // Aucune alerte
            $alerts = [];
            
            return view('admin.dashboard.index', compact('stats', 'recentActivities', 'chartsData', 'alerts'));
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du dashboard vide', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'timestamp' => Carbon::now()
            ]);
            
            return view('admin.dashboard.index', [
                'stats' => array_fill_keys([
                    'total_users', 'active_users', 'total_requests', 'pending_requests',
                    'approved_requests', 'rejected_requests', 'total_warehouses', 'active_warehouses',
                    'total_stocks', 'low_stock_items', 'total_personnel', 'active_personnel',
                    'today_requests', 'month_requests', 'fuel_available', 'total_stock',
                    'unread_notifications', 'unread_messages'
                ], 0),
                'recentActivities' => [
                    'recent_requests' => collect(),
                    'recent_users' => collect(),
                    'recent_movements' => collect()
                ],
                'chartsData' => [
                    'last7Days' => [],
                    'entrepotsByRegion' => [],
                    'fuelByEntrepot' => [],
                    'stockByCategory' => []
                ],
                'alerts' => []
            ]);
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicRequest;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * Afficher les statistiques générales
     */
    public function index()
    {
        try {
            $stats = $this->getGeneralStats();
            $chartData = $this->getChartData();
            $recentActivities = $this->getRecentActivities();

            return view('admin.statistics.index', compact('stats', 'chartData', 'recentActivities'));

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des statistiques', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            // Retourner des données par défaut
            $stats = $this->getDefaultStats();
            $chartData = $this->getDefaultChartData();
            $recentActivities = [];

            return view('admin.statistics.index', compact('stats', 'chartData', 'recentActivities'))
                ->with('error', 'Erreur lors du chargement des statistiques. Affichage des données par défaut.');
        }
    }

    /**
     * Obtenir les statistiques générales
     */
    private function getGeneralStats()
    {
        try {
            return [
                'total_users' => User::count(),
                'total_demandes' => PublicRequest::count(),
                'pending_demandes' => PublicRequest::where('status', 'pending')->count(),
                'total_stock_movements' => StockMovement::count(),
                'total_warehouses' => Warehouse::count(),
                'total_personnel' => Personnel::count(),
                'active_users' => User::where('is_active', true)->count(),
                'recent_demandes' => PublicRequest::where('created_at', '>=', now()->subDays(7))->count()
            ];
        } catch (\Exception $e) {
            Log::error('Erreur dans getGeneralStats', ['error' => $e->getMessage()]);
            return $this->getDefaultStats();
        }
    }

    /**
     * Obtenir les données par défaut
     */
    private function getDefaultStats()
    {
        return [
            'total_users' => 0,
            'total_demandes' => 0,
            'pending_demandes' => 0,
            'total_stock_movements' => 0,
            'total_warehouses' => 0,
            'total_personnel' => 0,
            'active_users' => 0,
            'recent_demandes' => 0
        ];
    }

    /**
     * Obtenir les données pour les graphiques
     */
    private function getChartData()
    {
        try {
            // Statistiques des demandes par mois (6 derniers mois)
            $demandesParMois = PublicRequest::select(
                DB::raw('MONTH(created_at) as mois'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->pluck('total', 'mois')
            ->toArray();

            // Statistiques des utilisateurs par rôle
            $usersParRole = User::select('role', DB::raw('COUNT(*) as total'))
                ->groupBy('role')
                ->pluck('total', 'role')
                ->toArray();

            // Statistiques des demandes par statut
            $demandesParStatut = PublicRequest::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            // Statistiques des demandes par type
            $demandesParType = PublicRequest::select('type', DB::raw('COUNT(*) as total'))
                ->groupBy('type')
                ->pluck('total', 'type')
                ->toArray();

            // Statistiques des demandes par région
            $demandesParRegion = PublicRequest::select('region', DB::raw('COUNT(*) as total'))
                ->whereNotNull('region')
                ->groupBy('region')
                ->pluck('total', 'region')
                ->toArray();

            // Évolution des demandes sur 30 jours
            $evolutionDemandes = PublicRequest::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

            return [
                'demandes_par_mois' => $demandesParMois,
                'users_par_role' => $usersParRole,
                'demandes_par_statut' => $demandesParStatut,
                'demandes_par_type' => $demandesParType,
                'demandes_par_region' => $demandesParRegion,
                'evolution_demandes' => $evolutionDemandes
            ];
        } catch (\Exception $e) {
            Log::error('Erreur dans getChartData', ['error' => $e->getMessage()]);
            return $this->getDefaultChartData();
        }
    }

    /**
     * Obtenir les données par défaut pour les graphiques
     */
    private function getDefaultChartData()
    {
        return [
            'demandes_par_mois' => [],
            'users_par_role' => [],
            'mouvements_par_type' => []
        ];
    }

    /**
     * Obtenir les activités récentes
     */
    private function getRecentActivities()
    {
        try {
            $activities = [];

            // Dernières demandes
            $recentDemandes = PublicRequest::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            foreach ($recentDemandes as $demande) {
                $activities[] = [
                    'type' => 'demande',
                    'message' => "Nouvelle demande de " . ($demande->user->name ?: 'Utilisateur inconnu'),
                    'date' => $demande->created_at,
                    'icon' => 'fas fa-file-alt',
                    'color' => 'primary'
                ];
            }

            // Derniers mouvements de stock
            $recentMouvements = StockMovement::with('warehouse')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            foreach ($recentMouvements as $mouvement) {
                $activities[] = [
                    'type' => 'stock',
                    'message' => "Mouvement de stock: {$mouvement->type}",
                    'date' => $mouvement->created_at,
                    'icon' => 'fas fa-boxes',
                    'color' => 'success'
                ];
            }

            // Trier par date et limiter à 10
            usort($activities, function($a, $b) {
                return $b['date']->timestamp - $a['date']->timestamp;
            });

            return array_slice($activities, 0, 10);

        } catch (\Exception $e) {
            Log::error('Erreur dans getRecentActivities', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Exporter les statistiques
     */
    public function export(Request $request)
    {
        try {
            $stats = $this->getGeneralStats();
            $chartData = $this->getChartData();

            // Ici vous pouvez implémenter l'export en PDF ou Excel
            // Pour l'instant, on retourne une réponse JSON
            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'chart_data' => $chartData,
                    'exported_at' => now()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'export des statistiques', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'export des statistiques'
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\PublicRequest;
use App\Models\Warehouse;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Afficher la page des rapports (lecture seule pour DG)
     */
    public function index(Request $request)
    {
        try {
            // Statistiques générales
            $stats = [
                'total_requests' => PublicRequest::count(),
                'total_warehouses' => Warehouse::count(),
                'total_users' => User::count(),
                'total_personnel' => Personnel::count(),
                'total_movements' => StockMovement::count(),
            ];

            // Évolution des demandes (30 derniers jours)
            $requestsEvolution = DB::table('public_requests')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->get();

            // Statistiques par statut
            $statusStats = DB::table('public_requests')
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get();

            // Top entrepôts par activité
            $topWarehouses = DB::table('warehouses')
                ->leftJoin('public_requests', 'warehouses.id', '=', 'public_requests.warehouse_id')
                ->select(
                    'warehouses.name',
                    'warehouses.region',
                    DB::raw('COUNT(public_requests.id) as requests_count')
                )
                ->groupBy('warehouses.id', 'warehouses.name', 'warehouses.region')
                ->orderBy('requests_count', 'desc')
                ->limit(10)
                ->get();

            // Activité par mois
            $monthlyActivity = DB::table('public_requests')
                ->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();

            // Rapports récents (simulation)
            $reports = collect([
                (object)[
                    'id' => 1,
                    'name' => 'Rapport Mensuel Octobre 2025',
                    'description' => 'Analyse complète des activités',
                    'type' => 'Mensuel',
                    'period' => 'Octobre 2025',
                    'date_range' => '01/10/2025 - 31/10/2025',
                    'size' => '2.5 MB',
                    'created_at' => now()->subDays(2)
                ],
                (object)[
                    'id' => 2,
                    'name' => 'Rapport Demandes Q3 2025',
                    'description' => 'Bilan trimestriel des demandes',
                    'type' => 'Trimestriel',
                    'period' => 'Q3 2025',
                    'date_range' => '01/07/2025 - 30/09/2025',
                    'size' => '1.8 MB',
                    'created_at' => now()->subDays(15)
                ]
            ]);

            // Statistiques des rapports
            $reportStats = [
                'total_reports' => $reports->count(),
                'total_size' => '4.3 MB',
                'last_generated' => 'Il y a 2 jours'
            ];

            return view('dg.reports.index', compact(
                'stats',
                'requestsEvolution',
                'statusStats',
                'topWarehouses',
                'monthlyActivity',
                'reports',
                'reportStats'
            ));

        } catch (\Exception $e) {
            \Log::error('Erreur dans DG ReportsController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Erreur lors du chargement des rapports');
        }
    }

    /**
     * Générer un rapport personnalisé
     */
    public function generate(Request $request)
    {
        try {
            $request->validate([
                'report_type' => 'required|in:requests,warehouses,users,personnel',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date|after_or_equal:date_from',
                'format' => 'required|in:pdf,excel'
            ]);

            $reportType = $request->report_type;
            $dateFrom = $request->date_from;
            $dateTo = $request->date_to;
            $format = $request->format;

            // Générer le rapport selon le type
            switch ($reportType) {
                case 'requests':
                    $data = $this->generateRequestsReport($dateFrom, $dateTo);
                    break;
                case 'warehouses':
                    $data = $this->generateWarehousesReport();
                    break;
                case 'users':
                    $data = $this->generateUsersReport();
                    break;
                case 'personnel':
                    $data = $this->generatePersonnelReport();
                    break;
                default:
                    throw new \Exception('Type de rapport non supporté');
            }

            // Pour l'instant, on retourne les données en JSON
            // Dans une vraie application, on générerait un PDF ou Excel
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Rapport généré avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur dans DG ReportsController@generate', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la génération du rapport'
            ], 500);
        }
    }

    /**
     * Exporter les données
     */
    public function export(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:requests,warehouses,users,personnel',
                'format' => 'required|in:csv,excel'
            ]);

            // Pour l'instant, on retourne un message de succès
            // Dans une vraie application, on générerait le fichier d'export
            return response()->json([
                'success' => true,
                'message' => 'Export généré avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur dans DG ReportsController@export', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'export'
            ], 500);
        }
    }

    /**
     * Générer le rapport des demandes
     */
    private function generateRequestsReport($dateFrom = null, $dateTo = null)
    {
        $query = PublicRequest::with(['warehouse', 'user']);

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        return $query->get();
    }

    /**
     * Générer le rapport des entrepôts
     */
    private function generateWarehousesReport()
    {
        return Warehouse::withCount(['publicRequests', 'stockMovements'])->get();
    }

    /**
     * Générer le rapport des utilisateurs
     */
    private function generateUsersReport()
    {
        return User::withCount(['publicRequests', 'stockMovements'])->get();
    }

    /**
     * Générer le rapport du personnel
     */
    private function generatePersonnelReport()
    {
        return Personnel::all();
    }
}


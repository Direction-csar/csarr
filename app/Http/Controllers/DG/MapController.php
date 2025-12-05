<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\PublicRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    /**
     * Afficher la carte interactive (lecture seule pour DG)
     */
    public function index()
    {
        try {
            // Récupérer les entrepôts avec leurs coordonnées
            $warehouses = Warehouse::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where('is_active', 1)
                ->get();

            // Statistiques pour la carte
            $stats = [
                'total_warehouses' => $warehouses->count(),
                'total_requests' => PublicRequest::count(),
                'pending_requests' => PublicRequest::where('status', 'pending')->count(),
                'approved_requests' => PublicRequest::where('status', 'approved')->count(),
            ];

            return view('dg.map.index', compact('warehouses', 'stats'));

        } catch (\Exception $e) {
            \Log::error('Erreur dans DG MapController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Erreur lors du chargement de la carte');
        }
    }

    /**
     * Récupérer les données pour la carte (API)
     */
    public function getData(Request $request)
    {
        try {
            // Données des entrepôts
            $warehouses = Warehouse::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where('is_active', 1)
                ->select('id', 'name', 'latitude', 'longitude', 'region', 'address')
                ->get()
                ->map(function ($warehouse) {
                    // Compter les demandes par entrepôt
                    $requestsCount = PublicRequest::where('warehouse_id', $warehouse->id)->count();
                    $pendingRequests = PublicRequest::where('warehouse_id', $warehouse->id)
                        ->where('status', 'pending')->count();

                    return [
                        'id' => $warehouse->id,
                        'name' => $warehouse->name,
                        'latitude' => (float) $warehouse->latitude,
                        'longitude' => (float) $warehouse->longitude,
                        'region' => $warehouse->region,
                        'address' => $warehouse->address,
                        'requests_count' => $requestsCount,
                        'pending_requests' => $pendingRequests,
                        'status' => $pendingRequests > 0 ? 'warning' : 'success'
                    ];
                });

            // Données des demandes récentes
            $recentRequests = PublicRequest::with(['warehouse', 'user'])
                ->whereHas('warehouse', function($query) {
                    $query->whereNotNull('latitude')->whereNotNull('longitude');
                })
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get()
                ->map(function ($request) {
                    return [
                        'id' => $request->id,
                        'type' => $request->type,
                        'status' => $request->status,
                        'created_at' => $request->created_at->format('d/m/Y H:i'),
                        'user_name' => $request->user->name ?? 'N/A',
                        'warehouse_name' => $request->warehouse->name ?? 'N/A',
                        'latitude' => $request->warehouse ? (float) $request->warehouse->latitude : null,
                        'longitude' => $request->warehouse ? (float) $request->warehouse->longitude : null,
                    ];
                });

            // Statistiques par région
            $regionStats = DB::table('warehouses')
                ->join('public_requests', 'warehouses.id', '=', 'public_requests.warehouse_id')
                ->select(
                    'warehouses.region',
                    DB::raw('COUNT(public_requests.id) as total_requests'),
                    DB::raw('SUM(CASE WHEN public_requests.status = "pending" THEN 1 ELSE 0 END) as pending_requests'),
                    DB::raw('SUM(CASE WHEN public_requests.status = "approved" THEN 1 ELSE 0 END) as approved_requests')
                )
                ->whereNotNull('warehouses.region')
                ->groupBy('warehouses.region')
                ->get();

            return response()->json([
                'warehouses' => $warehouses,
                'recent_requests' => $recentRequests,
                'region_stats' => $regionStats,
                'success' => true
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur dans DG MapController@getData', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'error' => 'Erreur lors du chargement des données de la carte',
                'success' => false
            ], 500);
        }
    }
}


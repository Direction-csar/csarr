<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehousesController extends Controller
{
    /**
     * Afficher la liste des entrepôts (lecture seule pour DG)
     */
    public function index(Request $request)
    {
        try {
            $query = Warehouse::withCount(['stockMovements', 'publicRequests'])
                ->orderBy('name');

            // Filtres
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active' ? 1 : 0);
            }

            if ($request->filled('region')) {
                $query->where('region', $request->region);
            }

            $warehouses = $query->paginate(20);

            // Statistiques
            $stats = [
                'total' => Warehouse::count(),
                'active' => Warehouse::where('is_active', 1)->count(),
                'inactive' => Warehouse::where('is_active', 0)->count(),
            ];

            return view('dg.warehouses.index', compact('warehouses', 'stats'));

        } catch (\Exception $e) {
            \Log::error('Erreur dans DG WarehousesController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Erreur lors du chargement des entrepôts');
        }
    }

    /**
     * Afficher les détails d'un entrepôt
     */
    public function show(Warehouse $warehouse)
    {
        try {
            $warehouse->load(['stockMovements' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            }, 'publicRequests' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            }]);

            // Statistiques de l'entrepôt
            $stats = [
                'total_movements' => $warehouse->stockMovements()->count(),
                'total_requests' => $warehouse->publicRequests()->count(),
                'pending_requests' => $warehouse->publicRequests()->where('status', 'pending')->count(),
                'approved_requests' => $warehouse->publicRequests()->where('status', 'approved')->count(),
            ];

            return view('dg.warehouses.show', compact('warehouse', 'stats'));

        } catch (\Exception $e) {
            \Log::error('Erreur dans DG WarehousesController@show', [
                'error' => $e->getMessage(),
                'warehouse_id' => $warehouse->id,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Erreur lors du chargement de l\'entrepôt');
        }
    }
}


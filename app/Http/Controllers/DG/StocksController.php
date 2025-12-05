<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StocksController extends Controller
{
    /**
     * Afficher la vue d'ensemble des stocks (lecture seule pour DG)
     */
    public function index(Request $request)
    {
        try {
            // Statistiques générales
            $stats = [
                'total_movements' => StockMovement::count(),
                'total_quantity' => StockMovement::sum('quantity'),
                'warehouses_count' => Warehouse::where('is_active', 1)->count(),
                'today_movements' => StockMovement::whereDate('created_at', today())->count(),
            ];

            // Mouvements récents
            $recentMovements = StockMovement::with(['warehouse', 'user'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Top entrepôts par activité
            $topWarehouses = DB::table('stock_movements')
                ->join('warehouses', 'stock_movements.warehouse_id', '=', 'warehouses.id')
                ->select('warehouses.name', 'warehouses.region', DB::raw('COUNT(stock_movements.id) as movements_count'))
                ->groupBy('warehouses.id', 'warehouses.name', 'warehouses.region')
                ->orderBy('movements_count', 'desc')
                ->limit(5)
                ->get();

            // Évolution des mouvements (7 derniers jours)
            $movementsEvolution = DB::table('stock_movements')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->get();

            return view('dg.stocks.index', compact('stats', 'recentMovements', 'topWarehouses', 'movementsEvolution'));

        } catch (\Exception $e) {
            \Log::error('Erreur dans DG StocksController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Erreur lors du chargement des stocks');
        }
    }

    /**
     * Afficher les mouvements de stock
     */
    public function movements(Request $request)
    {
        try {
            $query = StockMovement::with(['warehouse', 'user'])
                ->orderBy('created_at', 'desc');

            // Filtres
            if ($request->filled('warehouse_id')) {
                $query->where('warehouse_id', $request->warehouse_id);
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $movements = $query->paginate(20);

            // Entrepôts pour le filtre
            $warehouses = Warehouse::where('is_active', 1)
                ->orderBy('name')
                ->get();

            return view('dg.stocks.movements', compact('movements', 'warehouses'));

        } catch (\Exception $e) {
            \Log::error('Erreur dans DG StocksController@movements', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Erreur lors du chargement des mouvements');
        }
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Models\Stock;
use App\Models\SimReport;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Recherche globale
     */
    public function global(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');
        $user = Auth::user();

        if (empty($query)) {
            return view('search.results', [
                'query' => $query,
                'results' => collect(),
                'type' => $type
            ]);
        }

        $results = collect();

        // Recherche dans les demandes
        if ($type === 'all' || $type === 'demandes') {
            $demandes = $this->searchDemandes($query, $user);
            $results = $results->merge($demandes->map(function ($item) {
                return [
                    'type' => 'demande',
                    'title' => 'Demande #' . $item->id,
                    'description' => $item->description,
                    'url' => route('demandes.show', $item->id),
                    'date' => $item->created_at,
                    'status' => $item->status
                ];
            }));
        }

        // Recherche dans les stocks
        if ($type === 'all' || $type === 'stocks') {
            $stocks = $this->searchStocks($query, $user);
            $results = $results->merge($stocks->map(function ($item) {
                return [
                    'type' => 'stock',
                    'title' => $item->stockType->name ?? 'Stock',
                    'description' => 'Quantité: ' . $item->quantity . ' - Entrepôt: ' . $item->warehouse->name,
                    'url' => route('stocks.show', $item->id),
                    'date' => $item->updated_at,
                    'status' => $item->quantity > 100 ? 'good' : 'low'
                ];
            }));
        }

        // Recherche dans les rapports
        if ($type === 'all' || $type === 'reports') {
            $reports = $this->searchReports($query, $user);
            $results = $results->merge($reports->map(function ($item) {
                return [
                    'type' => 'report',
                    'title' => $item->title,
                    'description' => $item->description,
                    'url' => route('sim-reports.show', $item->id),
                    'date' => $item->created_at,
                    'status' => $item->status
                ];
            }));
        }

        // Recherche dans les entrepôts
        if ($type === 'all' || $type === 'warehouses') {
            $warehouses = $this->searchWarehouses($query, $user);
            $results = $results->merge($warehouses->map(function ($item) {
                return [
                    'type' => 'warehouse',
                    'title' => $item->name,
                    'description' => $item->address . ' - ' . $item->region,
                    'url' => route('warehouses.show', $item->id),
                    'date' => $item->updated_at,
                    'status' => 'active'
                ];
            }));
        }

        // Recherche dans les utilisateurs (admin seulement)
        if (($type === 'all' || $type === 'users') && $user->role === 'admin') {
            $users = $this->searchUsers($query);
            $results = $results->merge($users->map(function ($item) {
                return [
                    'type' => 'user',
                    'title' => $item->name,
                    'description' => $item->email . ' - ' . ucfirst($item->role),
                    'url' => route('users.show', $item->id),
                    'date' => $item->created_at,
                    'status' => 'active'
                ];
            }));
        }

        // Trier par date
        $results = $results->sortByDesc('date');

        return view('search.results', [
            'query' => $query,
            'results' => $results,
            'type' => $type
        ]);
    }

    /**
     * Recherche dans les demandes
     */
    private function searchDemandes($query, $user)
    {
        $demandes = PublicRequest::query();

        // Filtrage par rôle
        if ($user->role === 'agent') {
            $demandes->where('user_id', $user->id);
        } elseif ($user->role === 'responsable') {
            $demandes->where('warehouse_id', $user->warehouse_id);
        }

        return $demandes->where(function ($q) use ($query) {
            $q->where('id', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%")
              ->orWhere('status', 'like', "%{$query}%")
              ->orWhere('region', 'like', "%{$query}%");
        })->with(['user', 'warehouse'])->limit(20)->get();
    }

    /**
     * Recherche dans les stocks
     */
    private function searchStocks($query, $user)
    {
        $stocks = Stock::query();

        // Filtrage par rôle
        if ($user->role === 'responsable') {
            $stocks->where('warehouse_id', $user->warehouse_id);
        }

        return $stocks->whereHas('stockType', function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%");
        })->orWhere('quantity', 'like', "%{$query}%")
          ->with(['stockType', 'warehouse'])
          ->limit(20)
          ->get();
    }

    /**
     * Recherche dans les rapports
     */
    private function searchReports($query, $user)
    {
        return SimReport::where(function ($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%")
              ->orWhere('region', 'like', "%{$query}%")
              ->orWhere('market_sector', 'like', "%{$query}%");
        })->where('is_public', true)
          ->limit(20)
          ->get();
    }

    /**
     * Recherche dans les entrepôts
     */
    private function searchWarehouses($query, $user)
    {
        return Warehouse::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('address', 'like', "%{$query}%")
              ->orWhere('region', 'like', "%{$query}%")
              ->orWhere('city', 'like', "%{$query}%");
        })->limit(20)->get();
    }

    /**
     * Recherche dans les utilisateurs
     */
    private function searchUsers($query)
    {
        return User::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%")
              ->orWhere('role', 'like', "%{$query}%");
        })->limit(20)->get();
    }

    /**
     * Recherche rapide (API)
     */
    public function quickSearch(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $results = collect();

        // Recherche rapide dans les demandes
        $demandes = PublicRequest::where('id', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        foreach ($demandes as $demande) {
            $results->push([
                'type' => 'demande',
                'title' => 'Demande #' . $demande->id,
                'description' => substr($demande->description, 0, 100) . '...',
                'url' => route('demandes.show', $demande->id)
            ]);
        }

        // Recherche rapide dans les stocks
        $stocks = Stock::whereHas('stockType', function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%");
        })->with('stockType')->limit(5)->get();

        foreach ($stocks as $stock) {
            $results->push([
                'type' => 'stock',
                'title' => $stock->stockType->name ?? 'Stock',
                'description' => 'Quantité: ' . $stock->quantity,
                'url' => route('stocks.show', $stock->id)
            ]);
        }

        return response()->json($results->take(10));
    }

    /**
     * Suggestions de recherche
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = collect();

        // Suggestions de demandes
        $demandeIds = PublicRequest::where('id', 'like', "%{$query}%")
            ->pluck('id')
            ->take(3);

        foreach ($demandeIds as $id) {
            $suggestions->push([
                'text' => 'Demande #' . $id,
                'type' => 'demande',
                'url' => route('demandes.show', $id)
            ]);
        }

        // Suggestions de types de stock
        $stockTypes = \App\Models\StockType::where('name', 'like', "%{$query}%")
            ->pluck('name')
            ->take(3);

        foreach ($stockTypes as $name) {
            $suggestions->push([
                'text' => $name,
                'type' => 'stock_type',
                'url' => route('stocks.index', ['search' => $name])
            ]);
        }

        // Suggestions de régions
        $regions = Warehouse::where('region', 'like', "%{$query}%")
            ->distinct()
            ->pluck('region')
            ->take(3);

        foreach ($regions as $region) {
            $suggestions->push([
                'text' => $region,
                'type' => 'region',
                'url' => route('warehouses.index', ['region' => $region])
            ]);
        }

        return response()->json($suggestions);
    }
}

<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter d\'abord.');
        }
        
        // Récupérer l'entrepôt du responsable
        $warehouse = $user->warehouse;
        
        if (!$warehouse) {
            // Assigner automatiquement le premier entrepôt disponible pour les tests
            $availableWarehouse = Warehouse::where('is_active', true)->first();
            
            if ($availableWarehouse) {
                $user->update(['warehouse_id' => $availableWarehouse->id]);
                $warehouse = $availableWarehouse;
            } else {
                // Créer un entrepôt de test si aucun n'existe
                $warehouse = Warehouse::create([
                    'name' => 'Entrepôt Principal - ' . $user->name,
                    'description' => 'Entrepôt assigné automatiquement',
                    'address' => 'Dakar, Sénégal',
                    'region' => 'Dakar',
                    'city' => 'Dakar',
                    'phone' => '+221 33 123 45 67',
                    'email' => 'entrepot@csar.sn',
                    'is_active' => true,
                    'capacity' => 1000,
                    'current_stock' => 0,
                    'status' => 'active',
                    'latitude' => 14.7167,
                    'longitude' => -17.4677
                ]);
                
                $user->update(['warehouse_id' => $warehouse->id]);
            }
        }
        
        // Calculer les statistiques de l'entrepôt
        $warehouseData = [
            'name' => $warehouse->name ?? 'Entrepôt CSAR',
            'type' => $warehouse->type ?? 'Mixte',
            'status' => ($warehouse->is_active ?? true) ? 'Actif' : 'Inactif',
            'capacity' => $warehouse->capacity ?? 1000,
            'location' => $warehouse->address ?? 'Dakar, Sénégal',
            'region' => $warehouse->region ?? 'Dakar',
        ];
        
        // Calculer les statistiques de stock
        $stockStats = $this->calculateStockStats($warehouse->id);
        
        // Récupérer les alertes de stock
        $alerts = $this->getStockAlerts($warehouse->id);
        
        // Récupérer les mouvements récents
        $recentMovements = $this->getRecentMovements($warehouse->id);
        
        return view('responsable.dashboard', compact('warehouseData', 'stockStats', 'alerts', 'recentMovements'));
    }
    
    /**
     * Calculer les statistiques de stock
     */
    private function calculateStockStats($warehouseId)
    {
        try {
            $stockMovements = StockMovement::where('warehouse_id', $warehouseId)->get();
            $stocks = \App\Models\Stock::where('warehouse_id', $warehouseId)->get();
            
            $totalStock = $stocks->sum('quantity');
            $categories = $stocks->groupBy('category');
            
            return [
                'total_stock' => $totalStock,
                'categories' => $categories,
                'total_items' => $stocks->count(),
                'low_stock_items' => $stocks->where('quantity', '<', 100)->count(),
                'out_of_stock_items' => $stocks->where('quantity', 0)->count(),
            ];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur dans calculateStockStats', ['error' => $e->getMessage()]);
            return [
                'total_stock' => 0,
                'categories' => collect(),
                'total_items' => 0,
                'low_stock_items' => 0,
                'out_of_stock_items' => 0,
            ];
        }
    }
    
    /**
     * Récupérer les alertes de stock
     */
    private function getStockAlerts($warehouseId)
    {
        try {
            $alerts = [];
            $stocks = \App\Models\Stock::where('warehouse_id', $warehouseId)->get();
            
            foreach ($stocks as $stock) {
                if ($stock->quantity < 100) {
                    $alerts[] = [
                        'type' => 'warning',
                        'title' => 'Stock critique',
                        'message' => "Le stock de {$stock->item_name} est en dessous du seuil critique ({$stock->quantity} restants)",
                        'time' => 'Maintenant',
                        'product' => $stock->item_name,
                        'current_stock' => $stock->quantity,
                        'threshold' => 100
                    ];
                }
            }
            
            return $alerts;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur dans getStockAlerts', ['error' => $e->getMessage()]);
            return [];
        }
    }
    
    /**
     * Récupérer les mouvements récents
     */
    private function getRecentMovements($warehouseId)
    {
        try {
            $movements = StockMovement::where('warehouse_id', $warehouseId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            return $movements->map(function($movement) {
                return [
                    'type' => $movement->type,
                    'product' => $movement->product_name ?? 'Produit',
                    'quantity' => ($movement->type === 'in' ? '+' : '-') . $movement->quantity . ' ' . ($movement->unit ?? ''),
                    'time' => $movement->created_at->diffForHumans(),
                    'color' => $movement->type === 'in' ? '#059669' : '#dc2626',
                    'user' => $movement->created_by ? \App\Models\User::find($movement->created_by)->name ?? 'Utilisateur' : 'Système',
                    'supplier_or_destination' => $movement->supplier_or_destination ?? 'N/A'
                ];
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur dans getRecentMovements', ['error' => $e->getMessage()]);
            return collect();
        }
    }
}

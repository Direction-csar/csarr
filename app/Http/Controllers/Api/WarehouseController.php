<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WarehouseController extends Controller
{
    /**
     * Obtenir tous les entrepôts pour la carte
     */
    public function index(): JsonResponse
    {
        try {
            $warehouses = Warehouse::with(['responsable', 'stocks'])
                ->select([
                    'id', 'nom', 'adresse', 'latitude', 'longitude', 
                    'capacite', 'status', 'responsable_id', 'created_at'
                ])
                ->get()
                ->map(function ($warehouse) {
                    return [
                        'id' => $warehouse->id,
                        'nom' => $warehouse->nom,
                        'adresse' => $warehouse->adresse,
                        'latitude' => (float) $warehouse->latitude,
                        'longitude' => (float) $warehouse->longitude,
                        'capacite' => $warehouse->capacite,
                        'status' => $warehouse->status,
                        'responsable_nom' => $warehouse->responsable ? 
                            $warehouse->responsable->nom . ' ' . $warehouse->responsable->prenom : null,
                        'stock_total' => $warehouse->stocks->sum('quantite'),
                        'created_at' => $warehouse->created_at->toISOString()
                    ];
                });

            return response()->json($warehouses);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors du chargement des entrepôts',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques des entrepôts
     */
    public function stats(): JsonResponse
    {
        try {
            $warehouses = Warehouse::with('stocks')->get();
            
            $activeWarehouses = $warehouses->where('status', 'active')->count();
            $totalStock = $warehouses->sum(function ($warehouse) {
                return $warehouse->stocks->sum('quantite');
            });
            
            $totalCapacity = $warehouses->sum('capacite');
            $occupationRate = $totalCapacity > 0 ? 
                round(($totalStock / $totalCapacity) * 100, 1) : 0;
            
            $alerts = $warehouses->filter(function ($warehouse) {
                $stockLevel = $warehouse->stocks->sum('quantite');
                $capacity = $warehouse->capacite;
                return $capacity > 0 && ($stockLevel / $capacity) >= 0.9;
            })->count();

            return response()->json([
                'active_warehouses' => $activeWarehouses,
                'total_warehouses' => $warehouses->count(),
                'total_stock' => $totalStock,
                'total_capacity' => $totalCapacity,
                'occupation_rate' => $occupationRate,
                'alerts' => $alerts,
                'last_updated' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors du calcul des statistiques',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir un entrepôt spécifique
     */
    public function show($id): JsonResponse
    {
        try {
            $warehouse = Warehouse::with(['responsable', 'stocks.stockType'])
                ->findOrFail($id);

            return response()->json([
                'id' => $warehouse->id,
                'nom' => $warehouse->nom,
                'adresse' => $warehouse->adresse,
                'latitude' => (float) $warehouse->latitude,
                'longitude' => (float) $warehouse->longitude,
                'capacite' => $warehouse->capacite,
                'status' => $warehouse->status,
                'responsable' => $warehouse->responsable ? [
                    'id' => $warehouse->responsable->id,
                    'nom' => $warehouse->responsable->nom,
                    'prenom' => $warehouse->responsable->prenom,
                    'email' => $warehouse->responsable->email,
                    'telephone' => $warehouse->responsable->telephone
                ] : null,
                'stocks' => $warehouse->stocks->map(function ($stock) {
                    return [
                        'id' => $stock->id,
                        'type' => $stock->stockType->nom ?? 'Inconnu',
                        'quantite' => $stock->quantite,
                        'prix_unitaire' => $stock->prix_unitaire,
                        'date_expiration' => $stock->date_expiration
                    ];
                }),
                'stock_total' => $warehouse->stocks->sum('quantite'),
                'created_at' => $warehouse->created_at->toISOString(),
                'updated_at' => $warehouse->updated_at->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Entrepôt non trouvé',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mettre à jour la position d'un entrepôt
     */
    public function updatePosition(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180'
            ]);

            $warehouse = Warehouse::findOrFail($id);
            $warehouse->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);

            // Diffuser la mise à jour
            broadcast(new \App\Events\WarehouseUpdated($warehouse))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Position mise à jour avec succès',
                'warehouse' => [
                    'id' => $warehouse->id,
                    'latitude' => (float) $warehouse->latitude,
                    'longitude' => (float) $warehouse->longitude
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

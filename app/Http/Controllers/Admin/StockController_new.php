<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockLevel;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $mouvements = $this->getStockMovements($request);
            $stats = $this->getStockStats();
            
            return view('admin.stock.index', compact('mouvements', 'stats'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage des mouvements de stock: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors du chargement des données.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $entrepots = Warehouse::where('is_active', true)->get();
            $produits = Product::active()->orderBy('name')->get();
            
            return view('admin.stock.create', compact('entrepots', 'produits'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de création: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors du chargement du formulaire.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:in,out',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            // Générer la référence automatique
            $reference = $request->reference ?: $this->generateReference($request->type);
            
            // Récupérer le stock actuel pour cet entrepôt
            $currentStock = Stock::where('warehouse_id', $request->warehouse_id)->first();
            $quantityBefore = $currentStock ? $currentStock->current_stock : 0;
            
            // Calculer la nouvelle quantité
            $quantityChange = $request->type === 'in' ? $request->quantity : -$request->quantity;
            $quantityAfter = $quantityBefore + $quantityChange;
            
            // Créer le mouvement de stock
            $mouvement = StockMovement::create([
                'warehouse_id' => $request->warehouse_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'reason' => $request->reason,
                'reference' => $reference,
                'reference_number' => $this->getReferenceNumber($reference),
                'created_by' => auth()->id()
            ]);

            // Mettre à jour le stock de l'entrepôt
            if ($currentStock) {
                $currentStock->update(['current_stock' => $quantityAfter]);
            } else {
                Stock::create([
                    'warehouse_id' => $request->warehouse_id,
                    'current_stock' => $quantityAfter
                ]);
            }

            // Créer une notification
            Notification::create([
                'type' => 'stock_movement_created',
                'title' => 'Nouveau mouvement de stock',
                'message' => "Un nouveau mouvement {$mouvement->type} a été enregistré",
                'data' => ['mouvement_id' => $mouvement->id],
                'user_id' => null
            ]);

            DB::commit();

            return redirect()->route('admin.stock.index')
                ->with('success', 'Mouvement de stock créé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du mouvement de stock: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du mouvement de stock.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $mouvement = StockMovement::with(['warehouse', 'creator'])->findOrFail($id);
            return view('admin.stock.show', compact('mouvement'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage du mouvement: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Mouvement non trouvé.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $mouvement = StockMovement::findOrFail($id);
            $entrepots = Warehouse::where('is_active', true)->get();
            $produits = Product::active()->orderBy('name')->get();
            
            return view('admin.stock.edit', compact('mouvement', 'entrepots', 'produits'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage du formulaire d\'édition: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Mouvement non trouvé.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:in,out',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $mouvement = StockMovement::findOrFail($id);
            
            // Mettre à jour le mouvement
            $mouvement->update([
                'warehouse_id' => $request->warehouse_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'reference' => $request->reference
            ]);

            // Créer une notification
            Notification::create([
                'type' => 'stock_movement_updated',
                'title' => 'Mouvement de stock modifié',
                'message' => "Le mouvement {$mouvement->reference} a été modifié",
                'data' => ['mouvement_id' => $mouvement->id],
                'user_id' => null
            ]);

            DB::commit();

            return redirect()->route('admin.stock.index')
                ->with('success', 'Mouvement de stock modifié avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la modification du mouvement: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification du mouvement.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $mouvement = StockMovement::findOrFail($id);
            $mouvement->delete();

            // Créer une notification
            Notification::create([
                'type' => 'stock_movement_deleted',
                'title' => 'Mouvement de stock supprimé',
                'message' => "Le mouvement {$mouvement->reference} a été supprimé",
                'data' => ['mouvement_id' => $mouvement->id],
                'user_id' => null
            ]);

            DB::commit();

            return redirect()->route('admin.stock.index')
                ->with('success', 'Mouvement de stock supprimé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression du mouvement: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Erreur lors de la suppression du mouvement.');
        }
    }

    /**
     * Download receipt for a stock movement
     */
    public function downloadReceipt($id)
    {
        try {
            $mouvement = StockMovement::with(['warehouse', 'creator'])->findOrFail($id);
            return $this->generateReceiptPdf($mouvement);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du reçu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la génération du reçu.');
        }
    }

    /**
     * Export stock movements
     */
    public function export(Request $request)
    {
        try {
            $mouvements = $this->getStockMovements($request, false);
            return $this->generateCsv($mouvements);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'export: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'export.');
        }
    }

    // Méthodes privées

    private function getStockMovements($request, $paginate = true)
    {
        $query = StockMovement::with(['warehouse', 'creator']);

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('warehouse')) {
            $query->where('warehouse_id', $request->warehouse);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $query->orderBy('created_at', 'desc');

        return $paginate ? $query->paginate(15) : $query->get();
    }

    private function getStockStats()
    {
        $totalMovements = StockMovement::count();
        $entries = StockMovement::where('type', 'in')->count();
        $exits = StockMovement::where('type', 'out')->count();
        
        $totalValue = StockMovement::where('type', 'in')
            ->sum(DB::raw('quantity * 1000')); // Prix moyen estimé

        return [
            'total_movements' => $totalMovements,
            'entries' => $entries,
            'exits' => $exits,
            'transfers' => 0, // Pas de transferts dans cette structure
            'adjustments' => 0, // Pas d'ajustements dans cette structure
            'total_value' => $totalValue
        ];
    }

    private function generateReference($type)
    {
        $year = date('Y');
        $prefix = $type === 'in' ? 'ENT' : 'SOR';
        
        $lastMovement = StockMovement::where('reference', 'like', "{$prefix}-{$year}-%")
            ->orderBy('reference_number', 'desc')
            ->first();
        
        $nextNumber = $lastMovement ? $lastMovement->reference_number + 1 : 1;
        
        return [
            'reference' => "{$prefix}-{$year}-" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT),
            'number' => $nextNumber
        ];
    }

    private function getReferenceNumber($reference)
    {
        $parts = explode('-', $reference);
        return (int) end($parts);
    }

    private function generateReceiptPdf($mouvement)
    {
        // Implémentation simple du PDF
        $content = "REÇU DE MOUVEMENT DE STOCK\n\n";
        $content .= "Référence: {$mouvement->reference}\n";
        $content .= "Type: {$mouvement->type}\n";
        $content .= "Quantité: {$mouvement->quantity}\n";
        $content .= "Entrepôt: {$mouvement->warehouse->name}\n";
        $content .= "Motif: {$mouvement->reason}\n";
        $content .= "Date: {$mouvement->created_at->format('d/m/Y H:i')}\n";
        
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="receipt_' . $mouvement->reference . '.txt"');
    }

    private function generateCsv($mouvements)
    {
        $csv = "Référence,Type,Quantité,Entrepôt,Motif,Date\n";
        
        foreach ($mouvements as $mouvement) {
            $csv .= "{$mouvement->reference},{$mouvement->type},{$mouvement->quantity},{$mouvement->warehouse->name},{$mouvement->reason},{$mouvement->created_at->format('d/m/Y H:i')}\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="mouvements_stock_' . date('Y-m-d') . '.csv"');
    }
}



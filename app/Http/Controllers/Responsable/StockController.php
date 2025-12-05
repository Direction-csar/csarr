<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Carbon\Carbon;

class StockController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $warehouse = $user->warehouse;
        
        if (!$warehouse) {
            return redirect()->route('responsable.profile')->with('error', 'Aucun entrepôt assigné.');
        }
        
        // Récupérer les stocks actuels
        $currentStocks = $this->getCurrentStocks($warehouse->id);
        
        // Organiser par catégories
        $stockData = [
            'total_items' => $currentStocks->count(),
            'low_stock_items' => $currentStocks->where('quantity', '<', 100)->count(),
            'out_of_stock_items' => $currentStocks->where('quantity', 0)->count(),
            'categories' => $currentStocks->groupBy('category')
        ];
        
        return view('responsable.stock.index', compact('stockData', 'warehouse'));
    }
    
    public function create()
    {
        $user = Auth::user();
        $warehouse = $user->warehouse;
        
        if (!$warehouse) {
            return redirect()->route('responsable.profile')->with('error', 'Aucun entrepôt assigné.');
        }
        
        // Produits disponibles selon le type d'entrepôt
        $products = $this->getAvailableProducts($warehouse->type);
        
        return view('responsable.stock.create', compact('products', 'warehouse'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string',
            'product_category' => 'required|in:denrees,materiel,carburant',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string',
            'supplier' => 'required|string',
            'movement_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);
        
        $user = Auth::user();
        $warehouse = $user->warehouse;
        
        if (!$warehouse) {
            return back()->with('error', 'Aucun entrepôt assigné.');
        }
        
        // Calculer le stock avant et après
        $stockBefore = StockMovement::getCurrentStock($warehouse->id, $request->product_name);
        $stockAfter = StockMovement::calculateStockAfter($warehouse->id, $request->product_name, $request->quantity, 'in');
        
        // Créer le mouvement
        StockMovement::create([
            'warehouse_id' => $warehouse->id,
            'user_id' => $user->id,
            'type' => 'in',
            'product_name' => $request->product_name,
            'product_category' => $request->product_category,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'supplier_or_destination' => $request->supplier,
            'movement_date' => $request->movement_date,
            'notes' => $request->notes,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter
        ]);
        
        return redirect()->route('responsable.stock')->with('success', 'Entrée de stock enregistrée avec succès');
    }
    
    public function createOut()
    {
        $user = Auth::user();
        $warehouse = $user->warehouse;
        
        if (!$warehouse) {
            return redirect()->route('responsable.profile')->with('error', 'Aucun entrepôt assigné.');
        }
        
        // Produits disponibles avec stock actuel
        $availableProducts = $this->getAvailableProductsWithStock($warehouse->id);
        
        return view('responsable.stock.out', compact('availableProducts', 'warehouse'));
    }
    
    public function storeOut(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string',
            'quantity' => 'required|numeric|min:0.01',
            'destination' => 'required|string',
            'movement_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);
        
        $user = Auth::user();
        $warehouse = $user->warehouse;
        
        if (!$warehouse) {
            return back()->with('error', 'Aucun entrepôt assigné.');
        }
        
        // Vérifier le stock disponible
        $currentStock = StockMovement::getCurrentStock($warehouse->id, $request->product_name);
        
        if ($currentStock < $request->quantity) {
            return back()->withErrors(['quantity' => 'Stock insuffisant. Stock disponible : ' . $currentStock]);
        }
        
        // Récupérer les informations du produit
        $lastMovement = StockMovement::where('warehouse_id', $warehouse->id)
            ->where('product_name', $request->product_name)
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Calculer le stock après
        $stockAfter = StockMovement::calculateStockAfter($warehouse->id, $request->product_name, $request->quantity, 'out');
        
        // Créer le mouvement
        StockMovement::create([
            'warehouse_id' => $warehouse->id,
            'user_id' => $user->id,
            'type' => 'out',
            'product_name' => $request->product_name,
            'product_category' => $lastMovement->product_category,
            'quantity' => $request->quantity,
            'unit' => $lastMovement->unit,
            'supplier_or_destination' => $request->destination,
            'movement_date' => $request->movement_date,
            'notes' => $request->notes,
            'stock_before' => $currentStock,
            'stock_after' => $stockAfter
        ]);
        
        return redirect()->route('responsable.stock')->with('success', 'Sortie de stock enregistrée avec succès');
    }
    
    public function movements(Request $request)
    {
        $user = Auth::user();
        $warehouse = $user->warehouse;
        
        if (!$warehouse) {
            return redirect()->route('responsable.profile')->with('error', 'Aucun entrepôt assigné.');
        }
        
        $query = StockMovement::where('warehouse_id', $warehouse->id)->with('user');
        
        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('category')) {
            $query->where('product_category', $request->category);
        }
        
        if ($request->filled('product')) {
            $query->where('product_name', 'like', '%' . $request->product . '%');
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('movement_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('movement_date', '<=', $request->date_to);
        }
        
        $movements = $query->orderBy('created_at', 'desc')->paginate(50);
        
        // Statistiques
        $stats = [
            'total_movements' => StockMovement::where('warehouse_id', $warehouse->id)->count(),
            'entries' => StockMovement::where('warehouse_id', $warehouse->id)->where('type', 'in')->count(),
            'exits' => StockMovement::where('warehouse_id', $warehouse->id)->where('type', 'out')->count(),
        ];
        
        return view('responsable.movements', compact('movements', 'stats', 'warehouse'));
    }
    
    public function exportMovementsPdf(Request $request)
    {
        $user = Auth::user();
        $warehouse = $user->warehouse;
        
        if (!$warehouse) {
            return back()->with('error', 'Aucun entrepôt assigné.');
        }
        
        $query = StockMovement::where('warehouse_id', $warehouse->id)->with('user');
        
        // Appliquer les mêmes filtres que l'index
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('category')) {
            $query->where('product_category', $request->category);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('movement_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('movement_date', '<=', $request->date_to);
        }
        
        $movements = $query->orderBy('created_at', 'desc')->get();
        
        $pdf = PDF::loadView('responsable.movements-pdf', compact('movements', 'warehouse'));
        
        return $pdf->download('mouvements-stock-' . $warehouse->name . '-' . date('Y-m-d') . '.pdf');
    }
    
    public function exportMovementsExcel(Request $request)
    {
        // Implémentation de l'export Excel
        // À compléter selon les besoins
        return back()->with('info', 'Export Excel en cours de développement');
    }
    
    public function location()
    {
        $user = Auth::user();
        $warehouse = $user->warehouse;
        
        if (!$warehouse) {
            return redirect()->route('responsable.profile')->with('error', 'Aucun entrepôt assigné.');
        }
        
        // Données de localisation par défaut si pas encore configurées
        $location = [
            'latitude' => $warehouse->latitude ?? '14.6937',
            'longitude' => $warehouse->longitude ?? '-17.4441',
            'address' => $warehouse->address ?? 'Dakar, Sénégal',
            'last_updated' => $warehouse->updated_at ?? now()
        ];
        
        return view('responsable.location', compact('warehouse', 'location'));
    }
    
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);
        
        $user = Auth::user();
        $warehouse = $user->warehouse;
        
        if (!$warehouse) {
            return back()->with('error', 'Aucun entrepôt assigné.');
        }
        
        $warehouse->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);
        
        return back()->with('success', 'Localisation mise à jour avec succès');
    }
    
    public function profile()
    {
        $user = Auth::user();
        $warehouse = $user->warehouse;
        
        // Préparer les données du profil
        $profileData = [
            'user' => $user,
            'warehouse' => $warehouse,
            'role' => $user->role ? $user->role->display_name : 'Responsable'
        ];
        
        return view('responsable.profile', compact('user', 'warehouse'));
    }
    
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'email', 'phone', 'address']));

        return redirect()->route('responsable.profile')->with('success', 'Profil mis à jour avec succès');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return redirect()->route('responsable.profile')->with('success', 'Mot de passe mis à jour avec succès');
    }
    
    /**
     * Récupérer les stocks actuels
     */
    private function getCurrentStocks($warehouseId)
    {
        return Stock::where('warehouse_id', $warehouseId)
            ->where('is_active', true)
            ->with(['stockType'])
            ->get()
            ->map(function ($stock) {
                return [
                    'name' => $stock->item_name,
                    'category' => $stock->stockType ? $stock->stockType->name : 'Non classé',
                    'quantity' => $stock->quantity,
                    'unit' => 'kg', // Par défaut
                    'min_threshold' => $stock->min_quantity,
                    'status' => $this->getStockStatus($stock->quantity, $stock->min_quantity),
                    'expiry_date' => $stock->expiry_date,
                    'batch_number' => $stock->batch_number,
                    'supplier' => $stock->supplier
                ];
            });
    }
    
    /**
     * Obtenir les produits disponibles selon le type d'entrepôt
     */
    private function getAvailableProducts($warehouseType)
    {
        $products = [
            'denrees' => ['Riz', 'Lait en poudre', 'Huile', 'Sucre', 'Farine', 'Haricots', 'Maïs'],
            'materiel' => ['Couvertures', 'Tentes', 'Kits hygiène', 'Moustiquaires', 'Seaux', 'Matelas'],
            'carburant' => ['Essence', 'Gasoil', 'Huile moteur']
        ];
        
        // Retourner tous les produits organisés par catégorie si le type n'est pas spécifique
        if (!isset($products[$warehouseType])) {
            return $products; // Retourner le tableau complet avec les clés
        }
        
        // Retourner seulement la catégorie spécifique dans le format attendu
        return [
            $warehouseType => $products[$warehouseType]
        ];
    }
    
    /**
     * Obtenir les produits avec leur stock actuel
     */
    private function getAvailableProductsWithStock($warehouseId)
    {
        $stocks = $this->getCurrentStocks($warehouseId);
        
        return $stocks->map(function ($stock) {
            return [
                'name' => $stock['name'],
                'category' => $stock['category'],
                'current_stock' => $stock['quantity'],
                'unit' => $stock['unit']
            ];
        })->filter(function ($stock) {
            return $stock['current_stock'] > 0; // Seulement les produits en stock
        });
    }
    
    /**
     * Obtenir le seuil minimum pour un produit
     */
    private function getMinThreshold($productName)
    {
        $thresholds = [
            'Riz' => 500,
            'Lait en poudre' => 200,
            'Huile' => 100,
            'Essence' => 100,
            'Gasoil' => 200,
        ];
        
        return $thresholds[$productName] ?? 50;
    }
    
    /**
     * Obtenir le statut du stock
     */
    private function getStockStatus($quantity, $minThreshold)
    {
        if ($quantity == 0) {
            return 'danger';
        } elseif ($quantity <= $minThreshold) {
            return 'warning';
        } else {
            return 'success';
        }
    }
}

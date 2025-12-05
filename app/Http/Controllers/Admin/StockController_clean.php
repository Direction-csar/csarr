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
            // Récupérer les données avec gestion d'erreur robuste
            $mouvementsPaginated = $this->getStockMovements($request);
            $stats = $this->getStockStats();
            $chartData = $this->getChartData();
            
            // Passer les variables de filtrage à la vue
            $search = $request->get('search', '');
            $type = $request->get('type', '');
            $mouvement = $request->get('mouvement', '');
            $entrepot = $request->get('entrepot', '');
            $date_debut = $request->get('date_debut', '');
            $date_fin = $request->get('date_fin', '');
            
            return view('admin.stock.index', compact(
                'mouvementsPaginated', 
                'stats', 
                'chartData',
                'search', 
                'type', 
                'mouvement', 
                'entrepot', 
                'date_debut', 
                'date_fin'
            ));
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage des mouvements de stock: ' . $e->getMessage());
            return view('admin.stock.index', [
                'mouvementsPaginated' => new LengthAwarePaginator([], 0, 15),
                'stats' => $this->getDefaultStats(),
                'chartData' => $this->getDefaultChartData(),
                'search' => '',
                'type' => '',
                'mouvement' => '',
                'entrepot' => '',
                'date_debut' => '',
                'date_fin' => ''
            ])->with('error', 'Erreur lors du chargement des données.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $entrepots = Warehouse::where('is_active', true)->get();
            $stockTypes = StockType::all();
            $stocks = Stock::with('warehouse')->where('is_active', true)->get();
            
            return view('admin.stock.create', compact('entrepots', 'stockTypes', 'stocks'));
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
            'stock_id' => 'required|exists:stocks,id',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            // Générer la référence automatique
            $reference = $request->reference ?: $this->generateReference($request->type);
            
            // Récupérer le stock
            $stock = Stock::findOrFail($request->stock_id);
            $quantityBefore = $stock->quantity;
            
            // Calculer la nouvelle quantité
            $quantityChange = $request->type === 'in' ? $request->quantity : -$request->quantity;
            $quantityAfter = $quantityBefore + $quantityChange;
            
            // Vérifier que la quantité après n'est pas négative
            if ($quantityAfter < 0) {
                throw new \Exception('Quantité insuffisante en stock. Stock actuel: ' . $quantityBefore);
            }
            
            // Créer le mouvement de stock
            $mouvement = StockMovement::create([
                'warehouse_id' => $request->warehouse_id,
                'stock_id' => $request->stock_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'reason' => $request->reason,
                'reference' => $reference,
                'reference_number' => $this->getReferenceNumber($reference),
                'created_by' => auth()->id()
            ]);

            // Mettre à jour le stock
            $stock->update(['quantity' => $quantityAfter]);

            // Créer une notification
            Notification::create([
                'user_id' => auth()->id(),
                'title' => 'Nouveau mouvement de stock',
                'message' => "Mouvement {$reference} créé avec succès",
                'type' => 'success'
            ]);

            DB::commit();

            return redirect()->route('admin.stock.index')
                ->with('success', 'Mouvement de stock créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du mouvement: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du mouvement: ' . $e->getMessage());
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

    // Méthodes privées

    private function getStockMovements($request, $paginate = true)
    {
        $query = StockMovement::with(['warehouse', 'creator']);

        // Filtre de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%");
            });
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par entrepôt
        if ($request->filled('entrepot')) {
            $query->where('warehouse_id', $request->entrepot);
        }

        // Filtre par date
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $query->orderBy('created_at', 'desc');

        if ($paginate) {
            return $query->paginate(15)->withQueryString();
        } else {
            return $query->get();
        }
    }

    private function getStockStats()
    {
        try {
            $stats = [
                'total_movements' => StockMovement::count(),
                'entrees' => StockMovement::where('type', 'in')->count(),
                'sorties' => StockMovement::where('type', 'out')->count(),
                'transferts' => StockMovement::where('type', 'transfert')->count(),
                'ajustements' => StockMovement::where('type', 'ajustement')->count(),
                'valeur_totale' => StockMovement::sum('quantity'),
                'total' => StockMovement::count()
            ];
            return $stats;
        } catch (\Exception $e) {
            Log::error('Erreur lors du calcul des statistiques: ' . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    private function getChartData()
    {
        try {
            $types = StockMovement::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray();

            $entrepots = StockMovement::with('warehouse')
                ->selectRaw('warehouse_id, COUNT(*) as count')
                ->groupBy('warehouse_id')
                ->get()
                ->map(function($item) {
                    return [
                        'name' => $item->warehouse->name ?? 'Inconnu',
                        'count' => $item->count
                    ];
                });

            return [
                'types' => $types,
                'entrepots' => $entrepots,
                'produits' => collect([])
            ];
        } catch (\Exception $e) {
            Log::error('Erreur lors du calcul des données de graphique: ' . $e->getMessage());
            return $this->getDefaultChartData();
        }
    }

    private function generateReference($type)
    {
        $prefix = $type === 'in' ? 'STK-IN' : 'STK-OUT';
        $year = date('Y');
        $number = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        return "{$prefix}-{$year}-{$number}";
    }

    private function getReferenceNumber($reference)
    {
        return preg_replace('/[^0-9]/', '', $reference);
    }

    private function generateReceiptPdf($mouvement)
    {
        try {
            // Générer le contenu HTML du reçu
            $html = $this->generateReceiptHtml($mouvement);
            
            // Créer le PDF avec DomPDF ou fallback vers HTML
            if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
                $pdf->setPaper('A4', 'portrait');
                
                return $pdf->download('receipt_' . $mouvement->reference . '.pdf');
            } else {
                // Fallback vers HTML si DomPDF n'est pas disponible
                return response($html)
                    ->header('Content-Type', 'text/html')
                    ->header('Content-Disposition', 'attachment; filename="receipt_' . $mouvement->reference . '.html"');
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du PDF: ' . $e->getMessage());
            
            // Fallback vers texte simple
            $content = $this->generateSimpleReceipt($mouvement);
            return response($content)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', 'attachment; filename="receipt_' . $mouvement->reference . '.txt"');
        }
    }

    private function generateReceiptHtml($mouvement)
    {
        // Utiliser le logo CSAR disponible
        $logoPath = public_path('images/logos/LOGO CSAR vectoriel-01.png');
        $logoBase64 = '';
        
        if (file_exists($logoPath)) {
            $logoContent = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoContent);
        }
        
        $typeLabels = [
            'in' => 'ENTRÉE',
            'out' => 'SORTIE',
            'entree' => 'ENTRÉE',
            'sortie' => 'SORTIE',
            'transfert' => 'TRANSFERT',
            'ajustement' => 'AJUSTEMENT'
        ];
        
        $typeLabel = $typeLabels[$mouvement->type] ?? strtoupper($mouvement->type);
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Reçu de Mouvement de Stock - ' . $mouvement->reference . '</title>
            <style>
                @page {
                    margin: 20mm;
                    size: A4;
                }
                body {
                    font-family: "Segoe UI", Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background: white;
                    color: #333;
                    line-height: 1.4;
                }
                .receipt-container {
                    width: 100%;
                    max-width: 210mm;
                    margin: 0 auto;
                    background: white;
                    position: relative;
                    min-height: 297mm;
                }
                .watermark {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%) rotate(-45deg);
                    opacity: 0.05;
                    z-index: 1;
                    pointer-events: none;
                }
                .watermark img {
                    width: 400px;
                    height: auto;
                }
                .content-wrapper {
                    position: relative;
                    z-index: 2;
                    padding: 20px;
                }
                .header {
                    text-align: center;
                    border-bottom: 3px solid #1e3a8a;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .logo {
                    max-width: 120px;
                    height: auto;
                    margin-bottom: 15px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                    font-weight: bold;
                    color: #1e3a8a;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }
                .header .subtitle {
                    margin: 5px 0 0 0;
                    font-size: 16px;
                    color: #666;
                    font-weight: 500;
                }
                .receipt-title {
                    text-align: center;
                    font-size: 20px;
                    font-weight: bold;
                    color: #1e3a8a;
                    margin: 20px 0;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }
                .receipt-info {
                    background: #f8f9fa;
                    border: 2px solid #e9ecef;
                    border-radius: 8px;
                    padding: 25px;
                    margin: 20px 0;
                }
                .info-row {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 12px;
                    padding: 8px 0;
                    border-bottom: 1px solid #e9ecef;
                }
                .info-row:last-child {
                    border-bottom: none;
                    margin-bottom: 0;
                }
                .info-label {
                    font-weight: bold;
                    color: #495057;
                    min-width: 150px;
                }
                .info-value {
                    color: #212529;
                    text-align: right;
                    flex: 1;
                }
                .signatures {
                    margin-top: 40px;
                    display: flex;
                    justify-content: space-between;
                }
                .signature-box {
                    text-align: center;
                    width: 200px;
                }
                .signature-line {
                    border-bottom: 2px solid #333;
                    height: 50px;
                    margin-bottom: 10px;
                }
                .signature-label {
                    font-weight: bold;
                    color: #495057;
                    font-size: 14px;
                }
                .footer {
                    margin-top: 50px;
                    text-align: center;
                    border-top: 2px solid #e9ecef;
                    padding-top: 20px;
                    color: #6c757d;
                    font-size: 12px;
                }
                .footer-title {
                    font-weight: bold;
                    color: #495057;
                    margin-bottom: 5px;
                }
                .generated-info {
                    margin-top: 20px;
                    text-align: center;
                    font-size: 11px;
                    color: #6c757d;
                    font-style: italic;
                }
                @media print {
                    body { margin: 0; }
                    .receipt-container { box-shadow: none; }
                }
            </style>
        </head>
        <body>
            <div class="receipt-container">
                <div class="watermark">
                    <img src="' . $logoBase64 . '" alt="CSAR Logo">
                </div>
                <div class="content-wrapper">
                    <div class="header">
                        <img src="' . $logoBase64 . '" alt="CSAR Logo" class="logo">
                        <h1>CSAR</h1>
                        <p class="subtitle">Commissariat à la Sécurité Alimentaire<br>et à la Résilience</p>
                    </div>
                    
                    <div class="receipt-title">REÇU DE MOUVEMENT DE STOCK</div>
                    
                    <div class="receipt-info">
                        <div class="info-row">
                            <span class="info-label">Référence:</span>
                            <span class="info-value">' . $mouvement->reference . '</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Type de Mouvement:</span>
                            <span class="info-value">' . $typeLabel . '</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Quantité:</span>
                            <span class="info-value">' . number_format($mouvement->quantity, 2) . ' unités</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Entrepôt:</span>
                            <span class="info-value">' . ($mouvement->warehouse->name ?? 'N/A') . '</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Localisation:</span>
                            <span class="info-value">' . ($mouvement->warehouse->location ?? 'N/A') . '</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Motif:</span>
                            <span class="info-value">' . $mouvement->reason . '</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date de Création:</span>
                            <span class="info-value">' . $mouvement->created_at->format('d/m/Y à H:i') . '</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Créé par:</span>
                            <span class="info-value">' . ($mouvement->creator->name ?? 'Administrateur CSAR') . '</span>
                        </div>
                    </div>
                    
                    <div class="signatures">
                        <div class="signature-box">
                            <div class="signature-line"></div>
                            <div class="signature-label">Signature Responsable</div>
                        </div>
                        <div class="signature-box">
                            <div class="signature-line"></div>
                            <div class="signature-label">Signature Agent</div>
                        </div>
                    </div>
                    
                    <div class="footer">
                        <div class="footer-title">Centre de Secours et d\'Assistance Rapide (CSAR)</div>
                        <div>Plateforme de Gestion des Stocks</div>
                        <div class="generated-info">
                            Reçu généré le ' . now()->format('d/m/Y à H:i') . ' pour le téléchargement
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    private function generateSimpleReceipt($mouvement)
    {
        $typeLabels = [
            'in' => 'ENTRÉE',
            'out' => 'SORTIE',
            'entree' => 'ENTRÉE',
            'sortie' => 'SORTIE',
            'transfert' => 'TRANSFERT',
            'ajustement' => 'AJUSTEMENT'
        ];
        
        $typeLabel = $typeLabels[$mouvement->type] ?? strtoupper($mouvement->type);
        
        return "
CSAR - Commissariat à la Sécurité Alimentaire et à la Résilience
================================================================

REÇU DE MOUVEMENT DE STOCK

Référence: {$mouvement->reference}
Type de Mouvement: {$typeLabel}
Quantité: " . number_format($mouvement->quantity, 2) . " unités
Entrepôt: " . ($mouvement->warehouse->name ?? 'N/A') . "
Localisation: " . ($mouvement->warehouse->location ?? 'N/A') . "
Motif: {$mouvement->reason}
Date de Création: " . $mouvement->created_at->format('d/m/Y à H:i') . "
Créé par: " . ($mouvement->creator->name ?? 'Administrateur CSAR') . "

Signature Responsable: _________________
Signature Agent: _________________

Commissariat à la Sécurité Alimentaire et à la Résilience (CSAR)
Plateforme de Gestion des Stocks
Reçu généré le " . now()->format('d/m/Y à H:i') . " pour le téléchargement
        ";
    }

    /**
     * Obtenir les statistiques par défaut
     */
    private function getDefaultStats()
    {
        return [
            'total_movements' => 0,
            'entrees' => 0,
            'sorties' => 0,
            'transferts' => 0,
            'ajustements' => 0,
            'valeur_totale' => 0,
            'total' => 0
        ];
    }

    /**
     * Obtenir les données de graphique par défaut
     */
    private function getDefaultChartData()
    {
        return [
            'types' => [
                'entree' => 0,
                'sortie' => 0,
                'transfert' => 0,
                'ajustement' => 0
            ],
            'entrepots' => collect([]),
            'produits' => collect([])
        ];
    }
}

<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

echo "=== TEST DE LA CORRECTION MOUVEMENT DE STOCK ===\n\n";

try {
    $stock = Stock::first();
    $warehouse = Warehouse::first();
    
    if (!$stock || !$warehouse) {
        throw new Exception("Stock ou warehouse manquant");
    }
    
    echo "✅ Stock sélectionné: {$stock->item_name} (ID: {$stock->id})\n";
    echo "✅ Warehouse sélectionné: {$warehouse->name} (ID: {$warehouse->id})\n";
    echo "✅ Quantité actuelle: {$stock->quantity}\n\n";
    
    // Test de création d'un mouvement
    echo "🔧 Création d'un mouvement de stock...\n";
    
    $quantityBefore = $stock->quantity;
    $quantityToAdd = 15;
    $quantityAfter = $quantityBefore + $quantityToAdd;
    
    $mouvement = StockMovement::create([
        'stock_id' => $stock->id,
        'warehouse_id' => $warehouse->id,
        'type' => 'in',
        'quantity' => $quantityToAdd,
        'quantity_before' => $quantityBefore,
        'quantity_after' => $quantityAfter,
        'reason' => 'Test après correction',
        'reference' => 'TEST-CORR-' . time(),
        'created_by' => 1
    ]);
    
    echo "✅ Mouvement créé avec succès: ID {$mouvement->id}\n";
    echo "  - Type: {$mouvement->type}\n";
    echo "  - Quantité: {$mouvement->quantity}\n";
    echo "  - Avant: {$mouvement->quantity_before}\n";
    echo "  - Après: {$mouvement->quantity_after}\n";
    echo "  - Référence: {$mouvement->reference}\n\n";
    
    // Mettre à jour le stock
    $stock->update(['quantity' => $quantityAfter]);
    echo "✅ Stock mis à jour: {$stock->item_name} = {$stock->quantity}\n\n";
    
    // Vérifier les mouvements
    $totalMovements = StockMovement::count();
    echo "✅ Total mouvements dans la base: $totalMovements\n";
    
    echo "\n🎉 LA CRÉATION DE MOUVEMENTS DE STOCK FONCTIONNE MAINTENANT !\n";
    echo "✅ Plus d'erreur 'reference_number'\n";
    echo "✅ Plus d'erreur 'stock_id'\n";
    echo "✅ Le système est prêt à l'emploi\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}


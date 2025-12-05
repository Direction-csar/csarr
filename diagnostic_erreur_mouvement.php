<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

echo "=== DIAGNOSTIC DE L'ERREUR MOUVEMENT DE STOCK ===\n\n";

// 1. Vérifier la structure de la table stock_movements
echo "1. 🔍 STRUCTURE DE LA TABLE STOCK_MOVEMENTS\n";
echo "===========================================\n";
try {
    $columns = DB::select('DESCRIBE stock_movements');
    echo "Colonnes disponibles dans stock_movements:\n";
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type}) - Null: {$column->Null}, Default: {$column->Default}\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
echo "\n";

// 2. Vérifier les données disponibles
echo "2. 🔍 DONNÉES DISPONIBLES\n";
echo "=========================\n";
try {
    $stocks = Stock::all();
    $warehouses = Warehouse::all();
    
    echo "Stocks disponibles: " . $stocks->count() . "\n";
    foreach ($stocks as $stock) {
        echo "  - ID: {$stock->id}, Nom: {$stock->item_name}, Qty: {$stock->quantity}, Warehouse: {$stock->warehouse_id}\n";
    }
    
    echo "\nEntrepôts disponibles: " . $warehouses->count() . "\n";
    foreach ($warehouses as $warehouse) {
        echo "  - ID: {$warehouse->id}, Nom: {$warehouse->name}\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
echo "\n";

// 3. Test de création d'un mouvement avec TOUS les champs possibles
echo "3. 🔧 TEST DE CRÉATION DE MOUVEMENT\n";
echo "===================================\n";

try {
    $stock = Stock::first();
    $warehouse = Warehouse::first();
    
    if (!$stock || !$warehouse) {
        throw new Exception("Stock ou warehouse manquant");
    }
    
    echo "Stock sélectionné: {$stock->item_name} (ID: {$stock->id})\n";
    echo "Warehouse sélectionné: {$warehouse->name} (ID: {$warehouse->id})\n";
    echo "Quantité actuelle du stock: {$stock->quantity}\n";
    
    // Test 1: Création MINIMALE
    echo "\nTest 1: Création minimale avec seulement les champs requis...\n";
    try {
        $mouvement1 = StockMovement::create([
            'stock_id' => $stock->id,
            'warehouse_id' => $warehouse->id,
            'type' => 'in',
            'quantity' => 10,
            'quantity_before' => $stock->quantity,
            'quantity_after' => $stock->quantity + 10,
            'reason' => 'Test minimal',
            'reference' => 'TEST-MIN-' . time(),
            'created_by' => 1
        ]);
        echo "✅ Test 1 RÉUSSI - ID: {$mouvement1->id}\n";
    } catch (Exception $e) {
        echo "❌ Test 1 ÉCHOUÉ: " . $e->getMessage() . "\n";
        echo "Détails: " . $e->getTraceAsString() . "\n\n";
    }
    
    // Test 2: Vérifier si 'reference_number' est requis
    echo "\nTest 2: Création avec reference_number...\n";
    try {
        $mouvement2 = StockMovement::create([
            'stock_id' => $stock->id,
            'warehouse_id' => $warehouse->id,
            'type' => 'in',
            'quantity' => 5,
            'quantity_before' => $stock->quantity,
            'quantity_after' => $stock->quantity + 5,
            'reason' => 'Test avec reference_number',
            'reference' => 'TEST-REF-' . time(),
            'reference_number' => 'REF-' . time(),
            'created_by' => 1
        ]);
        echo "✅ Test 2 RÉUSSI - ID: {$mouvement2->id}\n";
    } catch (Exception $e) {
        echo "❌ Test 2 ÉCHOUÉ: " . $e->getMessage() . "\n";
    }
    
    // Test 3: Sans stock_id (pour voir si c'est la cause)
    echo "\nTest 3: Création sans stock_id (ancien format)...\n";
    try {
        $mouvement3 = StockMovement::create([
            'warehouse_id' => $warehouse->id,
            'type' => 'in',
            'quantity' => 3,
            'quantity_before' => 0,
            'quantity_after' => 3,
            'reason' => 'Test sans stock_id',
            'reference' => 'TEST-NOSTK-' . time(),
            'created_by' => 1
        ]);
        echo "✅ Test 3 RÉUSSI - ID: {$mouvement3->id}\n";
    } catch (Exception $e) {
        echo "❌ Test 3 ÉCHOUÉ: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
}
echo "\n";

// 4. Vérifier le modèle StockMovement
echo "4. 🔍 CONFIGURATION DU MODÈLE STOCKMOVEMENT\n";
echo "===========================================\n";
try {
    $model = new StockMovement();
    $fillable = $model->getFillable();
    echo "Champs fillable dans StockMovement:\n";
    foreach ($fillable as $field) {
        echo "  - $field\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
echo "\n";

// 5. Lire les dernières lignes du log Laravel
echo "5. 📋 DERNIÈRES ERREURS DANS LES LOGS\n";
echo "=====================================\n";
try {
    $logFile = 'storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $lines = file($logFile);
        $lastLines = array_slice($lines, -50); // 50 dernières lignes
        
        // Chercher les erreurs liées aux mouvements de stock
        echo "Dernières erreurs liées aux mouvements de stock:\n";
        $found = false;
        foreach ($lastLines as $line) {
            if (stripos($line, 'mouvement') !== false || stripos($line, 'stock') !== false) {
                echo $line;
                $found = true;
            }
        }
        
        if (!$found) {
            echo "Aucune erreur récente trouvée dans les logs.\n";
        }
    } else {
        echo "Fichier de log non trouvé.\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur lecture log: " . $e->getMessage() . "\n";
}
echo "\n";

echo "=== FIN DU DIAGNOSTIC ===\n";
echo "Veuillez partager les erreurs spécifiques affichées ci-dessus.\n";


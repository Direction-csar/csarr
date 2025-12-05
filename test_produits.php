<?php
/**
 * Script de test pour vérifier la configuration des produits
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\StockType;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "=============================================\n";
echo "   TEST DE CONFIGURATION DES PRODUITS\n";
echo "=============================================\n\n";

$errors = [];
$warnings = [];
$success = [];

// Test 1 : Connexion à la base de données
echo "🔍 Test 1 : Connexion à la base de données... ";
try {
    DB::connection()->getPdo();
    echo "✅ OK\n";
    $success[] = "Connexion à la base de données réussie";
} catch (\Exception $e) {
    echo "❌ ERREUR\n";
    $errors[] = "Impossible de se connecter à la base de données : " . $e->getMessage();
}

// Test 2 : Vérification de la table warehouses
echo "🔍 Test 2 : Table 'warehouses'... ";
try {
    $warehouseCount = Warehouse::count();
    $activeWarehouses = Warehouse::where('is_active', true)->count();
    
    if ($warehouseCount === 0) {
        echo "⚠️  ATTENTION\n";
        $warnings[] = "Aucun entrepôt trouvé dans la base de données";
    } elseif ($activeWarehouses === 0) {
        echo "⚠️  ATTENTION\n";
        $warnings[] = "Aucun entrepôt actif trouvé";
    } else {
        echo "✅ OK ($activeWarehouses actif(s))\n";
        $success[] = "$activeWarehouses entrepôt(s) actif(s) disponible(s)";
    }
} catch (\Exception $e) {
    echo "❌ ERREUR\n";
    $errors[] = "Erreur lors de la vérification des entrepôts : " . $e->getMessage();
}

// Test 3 : Vérification de la table stock_types
echo "🔍 Test 3 : Table 'stock_types'... ";
try {
    $stockTypeCount = StockType::count();
    
    if ($stockTypeCount === 0) {
        echo "⚠️  ATTENTION\n";
        $warnings[] = "Aucun type de stock trouvé dans la base de données";
    } else {
        echo "✅ OK ($stockTypeCount type(s))\n";
        $success[] = "$stockTypeCount type(s) de stock disponible(s)";
        
        // Afficher les types
        echo "   Types disponibles : ";
        $types = StockType::pluck('name')->toArray();
        echo implode(", ", $types) . "\n";
    }
} catch (\Exception $e) {
    echo "❌ ERREUR\n";
    $errors[] = "Erreur lors de la vérification des types de stock : " . $e->getMessage();
}

// Test 4 : Vérification de la table stocks
echo "🔍 Test 4 : Table 'stocks' (produits)... ";
try {
    $stockCount = Stock::count();
    $activeStocks = Stock::where('is_active', true)->count();
    
    if ($stockCount === 0) {
        echo "⚠️  VIDE\n";
        $warnings[] = "Aucun produit trouvé dans la base de données - C'EST NORMAL si vous n'avez pas encore ajouté de produits";
    } else {
        echo "✅ OK ($activeStocks actif(s))\n";
        $success[] = "$activeStocks produit(s) disponible(s) pour les mouvements de stock";
    }
} catch (\Exception $e) {
    echo "❌ ERREUR\n";
    $errors[] = "Erreur lors de la vérification des produits : " . $e->getMessage();
}

// Test 5 : Vérification des relations
if ($activeStocks > 0) {
    echo "🔍 Test 5 : Intégrité des données... ";
    try {
        $stocksWithoutWarehouse = Stock::whereNull('warehouse_id')->where('is_active', true)->count();
        $stocksWithoutType = Stock::whereNull('stock_type_id')->where('is_active', true)->count();
        
        if ($stocksWithoutWarehouse > 0 || $stocksWithoutType > 0) {
            echo "⚠️  ATTENTION\n";
            if ($stocksWithoutWarehouse > 0) {
                $warnings[] = "$stocksWithoutWarehouse produit(s) sans entrepôt assigné";
            }
            if ($stocksWithoutType > 0) {
                $warnings[] = "$stocksWithoutType produit(s) sans type de stock";
            }
        } else {
            echo "✅ OK\n";
            $success[] = "Toutes les relations sont correctes";
        }
    } catch (\Exception $e) {
        echo "❌ ERREUR\n";
        $errors[] = "Erreur lors de la vérification de l'intégrité : " . $e->getMessage();
    }
}

// Test 6 : Répartition par type de stock
if ($activeStocks > 0) {
    echo "\n📊 Répartition des produits par type :\n";
    try {
        $repartition = DB::table('stocks')
            ->join('stock_types', 'stocks.stock_type_id', '=', 'stock_types.id')
            ->where('stocks.is_active', true)
            ->select('stock_types.name', DB::raw('COUNT(*) as count'))
            ->groupBy('stock_types.name')
            ->get();
        
        foreach ($repartition as $rep) {
            echo "   - {$rep->name} : {$rep->count} produit(s)\n";
        }
    } catch (\Exception $e) {
        echo "   ❌ Erreur : " . $e->getMessage() . "\n";
    }
}

// Test 7 : Exemples de produits
if ($activeStocks > 0) {
    echo "\n📦 Exemples de produits (5 premiers) :\n";
    try {
        $exemples = Stock::with(['warehouse', 'stockType'])
            ->where('is_active', true)
            ->limit(5)
            ->get();
        
        foreach ($exemples as $stock) {
            $warehouse = $stock->warehouse ? $stock->warehouse->name : 'N/A';
            $type = $stock->stockType ? $stock->stockType->name : 'N/A';
            echo "   - {$stock->item_name}\n";
            echo "     Entrepôt: {$warehouse} | Type: {$type} | Quantité: {$stock->quantity} | Prix: " . number_format($stock->unit_price, 0, ',', ' ') . " FCFA\n";
        }
    } catch (\Exception $e) {
        echo "   ❌ Erreur : " . $e->getMessage() . "\n";
    }
}

// Résumé
echo "\n=============================================\n";
echo "                 RÉSUMÉ\n";
echo "=============================================\n\n";

if (!empty($errors)) {
    echo "❌ ERREURS CRITIQUES (" . count($errors) . ") :\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠️  AVERTISSEMENTS (" . count($warnings) . ") :\n";
    foreach ($warnings as $warning) {
        echo "   - $warning\n";
    }
    echo "\n";
}

if (!empty($success)) {
    echo "✅ SUCCÈS (" . count($success) . ") :\n";
    foreach ($success as $succ) {
        echo "   - $succ\n";
    }
    echo "\n";
}

// Recommandations
echo "=============================================\n";
echo "              RECOMMANDATIONS\n";
echo "=============================================\n\n";

if (empty($errors)) {
    if ($activeStocks === 0) {
        echo "📝 PROCHAINES ÉTAPES :\n\n";
        echo "Vous devez ajouter des produits avant de créer des mouvements de stock.\n";
        echo "3 méthodes disponibles :\n\n";
        echo "1. Interface Web (RECOMMANDÉ) :\n";
        echo "   → http://localhost/csar/gestion_produits.php\n\n";
        echo "2. Script SQL (Ajout en masse) :\n";
        echo "   → Exécutez 'ajouter_produits.sql' dans phpMyAdmin\n\n";
        echo "3. Script PHP (Ligne de commande) :\n";
        echo "   → php ajouter_produits_stock.php\n\n";
    } else {
        echo "✅ Votre système est prêt !\n\n";
        echo "Vous pouvez maintenant créer des mouvements de stock :\n";
        echo "→ Admin > Gestion des Stocks > Nouveau Mouvement\n\n";
        echo "Le dropdown 'Produit/Stock' affichera {$activeStocks} produit(s).\n\n";
    }
    
    echo "📚 Besoin d'aide ?\n";
    echo "   → Consultez GUIDE_AJOUT_PRODUITS.md\n";
    echo "   → Ou ouvrez index_gestion_produits.html dans votre navigateur\n\n";
} else {
    echo "❌ Des erreurs critiques ont été détectées.\n";
    echo "Veuillez corriger ces erreurs avant de continuer.\n\n";
    echo "Vérifiez :\n";
    echo "   - Que XAMPP (Apache + MySQL) est démarré\n";
    echo "   - Que la base de données existe\n";
    echo "   - Les paramètres de connexion dans .env\n\n";
}

echo "=============================================\n\n";
























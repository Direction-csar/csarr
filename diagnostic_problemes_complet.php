<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Demande;
use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Personnel;
use App\Models\NewsletterSubscriber;
use App\Models\Newsletter;
use App\Models\SimReport;
use App\Models\Notification;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

echo "=== DIAGNOSTIC COMPLET DES PROBLÈMES CSAR ===\n\n";

$errors = [];
$warnings = [];
$success = [];

// 1. DIAGNOSTIC DES MOUVEMENTS DE STOCK
echo "1. 🔍 DIAGNOSTIC DES MOUVEMENTS DE STOCK\n";
echo "========================================\n";

try {
    // Vérifier la table stock_movements
    $stockMovementsCount = DB::table('stock_movements')->count();
    echo "✅ Stock movements dans la base: $stockMovementsCount\n";
    
    // Vérifier la structure de la table
    $columns = DB::select('DESCRIBE stock_movements');
    echo "✅ Colonnes stock_movements: " . implode(', ', array_column($columns, 'Field')) . "\n";
    
    // Tenter de créer un mouvement de stock
    echo "\n🔧 Test de création d'un mouvement de stock...\n";
    
    $stock = Stock::first();
    $warehouse = Warehouse::first();
    
    if ($stock && $warehouse) {
        $testMovement = StockMovement::create([
            'stock_id' => $stock->id,
            'warehouse_id' => $warehouse->id,
            'type' => 'in',
            'quantity' => 10,
            'quantity_before' => $stock->quantity,
            'quantity_after' => $stock->quantity + 10,
            'reason' => 'Test diagnostic',
            'reference' => 'TEST-' . time(),
            'created_by' => 1
        ]);
        echo "✅ Mouvement de stock créé avec succès: ID {$testMovement->id}\n";
        $success[] = "Création mouvement de stock réussie";
    } else {
        echo "❌ Impossible de créer un mouvement: stock ou warehouse manquant\n";
        $errors[] = "Stock ou warehouse manquant pour test mouvement";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur mouvement de stock: " . $e->getMessage() . "\n";
    $errors[] = "Erreur mouvement de stock: " . $e->getMessage();
}
echo "\n";

// 2. VÉRIFICATION DES CONTRÔLEURS
echo "2. 🔍 VÉRIFICATION DES CONTRÔLEURS\n";
echo "==================================\n";

$controllers = [
    'StockController' => 'app/Http/Controllers/Admin/StockController.php',
    'EntrepotsController' => 'app/Http/Controllers/Admin/EntrepotsController.php',
    'DashboardController' => 'app/Http/Controllers/Admin/DashboardController.php',
    'DemandesController' => 'app/Http/Controllers/Admin/DemandesController.php',
    'UserController' => 'app/Http/Controllers/Admin/UserController.php',
    'PersonnelController' => 'app/Http/Controllers/Admin/PersonnelController.php',
    'NewsletterController' => 'app/Http/Controllers/Admin/NewsletterController.php',
    'SimReportsController' => 'app/Http/Controllers/Admin/SimReportsController.php',
    'AuditController' => 'app/Http/Controllers/Admin/AuditController.php',
    'CommunicationController' => 'app/Http/Controllers/Admin/CommunicationController.php',
    'MessagesController' => 'app/Http/Controllers/Admin/MessagesController.php'
];

foreach ($controllers as $name => $path) {
    if (file_exists($path)) {
        echo "✅ $name: Fichier existe\n";
        $success[] = "Contrôleur $name existe";
    } else {
        echo "❌ $name: Fichier manquant\n";
        $errors[] = "Contrôleur $name manquant";
    }
}
echo "\n";

// 3. VÉRIFICATION DES VUES
echo "3. 🔍 VÉRIFICATION DES VUES\n";
echo "===========================\n";

$views = [
    'admin.dashboard.index' => 'resources/views/admin/dashboard/index.blade.php',
    'admin.stock.index' => 'resources/views/admin/stock/index.blade.php',
    'admin.entrepots.index' => 'resources/views/admin/entrepots/index.blade.php',
    'admin.demandes.index' => 'resources/views/admin/demandes/index.blade.php',
    'admin.users.index' => 'resources/views/admin/users/index.blade.php',
    'admin.personnel.index' => 'resources/views/admin/personnel/index.blade.php',
    'admin.newsletter.index' => 'resources/views/admin/newsletter/index.blade.php',
    'admin.sim-reports.index' => 'resources/views/admin/sim-reports/index.blade.php',
    'admin.audit.index' => 'resources/views/admin/audit/index.blade.php',
    'admin.communication.index' => 'resources/views/admin/communication/index.blade.php',
    'admin.messages.index' => 'resources/views/admin/messages/index.blade.php'
];

foreach ($views as $name => $path) {
    if (file_exists($path)) {
        echo "✅ $name: Vue existe\n";
        $success[] = "Vue $name existe";
    } else {
        echo "❌ $name: Vue manquante\n";
        $errors[] = "Vue $name manquante";
    }
}
echo "\n";

// 4. VÉRIFICATION DES MODÈLES ET RELATIONS
echo "4. 🔍 VÉRIFICATION DES MODÈLES ET RELATIONS\n";
echo "===========================================\n";

// Test des relations critiques
try {
    $warehouse = Warehouse::first();
    if ($warehouse) {
        $stocks = $warehouse->stocks;
        echo "✅ Relation Warehouse->Stock: " . $stocks->count() . " stocks\n";
        
        $stockMovements = $warehouse->stockMovements;
        echo "✅ Relation Warehouse->StockMovement: " . $stockMovements->count() . " mouvements\n";
    }
    
    $stock = Stock::first();
    if ($stock) {
        $movements = $stock->movements;
        echo "✅ Relation Stock->StockMovement: " . $movements->count() . " mouvements\n";
        
        $warehouse = $stock->warehouse;
        echo "✅ Relation Stock->Warehouse: " . ($warehouse ? $warehouse->name : 'Aucun') . "\n";
    }
    
    $success[] = "Relations modèles fonctionnelles";
} catch (Exception $e) {
    echo "❌ Erreur relations: " . $e->getMessage() . "\n";
    $errors[] = "Erreur relations: " . $e->getMessage();
}
echo "\n";

// 5. VÉRIFICATION DES ROUTES ET NAVIGATION
echo "5. 🔍 VÉRIFICATION DES ROUTES\n";
echo "=============================\n";

$routes = [
    'admin.dashboard',
    'admin.demandes.index',
    'admin.users.index',
    'admin.entrepots.index',
    'admin.stock.index',
    'admin.personnel.index',
    'admin.statistics',
    'admin.chiffres-cles.index',
    'admin.actualites.index',
    'admin.gallery.index',
    'admin.communication.index',
    'admin.messages.index',
    'admin.newsletter.index',
    'admin.sim-reports.index',
    'admin.audit.index'
];

foreach ($routes as $route) {
    try {
        $url = route($route);
        echo "✅ Route '$route': $url\n";
        $success[] = "Route $route accessible";
    } catch (Exception $e) {
        echo "❌ Route '$route': ERREUR - " . $e->getMessage() . "\n";
        $errors[] = "Route $route défaillante: " . $e->getMessage();
    }
}
echo "\n";

// 6. TEST DES FONCTIONNALITÉS CRITIQUES
echo "6. 🔍 TEST DES FONCTIONNALITÉS CRITIQUES\n";
echo "=======================================\n";

// Test création de données
try {
    // Test création stock
    $stockType = DB::table('stock_types')->first();
    if ($stockType) {
        $testStock = Stock::create([
            'item_name' => 'Test Diagnostic Stock',
            'description' => 'Stock de test pour diagnostic',
            'quantity' => 50,
            'unit_price' => 1000.00,
            'warehouse_id' => Warehouse::first()->id,
            'stock_type_id' => $stockType->id,
            'min_quantity' => 5,
            'max_quantity' => 200,
            'supplier' => 'Fournisseur Test',
            'is_active' => true
        ]);
        echo "✅ Création stock: {$testStock->item_name}\n";
        $success[] = "Création stock réussie";
    }
    
    // Test création demande
    $testDemande = Demande::create([
        'nom' => 'Test',
        'prenom' => 'Diagnostic',
        'email' => 'test.diagnostic@csar.com',
        'telephone' => '+221 77 000 00 00',
        'objet' => 'Test diagnostic demandes',
        'description' => 'Test complet diagnostic',
        'type_demande' => 'information',
        'statut' => 'en_attente',
        'consentement' => true,
        'tracking_code' => 'DEM' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)
    ]);
    echo "✅ Création demande: {$testDemande->tracking_code}\n";
    $success[] = "Création demande réussie";
    
} catch (Exception $e) {
    echo "❌ Erreur création données: " . $e->getMessage() . "\n";
    $errors[] = "Erreur création données: " . $e->getMessage();
}
echo "\n";

// RÉSUMÉ DU DIAGNOSTIC
echo "=== RÉSUMÉ DU DIAGNOSTIC ===\n";
echo "============================\n";
echo "✅ Succès: " . count($success) . "\n";
echo "⚠️  Avertissements: " . count($warnings) . "\n";
echo "❌ Erreurs: " . count($errors) . "\n\n";

if (count($errors) > 0) {
    echo "🚨 ERREURS CRITIQUES À CORRIGER:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
    echo "\n";
}

if (count($warnings) > 0) {
    echo "⚠️  AVERTISSEMENTS:\n";
    foreach ($warnings as $warning) {
        echo "  - $warning\n";
    }
    echo "\n";
}

if (count($errors) == 0) {
    echo "🎉 SYSTÈME EN BON ÉTAT !\n";
} else {
    echo "🔧 CORRECTIONS NÉCESSAIRES AVANT HÉBERGEMENT\n";
}

echo "\n=== PROCHAINES ÉTAPES ===\n";
if (count($errors) > 0) {
    echo "1. Corriger les erreurs identifiées\n";
    echo "2. Retester les fonctionnalités\n";
    echo "3. Vérifier l'intégration API Orange\n";
} else {
    echo "1. Système prêt pour l'hébergement\n";
    echo "2. Intégrer l'API Orange\n";
    echo "3. Déployer en production\n";
}


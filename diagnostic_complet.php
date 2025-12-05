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

echo "=== DIAGNOSTIC COMPLET DU SYSTÈME CSAR ===\n\n";

$errors = [];
$warnings = [];
$success = [];

// 1. VÉRIFICATION DES MODÈLES ET TABLES
echo "1. 🔍 VÉRIFICATION DES MODÈLES ET TABLES\n";
echo "========================================\n";

// Vérifier les tables existantes
$tables = [
    'users', 'demandes', 'warehouses', 'stocks', 'stock_movements', 
    'personnel', 'newsletter_subscribers', 'newsletters', 'sim_reports',
    'notifications', 'messages', 'audit_logs', 'stock_types'
];

foreach ($tables as $table) {
    try {
        $count = DB::table($table)->count();
        echo "✅ Table '$table': $count enregistrements\n";
        $success[] = "Table $table accessible";
    } catch (Exception $e) {
        echo "❌ Table '$table': ERREUR - " . $e->getMessage() . "\n";
        $errors[] = "Table $table inaccessible: " . $e->getMessage();
    }
}
echo "\n";

// 2. VÉRIFICATION DES MODÈLES
echo "2. 🔍 VÉRIFICATION DES MODÈLES\n";
echo "=============================\n";

$models = [
    'User' => User::class,
    'Demande' => Demande::class,
    'Warehouse' => Warehouse::class,
    'Stock' => Stock::class,
    'StockMovement' => StockMovement::class,
    'Personnel' => Personnel::class,
    'NewsletterSubscriber' => NewsletterSubscriber::class,
    'Newsletter' => Newsletter::class,
    'SimReport' => SimReport::class,
    'Notification' => Notification::class,
    'Message' => Message::class
];

foreach ($models as $name => $model) {
    try {
        $count = $model::count();
        echo "✅ Modèle '$name': $count enregistrements\n";
        $success[] = "Modèle $name fonctionnel";
    } catch (Exception $e) {
        echo "❌ Modèle '$name': ERREUR - " . $e->getMessage() . "\n";
        $errors[] = "Modèle $name défaillant: " . $e->getMessage();
    }
}
echo "\n";

// 3. VÉRIFICATION DES COLONNES CRITIQUES
echo "3. 🔍 VÉRIFICATION DES COLONNES CRITIQUES\n";
echo "=========================================\n";

// Vérifier les colonnes des demandes
try {
    $demandeColumns = DB::select('DESCRIBE demandes');
    $demandeColNames = array_column($demandeColumns, 'Field');
    echo "✅ Colonnes demandes: " . implode(', ', $demandeColNames) . "\n";
    
    if (!in_array('statut', $demandeColNames)) {
        echo "⚠️  Colonne 'statut' manquante dans demandes\n";
        $warnings[] = "Colonne 'statut' manquante dans demandes";
    }
} catch (Exception $e) {
    echo "❌ Erreur vérification demandes: " . $e->getMessage() . "\n";
    $errors[] = "Erreur vérification demandes: " . $e->getMessage();
}

// Vérifier les colonnes des stocks
try {
    $stockColumns = DB::select('DESCRIBE stocks');
    $stockColNames = array_column($stockColumns, 'Field');
    echo "✅ Colonnes stocks: " . implode(', ', $stockColNames) . "\n";
} catch (Exception $e) {
    echo "❌ Erreur vérification stocks: " . $e->getMessage() . "\n";
    $errors[] = "Erreur vérification stocks: " . $e->getMessage();
}

// Vérifier les colonnes des entrepôts
try {
    $warehouseColumns = DB::select('DESCRIBE warehouses');
    $warehouseColNames = array_column($warehouseColumns, 'Field');
    echo "✅ Colonnes warehouses: " . implode(', ', $warehouseColNames) . "\n";
} catch (Exception $e) {
    echo "❌ Erreur vérification warehouses: " . $e->getMessage() . "\n";
    $errors[] = "Erreur vérification warehouses: " . $e->getMessage();
}
echo "\n";

// 4. VÉRIFICATION DES RELATIONS
echo "4. 🔍 VÉRIFICATION DES RELATIONS\n";
echo "===============================\n";

// Test relation Warehouse -> Stock
try {
    $warehouse = Warehouse::first();
    if ($warehouse) {
        $stocks = $warehouse->stocks;
        echo "✅ Relation Warehouse->Stock: " . $stocks->count() . " stocks\n";
    } else {
        echo "⚠️  Aucun entrepôt pour tester la relation\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur relation Warehouse->Stock: " . $e->getMessage() . "\n";
    $errors[] = "Erreur relation Warehouse->Stock: " . $e->getMessage();
}

// Test relation Stock -> StockMovement
try {
    $stock = Stock::first();
    if ($stock) {
        $movements = $stock->movements;
        echo "✅ Relation Stock->StockMovement: " . $movements->count() . " mouvements\n";
    } else {
        echo "⚠️  Aucun stock pour tester la relation\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur relation Stock->StockMovement: " . $e->getMessage() . "\n";
    $errors[] = "Erreur relation Stock->StockMovement: " . $e->getMessage();
}
echo "\n";

// 5. VÉRIFICATION DES STATISTIQUES DASHBOARD
echo "5. 🔍 VÉRIFICATION DES STATISTIQUES DASHBOARD\n";
echo "============================================\n";

try {
    $stats = [
        'users' => User::count(),
        'demandes' => Demande::count(),
        'warehouses' => Warehouse::count(),
        'stocks' => Stock::count(),
        'stock_movements' => StockMovement::count(),
        'personnel' => Personnel::count(),
        'newsletter_subscribers' => NewsletterSubscriber::count(),
        'newsletters' => Newsletter::count(),
        'sim_reports' => SimReport::count(),
        'notifications' => Notification::count(),
        'messages' => Message::count()
    ];
    
    foreach ($stats as $key => $value) {
        echo "✅ $key: $value\n";
    }
    $success[] = "Statistiques dashboard calculées";
} catch (Exception $e) {
    echo "❌ Erreur calcul statistiques: " . $e->getMessage() . "\n";
    $errors[] = "Erreur calcul statistiques: " . $e->getMessage();
}
echo "\n";

// 6. VÉRIFICATION DES ROUTES
echo "6. 🔍 VÉRIFICATION DES ROUTES\n";
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


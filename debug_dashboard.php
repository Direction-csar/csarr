<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DIAGNOSTIC TABLEAU DE BORD ===\n\n";

try {
    // 1. Vérifier les colonnes des tables
    echo "1. 🔍 VÉRIFICATION COLONNES TABLES:\n";
    
    // Table notifications
    $notificationColumns = Illuminate\Support\Facades\Schema::getColumnListing('notifications');
    echo "Colonnes table 'notifications':\n";
    foreach ($notificationColumns as $col) {
        echo "  - $col\n";
    }
    
    // Table messages
    $messageColumns = Illuminate\Support\Facades\Schema::getColumnListing('messages');
    echo "\nColonnes table 'messages':\n";
    foreach ($messageColumns as $col) {
        echo "  - $col\n";
    }
    
    // Table users
    $userColumns = Illuminate\Support\Facades\Schema::getColumnListing('users');
    echo "\nColonnes table 'users':\n";
    foreach ($userColumns as $col) {
        echo "  - $col\n";
    }
    
    // 2. Test des requêtes actuelles
    echo "\n2. 📊 TEST REQUÊTES ACTUELLES:\n";
    
    // Test notifications
    try {
        $unreadNotifications = Illuminate\Support\Facades\DB::table('notifications')->where('read', false)->count();
        echo "✅ Notifications non lues (read=false): $unreadNotifications\n";
    } catch (Exception $e) {
        echo "❌ Erreur notifications: " . $e->getMessage() . "\n";
    }
    
    try {
        $unreadNotifications2 = Illuminate\Support\Facades\DB::table('notifications')->where('is_read', false)->count();
        echo "✅ Notifications non lues (is_read=false): $unreadNotifications2\n";
    } catch (Exception $e) {
        echo "❌ Erreur notifications is_read: " . $e->getMessage() . "\n";
    }
    
    // Test messages
    try {
        $unreadMessages = Illuminate\Support\Facades\DB::table('messages')->where('lu', false)->count();
        echo "✅ Messages non lus (lu=false): $unreadMessages\n";
    } catch (Exception $e) {
        echo "❌ Erreur messages: " . $e->getMessage() . "\n";
    }
    
    // Test users
    try {
        $totalUsers = Illuminate\Support\Facades\DB::table('users')->count();
        $activeUsers = Illuminate\Support\Facades\DB::table('users')->where('is_active', true)->count();
        echo "✅ Total utilisateurs: $totalUsers\n";
        echo "✅ Utilisateurs actifs: $activeUsers\n";
    } catch (Exception $e) {
        echo "❌ Erreur users: " . $e->getMessage() . "\n";
    }
    
    // Test demandes
    try {
        $totalDemandes = Illuminate\Support\Facades\DB::table('demandes')->count();
        echo "✅ Total demandes: $totalDemandes\n";
    } catch (Exception $e) {
        echo "❌ Erreur demandes: " . $e->getMessage() . "\n";
    }
    
    // Test warehouses
    try {
        $totalWarehouses = Illuminate\Support\Facades\DB::table('warehouses')->count();
        echo "✅ Total entrepôts: $totalWarehouses\n";
    } catch (Exception $e) {
        echo "❌ Erreur warehouses: " . $e->getMessage() . "\n";
    }
    
    // Test stock_movements
    try {
        $totalStockMovements = Illuminate\Support\Facades\DB::table('stock_movements')->count();
        echo "✅ Total mouvements de stock: $totalStockMovements\n";
    } catch (Exception $e) {
        echo "❌ Erreur stock_movements: " . $e->getMessage() . "\n";
    }
    
    // 3. Test du contrôleur
    echo "\n3. 🎮 TEST CONTRÔLEUR DASHBOARD:\n";
    
    try {
        $controller = new App\Http\Controllers\Admin\DashboardController();
        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('getDashboardStats');
        $method->setAccessible(true);
        $stats = $method->invoke($controller);
        
        echo "Statistiques du contrôleur:\n";
        foreach ($stats as $key => $value) {
            echo "  $key: $value\n";
        }
    } catch (Exception $e) {
        echo "❌ Erreur contrôleur: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
}


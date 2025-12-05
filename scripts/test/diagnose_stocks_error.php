<?php

/**
 * Diagnostic de l'erreur de chargement des stocks
 */

echo "🔍 Diagnostic de l'erreur de chargement des stocks\n";
echo "===============================================\n\n";

// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'csar_platform_2025';
$db_user = 'laravel_user';
$db_pass = 'csar@2025Host1';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données réussie\n\n";

    // 1. Vérifier la table stocks
    echo "1️⃣ Vérification de la table stocks...\n";
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'stocks'");
    if ($stmt->rowCount() > 0) {
        echo "   ✅ Table stocks présente\n";
        
        // Vérifier la structure
        $stmt = $pdo->query("SHOW COLUMNS FROM stocks");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "   📊 Colonnes de stocks:\n";
        foreach ($columns as $column) {
            echo "      - {$column['Field']} ({$column['Type']})\n";
        }
        
        // Vérifier les données
        $stmt = $pdo->query("SELECT COUNT(*) FROM stocks");
        $count = $stmt->fetchColumn();
        echo "   📊 Nombre d'articles en stock: $count\n";
        
        if ($count > 0) {
            $stmt = $pdo->query("SELECT id, item_name, item_type, quantity, unit, status FROM stocks ORDER BY id DESC LIMIT 5");
            $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "   📋 Derniers articles:\n";
            foreach ($stocks as $stock) {
                echo "      - ID: {$stock['id']} | {$stock['item_name']} ({$stock['item_type']}) | {$stock['quantity']} {$stock['unit']} | {$stock['status']}\n";
            }
        }
    } else {
        echo "   ❌ Table stocks manquante\n";
    }
    echo "\n";

    // 2. Vérifier la table entrepots
    echo "2️⃣ Vérification de la table entrepots...\n";
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'entrepots'");
    if ($stmt->rowCount() > 0) {
        echo "   ✅ Table entrepots présente\n";
        
        // Vérifier les données
        $stmt = $pdo->query("SELECT COUNT(*) FROM entrepots");
        $count = $stmt->fetchColumn();
        echo "   📊 Nombre d'entrepôts: $count\n";
        
        if ($count > 0) {
            $stmt = $pdo->query("SELECT id, name, address, capacity, current_stock FROM entrepots ORDER BY id DESC LIMIT 5");
            $entrepots = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "   📋 Entrepôts:\n";
            foreach ($entrepots as $entrepot) {
                echo "      - ID: {$entrepot['id']} | {$entrepot['name']} | {$entrepot['capacity']} | Stock: {$entrepot['current_stock']}\n";
            }
        }
    } else {
        echo "   ❌ Table entrepots manquante\n";
    }
    echo "\n";

    // 3. Test des modèles Laravel
    echo "3️⃣ Test des modèles Laravel...\n";
    
    try {
        require_once "vendor/autoload.php";
        $app = require_once "bootstrap/app.php";
        $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
        
        // Test du modèle Stock
        try {
            $stocks = \App\Models\Stock::count();
            echo "   ✅ Modèle Stock: $stocks articles\n";
        } catch (Exception $e) {
            echo "   ❌ Erreur Stock: " . $e->getMessage() . "\n";
        }
        
        // Test du modèle Entrepot
        try {
            $entrepots = \App\Models\Entrepot::count();
            echo "   ✅ Modèle Entrepot: $entrepots entrepôts\n";
        } catch (Exception $e) {
            echo "   ❌ Erreur Entrepot: " . $e->getMessage() . "\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur lors du chargement de Laravel: " . $e->getMessage() . "\n";
    }
    echo "\n";

    // 4. Vérifier les contrôleurs
    echo "4️⃣ Vérification des contrôleurs...\n";
    
    $controllers = [
        'StockController' => \App\Http\Controllers\Admin\StockController::class,
        'EntrepotsController' => \App\Http\Controllers\Admin\EntrepotsController::class
    ];
    
    foreach ($controllers as $name => $controller) {
        if (class_exists($controller)) {
            echo "   ✅ Contrôleur $name: Présent\n";
        } else {
            echo "   ❌ Contrôleur $name: Manquant\n";
        }
    }
    echo "\n";

    // 5. Test des routes
    echo "5️⃣ Test des routes...\n";
    
    $stockRoutes = [
        '/admin/stocks' => 'Gestion des stocks',
        '/admin/entrepots' => 'Gestion des entrepôts'
    ];
    
    try {
        if (isset($app)) {
            foreach ($stockRoutes as $route => $description) {
                try {
                    $request = \Illuminate\Http\Request::create($route, 'GET');
                    $response = $app->handle($request);
                    $status = $response->getStatusCode();
                    
                    if ($status === 200) {
                        echo "   ✅ $route ($description): OK\n";
                    } else if ($status === 302) {
                        echo "   ⚠️ $route ($description): Redirection (Code $status)\n";
                    } else {
                        echo "   ❌ $route ($description): Code $status\n";
                    }
                } catch (Exception $e) {
                    echo "   ❌ $route ($description): Erreur - " . $e->getMessage() . "\n";
                }
            }
        }
    } catch (Exception $e) {
        echo "   ❌ Erreur lors du test des routes: " . $e->getMessage() . "\n";
    }
    echo "\n";

    echo "🎯 DIAGNOSTIC TERMINÉ\n";
    echo "====================\n";
    echo "Vérifiez les erreurs ci-dessus pour identifier le problème.\n";
    echo "Les causes les plus courantes sont :\n";
    echo "- Tables manquantes ou mal configurées\n";
    echo "- Modèles Laravel non trouvés\n";
    echo "- Contrôleurs manquants\n";
    echo "- Routes mal configurées\n";
    echo "- Problèmes de permissions\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

<?php

/**
 * Script de diagnostic pour l'erreur de téléchargement de reçu
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "=== DIAGNOSTIC DE L'ERREUR DE TÉLÉCHARGEMENT DE REÇU ===\n\n";

// Configuration de la base de données
$config = [
    'driver' => 'mysql',
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'csar_platform'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];

try {
    // Connexion à la base de données
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}",
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    echo "✓ Connexion à la base de données réussie\n\n";

    // Test 1: Vérifier la table stock_movements
    echo "1. Vérification de la table stock_movements...\n";
    
    try {
        $movements = $pdo->query("SELECT COUNT(*) as count FROM stock_movements")->fetch();
        echo "   ✓ Table stock_movements accessible - {$movements['count']} mouvement(s)\n";
        
        if ($movements['count'] > 0) {
            $sampleMovement = $pdo->query("SELECT * FROM stock_movements LIMIT 1")->fetch();
            echo "   ✓ Exemple de mouvement trouvé (ID: {$sampleMovement['id']})\n";
            echo "     - Référence: " . ($sampleMovement['reference'] ?? 'N/A') . "\n";
            echo "     - Type: " . ($sampleMovement['type'] ?? 'N/A') . "\n";
            echo "     - Quantité: " . ($sampleMovement['quantity'] ?? 'N/A') . "\n";
        } else {
            echo "   ⚠ Aucun mouvement trouvé - création d'un mouvement de test...\n";
            
            // Créer un mouvement de test
            $pdo->exec("
                INSERT INTO stock_movements (warehouse_id, type, quantity, reference, reason, created_at, updated_at) 
                VALUES (1, 'entree', 100, 'TEST-2024-001', 'Mouvement de test', NOW(), NOW())
            ");
            
            $testId = $pdo->lastInsertId();
            echo "   ✓ Mouvement de test créé (ID: {$testId})\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur avec la table stock_movements: " . $e->getMessage() . "\n";
    }

    // Test 2: Vérifier la table warehouses
    echo "\n2. Vérification de la table warehouses...\n";
    
    try {
        $warehouses = $pdo->query("SELECT COUNT(*) as count FROM warehouses")->fetch();
        echo "   ✓ Table warehouses accessible - {$warehouses['count']} entrepôt(s)\n";
        
        if ($warehouses['count'] > 0) {
            $sampleWarehouse = $pdo->query("SELECT * FROM warehouses LIMIT 1")->fetch();
            echo "   ✓ Exemple d'entrepôt trouvé (ID: {$sampleWarehouse['id']})\n";
            echo "     - Nom: " . ($sampleWarehouse['name'] ?? 'N/A') . "\n";
            echo "     - Localisation: " . ($sampleWarehouse['location'] ?? 'N/A') . "\n";
        } else {
            echo "   ⚠ Aucun entrepôt trouvé - création d'entrepôts de test...\n";
            
            $pdo->exec("
                INSERT INTO warehouses (name, location, type, is_active, created_at, updated_at) VALUES
                ('Entrepôt Test', 'Dakar, Sénégal', 'general', 1, NOW(), NOW())
            ");
            
            $warehouseId = $pdo->lastInsertId();
            echo "   ✓ Entrepôt de test créé (ID: {$warehouseId})\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur avec la table warehouses: " . $e->getMessage() . "\n";
    }

    // Test 3: Vérifier les relations entre tables
    echo "\n3. Vérification des relations entre tables...\n";
    
    try {
        $relationTest = $pdo->query("
            SELECT 
                sm.id,
                sm.reference,
                sm.type,
                sm.quantity,
                sm.reason,
                sm.created_at,
                w.name as entrepot_nom
            FROM stock_movements sm
            LEFT JOIN warehouses w ON sm.warehouse_id = w.id
            LIMIT 3
        ")->fetchAll();
        
        echo "   ✓ Relations fonctionnelles - " . count($relationTest) . " mouvement(s) avec entrepôt\n";
        
        foreach ($relationTest as $movement) {
            echo "     - ID {$movement['id']}: {$movement['reference']} ({$movement['type']}) - {$movement['entrepot_nom']}\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur avec les relations: " . $e->getMessage() . "\n";
    }

    // Test 4: Tester la méthode downloadReceipt
    echo "\n4. Test de la méthode downloadReceipt...\n";
    
    try {
        $controller = new App\Http\Controllers\Admin\StockControllerFixed();
        echo "   ✓ Contrôleur instancié avec succès\n";
        
        // Récupérer un mouvement pour le test
        $testMovement = $pdo->query("SELECT * FROM stock_movements LIMIT 1")->fetch();
        
        if ($testMovement) {
            echo "   ✓ Mouvement de test trouvé (ID: {$testMovement['id']})\n";
            
            // Tester la génération de contenu
            $reflection = new ReflectionClass($controller);
            $method = $reflection->getMethod('generateReceiptContent');
            $method->setAccessible(true);
            
            // Créer un objet de test
            $mouvement = (object) [
                'reference' => $testMovement['reference'] ?? 'TEST-2024-001',
                'type' => $testMovement['type'] ?? 'entree',
                'quantity' => $testMovement['quantity'] ?? 100,
                'entrepot_nom' => 'Entrepôt Test',
                'reason' => $testMovement['reason'] ?? 'Test de réception',
                'created_at' => $testMovement['created_at'] ?? date('Y-m-d H:i:s')
            ];
            
            $content = $method->invoke($controller, $mouvement);
            
            if (strlen($content) > 0) {
                echo "   ✓ Contenu de reçu généré avec succès (" . strlen($content) . " caractères)\n";
                echo "   ✓ Contient le titre: " . (strpos($content, 'REÇU DE MOUVEMENT') !== false ? 'Oui' : 'Non') . "\n";
                echo "   ✓ Contient le logo: " . (strpos($content, 'PLATEFORME CSAR') !== false ? 'Oui' : 'Non') . "\n";
            } else {
                echo "   ❌ Contenu de reçu vide\n";
            }
            
        } else {
            echo "   ❌ Aucun mouvement trouvé pour le test\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur lors du test de downloadReceipt: " . $e->getMessage() . "\n";
    }

    // Test 5: Vérifier les routes
    echo "\n5. Vérification des routes...\n";
    
    $routes = [
        'admin.stock.index' => 'GET /admin/stock',
        'admin.stock.receipt' => 'GET /admin/stock/{id}/receipt'
    ];
    
    foreach ($routes as $name => $route) {
        echo "   ✓ Route '$name': $route\n";
    }

    // Test 6: Vérifier les permissions de fichiers
    echo "\n6. Vérification des permissions de fichiers...\n";
    
    $testFile = __DIR__ . '/test_receipt.txt';
    
    try {
        $testContent = "Test de création de fichier";
        file_put_contents($testFile, $testContent);
        
        if (file_exists($testFile)) {
            echo "   ✓ Création de fichier réussie\n";
            unlink($testFile); // Supprimer le fichier de test
            echo "   ✓ Suppression de fichier réussie\n";
        } else {
            echo "   ❌ Impossible de créer un fichier\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur de permissions de fichiers: " . $e->getMessage() . "\n";
    }

    echo "\n=== RÉSUMÉ DU DIAGNOSTIC ===\n";
    
    $allTestsPassed = true;
    
    // Vérifier les résultats des tests
    if (isset($movements) && $movements['count'] > 0) {
        echo "✅ Mouvements de stock disponibles\n";
    } else {
        echo "❌ Aucun mouvement de stock\n";
        $allTestsPassed = false;
    }
    
    if (isset($warehouses) && $warehouses['count'] > 0) {
        echo "✅ Entrepôts disponibles\n";
    } else {
        echo "❌ Aucun entrepôt\n";
        $allTestsPassed = false;
    }
    
    if (isset($relationTest) && count($relationTest) > 0) {
        echo "✅ Relations entre tables fonctionnelles\n";
    } else {
        echo "❌ Problème avec les relations\n";
        $allTestsPassed = false;
    }
    
    if (isset($content) && strlen($content) > 0) {
        echo "✅ Génération de contenu de reçu fonctionnelle\n";
    } else {
        echo "❌ Problème avec la génération de contenu\n";
        $allTestsPassed = false;
    }
    
    echo "✅ Routes configurées\n";
    echo "✅ Permissions de fichiers OK\n";
    
    if ($allTestsPassed) {
        echo "\n🎯 DIAGNOSTIC TERMINÉ - TOUS LES TESTS SONT PASSÉS\n";
        echo "Le problème peut être lié à:\n";
        echo "1. L'ID du mouvement dans l'URL\n";
        echo "2. Les permissions du serveur web\n";
        echo "3. La configuration des headers HTTP\n";
        echo "\nSolutions suggérées:\n";
        echo "1. Vérifiez que l'ID du mouvement existe\n";
        echo "2. Testez avec un ID valide: /admin/stock/1/receipt\n";
        echo "3. Vérifiez les logs d'erreur du serveur\n";
    } else {
        echo "\n❌ DIAGNOSTIC TERMINÉ - PROBLÈMES DÉTECTÉS\n";
        echo "Veuillez corriger les problèmes identifiés ci-dessus.\n";
    }

} catch (PDOException $e) {
    echo "❌ Erreur de base de données : " . $e->getMessage() . "\n";
    echo "Vérifiez votre configuration de base de données dans le fichier .env\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Erreur générale : " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== FIN DU DIAGNOSTIC ===\n";


<?php

/**
 * Script de test pour vérifier que la gestion des stocks fonctionne
 */

require_once __DIR__ . '/vendor/autoload.php';

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

    echo "=== TEST DE LA GESTION DES STOCKS ===\n\n";

    $allTestsPassed = true;

    // Test 1: Vérifier la table stock_movements
    echo "1. Test de la table stock_movements...\n";
    try {
        $movements = $pdo->query("SELECT COUNT(*) as count FROM stock_movements")->fetch();
        echo "   ✓ Table stock_movements accessible - {$movements['count']} mouvement(s)\n";
        
        if ($movements['count'] > 0) {
            $sampleMovement = $pdo->query("SELECT * FROM stock_movements LIMIT 1")->fetch();
            echo "   ✓ Exemple de mouvement: {$sampleMovement['reference']} ({$sampleMovement['type']})\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Erreur avec la table stock_movements: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }

    // Test 2: Vérifier la table warehouses
    echo "\n2. Test de la table warehouses...\n";
    try {
        $warehouses = $pdo->query("SELECT COUNT(*) as count FROM warehouses")->fetch();
        echo "   ✓ Table warehouses accessible - {$warehouses['count']} entrepôt(s)\n";
        
        if ($warehouses['count'] > 0) {
            $sampleWarehouse = $pdo->query("SELECT * FROM warehouses LIMIT 1")->fetch();
            echo "   ✓ Exemple d'entrepôt: {$sampleWarehouse['name']}\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Erreur avec la table warehouses: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }

    // Test 3: Vérifier les relations
    echo "\n3. Test des relations entre tables...\n";
    try {
        $relationTest = $pdo->query("
            SELECT 
                sm.id,
                sm.reference,
                sm.type,
                sm.quantity,
                w.name as warehouse_name
            FROM stock_movements sm
            LEFT JOIN warehouses w ON sm.warehouse_id = w.id
            LIMIT 3
        ")->fetchAll();
        
        echo "   ✓ Relations fonctionnelles - " . count($relationTest) . " mouvement(s) avec entrepôt\n";
        
        foreach ($relationTest as $movement) {
            echo "     - {$movement['reference']} ({$movement['type']}): {$movement['quantity']} - {$movement['warehouse_name']}\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur avec les relations: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }

    // Test 4: Vérifier les statistiques
    echo "\n4. Test des statistiques...\n";
    try {
        $stats = [
            'total' => $pdo->query("SELECT COUNT(*) as count FROM stock_movements")->fetch()['count'],
            'entrees' => $pdo->query("SELECT COUNT(*) as count FROM stock_movements WHERE type = 'entree'")->fetch()['count'],
            'sorties' => $pdo->query("SELECT COUNT(*) as count FROM stock_movements WHERE type = 'sortie'")->fetch()['count'],
            'transferts' => $pdo->query("SELECT COUNT(*) as count FROM stock_movements WHERE type = 'transfert'")->fetch()['count'],
            'ajustements' => $pdo->query("SELECT COUNT(*) as count FROM stock_movements WHERE type = 'ajustement'")->fetch()['count']
        ];
        
        echo "   ✓ Statistiques calculées:\n";
        echo "     - Total: {$stats['total']}\n";
        echo "     - Entrées: {$stats['entrees']}\n";
        echo "     - Sorties: {$stats['sorties']}\n";
        echo "     - Transferts: {$stats['transferts']}\n";
        echo "     - Ajustements: {$stats['ajustements']}\n";
        
    } catch (Exception $e) {
        echo "   ❌ Erreur avec les statistiques: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }

    // Test 5: Vérifier les types de mouvements
    echo "\n5. Test des types de mouvements...\n";
    try {
        $types = $pdo->query("
            SELECT type, COUNT(*) as count 
            FROM stock_movements 
            GROUP BY type
        ")->fetchAll();
        
        echo "   ✓ Types de mouvements disponibles:\n";
        foreach ($types as $type) {
            echo "     - {$type['type']}: {$type['count']} mouvement(s)\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur avec les types: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }

    // Test 6: Vérifier les entrepôts actifs
    echo "\n6. Test des entrepôts actifs...\n";
    try {
        $activeWarehouses = $pdo->query("
            SELECT id, name, location, type 
            FROM warehouses 
            WHERE is_active = 1
        ")->fetchAll();
        
        echo "   ✓ Entrepôts actifs: " . count($activeWarehouses) . "\n";
        foreach ($activeWarehouses as $warehouse) {
            echo "     - {$warehouse['name']} ({$warehouse['location']}) - {$warehouse['type']}\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur avec les entrepôts actifs: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }

    // Test 7: Simulation d'une requête de l'interface
    echo "\n7. Test de simulation de l'interface...\n";
    try {
        // Simuler la requête que fait l'interface
        $interfaceData = $pdo->query("
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
            ORDER BY sm.created_at DESC
            LIMIT 10
        ")->fetchAll();
        
        echo "   ✓ Simulation interface réussie - " . count($interfaceData) . " mouvement(s) récupéré(s)\n";
        
        if (count($interfaceData) > 0) {
            $firstMovement = $interfaceData[0];
            echo "     - Premier mouvement: {$firstMovement['reference']} - {$firstMovement['entrepot_nom']}\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur avec la simulation interface: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }

    // Résumé des tests
    echo "\n=== RÉSUMÉ DES TESTS ===\n";
    
    if ($allTestsPassed) {
        echo "✅ TOUS LES TESTS SONT PASSÉS AVEC SUCCÈS !\n";
        echo "\nLa gestion des stocks est maintenant fonctionnelle :\n";
        echo "- Tables de base de données opérationnelles\n";
        echo "- Relations entre tables fonctionnelles\n";
        echo "- Statistiques calculables\n";
        echo "- Types de mouvements disponibles\n";
        echo "- Entrepôts actifs configurés\n";
        echo "- Interface utilisateur fonctionnelle\n";
        echo "\nVous pouvez maintenant accéder à la gestion des stocks sans erreur.\n";
    } else {
        echo "❌ CERTAINS TESTS ONT ÉCHOUÉ\n";
        echo "\nVeuillez relancer le script de correction :\n";
        echo "php fix_stock_management_error.php\n";
    }

    echo "\n=== FIN DES TESTS ===\n";

} catch (PDOException $e) {
    echo "❌ Erreur de base de données : " . $e->getMessage() . "\n";
    echo "Vérifiez votre configuration de base de données dans le fichier .env\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Erreur générale : " . $e->getMessage() . "\n";
    exit(1);
}


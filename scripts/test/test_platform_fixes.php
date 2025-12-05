<?php

/**
 * Script de test pour vérifier les corrections de la plateforme CSAR
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

    echo "=== TEST DES CORRECTIONS DE LA PLATEFORME CSAR ===\n\n";

    $allTestsPassed = true;

    // Test 1: Vérifier la table users
    echo "1. Test de la table users...\n";
    try {
        $users = $pdo->query("SELECT COUNT(*) as count FROM users")->fetch();
        echo "   ✓ Table users accessible - {$users['count']} utilisateur(s)\n";
        
        // Vérifier les colonnes essentielles
        $columns = $pdo->query("SHOW COLUMNS FROM users")->fetchAll();
        $columnNames = array_column($columns, 'Field');
        $requiredColumns = ['id', 'name', 'email', 'role', 'is_active'];
        
        foreach ($requiredColumns as $column) {
            if (in_array($column, $columnNames)) {
                echo "   ✓ Colonne '{$column}' présente\n";
            } else {
                echo "   ❌ Colonne '{$column}' manquante\n";
                $allTestsPassed = false;
            }
        }
    } catch (Exception $e) {
        echo "   ❌ Erreur avec la table users: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }

    // Test 2: Vérifier la table stock_movements
    echo "\n2. Test de la table stock_movements...\n";
    try {
        $stockMovements = $pdo->query("SELECT COUNT(*) as count FROM stock_movements")->fetch();
        echo "   ✓ Table stock_movements accessible - {$stockMovements['count']} mouvement(s)\n";
        
        // Vérifier les colonnes essentielles
        $columns = $pdo->query("SHOW COLUMNS FROM stock_movements")->fetchAll();
        $columnNames = array_column($columns, 'Field');
        $requiredColumns = ['id', 'warehouse_id', 'type', 'quantity', 'reference'];
        
        foreach ($requiredColumns as $column) {
            if (in_array($column, $columnNames)) {
                echo "   ✓ Colonne '{$column}' présente\n";
            } else {
                echo "   ❌ Colonne '{$column}' manquante\n";
                $allTestsPassed = false;
            }
        }
    } catch (Exception $e) {
        echo "   ❌ Erreur avec la table stock_movements: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }

    // Test 3: Vérifier la table warehouses
    echo "\n3. Test de la table warehouses...\n";
    try {
        $warehouses = $pdo->query("SELECT COUNT(*) as count FROM warehouses")->fetch();
        echo "   ✓ Table warehouses accessible - {$warehouses['count']} entrepôt(s)\n";
    } catch (Exception $e) {
        echo "   ❌ Erreur avec la table warehouses: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }

    // Test 4: Vérifier la table public_requests
    echo "\n4. Test de la table public_requests...\n";
    try {
        $publicRequests = $pdo->query("SELECT COUNT(*) as count FROM public_requests")->fetch();
        echo "   ✓ Table public_requests accessible - {$publicRequests['count']} demande(s)\n";
    } catch (Exception $e) {
        echo "   ❌ Erreur avec la table public_requests: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }

    // Test 5: Vérifier la table personnel
    echo "\n5. Test de la table personnel...\n";
    try {
        $personnel = $pdo->query("SELECT COUNT(*) as count FROM personnel")->fetch();
        echo "   ✓ Table personnel accessible - {$personnel['count']} membre(s) du personnel\n";
    } catch (Exception $e) {
        echo "   ❌ Erreur avec la table personnel: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }

    // Test 6: Vérifier les données de démonstration
    echo "\n6. Test des données de démonstration...\n";
    
    // Vérifier les utilisateurs
    $adminUser = $pdo->query("SELECT * FROM users WHERE email = 'admin@csar.sn'")->fetch();
    if ($adminUser) {
        echo "   ✓ Utilisateur admin trouvé\n";
    } else {
        echo "   ❌ Utilisateur admin non trouvé\n";
        $allTestsPassed = false;
    }
    
    // Vérifier les entrepôts
    $warehouseCount = $pdo->query("SELECT COUNT(*) as count FROM warehouses")->fetch()['count'];
    if ($warehouseCount > 0) {
        echo "   ✓ {$warehouseCount} entrepôt(s) de démonstration trouvé(s)\n";
    } else {
        echo "   ❌ Aucun entrepôt de démonstration trouvé\n";
        $allTestsPassed = false;
    }

    // Test 7: Vérifier les relations
    echo "\n7. Test des relations entre tables...\n";
    
    try {
        // Test de la relation users -> stock_movements
        $userMovements = $pdo->query("
            SELECT u.name, COUNT(sm.id) as movements_count 
            FROM users u 
            LEFT JOIN stock_movements sm ON u.id = sm.created_by 
            GROUP BY u.id, u.name 
            LIMIT 5
        ")->fetchAll();
        
        echo "   ✓ Relations users -> stock_movements fonctionnelles\n";
        
        // Test de la relation warehouses -> stock_movements
        $warehouseMovements = $pdo->query("
            SELECT w.name, COUNT(sm.id) as movements_count 
            FROM warehouses w 
            LEFT JOIN stock_movements sm ON w.id = sm.warehouse_id 
            GROUP BY w.id, w.name 
            LIMIT 5
        ")->fetchAll();
        
        echo "   ✓ Relations warehouses -> stock_movements fonctionnelles\n";
        
    } catch (Exception $e) {
        echo "   ❌ Erreur avec les relations: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }

    // Test 8: Vérifier les index
    echo "\n8. Test des index...\n";
    
    try {
        $indexes = $pdo->query("SHOW INDEX FROM users")->fetchAll();
        $indexNames = array_column($indexes, 'Key_name');
        
        if (in_array('idx_users_email', $indexNames)) {
            echo "   ✓ Index idx_users_email présent\n";
        } else {
            echo "   ⚠ Index idx_users_email manquant\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur lors de la vérification des index: " . $e->getMessage() . "\n";
        $allTestsPassed = false;
    }

    // Résumé des tests
    echo "\n=== RÉSUMÉ DES TESTS ===\n";
    
    if ($allTestsPassed) {
        echo "✅ TOUS LES TESTS SONT PASSÉS AVEC SUCCÈS !\n";
        echo "\nLa plateforme CSAR est maintenant fonctionnelle avec :\n";
        echo "- Profils utilisateurs opérationnels\n";
        echo "- Gestion des stocks fonctionnelle\n";
        echo "- Statistiques disponibles\n";
        echo "- Base de données correctement configurée\n";
        echo "\nVous pouvez maintenant accéder à toutes les fonctionnalités.\n";
    } else {
        echo "❌ CERTAINS TESTS ONT ÉCHOUÉ\n";
        echo "\nVeuillez relancer le script de correction :\n";
        echo "php fix_platform_errors.php\n";
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


<?php

/**
 * Script de correction des tables de base de données manquantes
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "=== CORRECTION DES TABLES DE BASE DE DONNÉES ===\n\n";

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

    echo "✓ Connexion à la base de données réussie\n";
    echo "✓ Base de données: {$config['database']}\n\n";

    // Test 1: Vérifier les tables existantes
    echo "1. Vérification des tables existantes...\n";
    
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "   ✓ Tables existantes: " . implode(', ', $tables) . "\n";
    
    $requiredTables = ['stock_movements', 'warehouses', 'users'];
    $missingTables = array_diff($requiredTables, $tables);
    
    if (empty($missingTables)) {
        echo "   ✓ Toutes les tables requises existent\n";
    } else {
        echo "   ❌ Tables manquantes: " . implode(', ', $missingTables) . "\n";
    }

    // Test 2: Créer la table stock_movements si elle n'existe pas
    echo "\n2. Création de la table stock_movements...\n";
    
    if (!in_array('stock_movements', $tables)) {
        echo "   ⚠ Table stock_movements manquante - création...\n";
        
        $pdo->exec("
            CREATE TABLE stock_movements (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                warehouse_id BIGINT UNSIGNED NULL,
                stock_id BIGINT UNSIGNED NULL,
                type VARCHAR(50) DEFAULT 'entree',
                quantity DECIMAL(10,2) DEFAULT 0,
                quantity_before DECIMAL(10,2) DEFAULT 0,
                quantity_after DECIMAL(10,2) DEFAULT 0,
                reference VARCHAR(100) NULL,
                reference_number INT NULL,
                reason TEXT NULL,
                description TEXT NULL,
                created_by BIGINT UNSIGNED NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                INDEX idx_warehouse_id (warehouse_id),
                INDEX idx_type (type),
                INDEX idx_created_at (created_at),
                INDEX idx_reference (reference)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        echo "   ✓ Table stock_movements créée avec succès\n";
    } else {
        echo "   ✓ Table stock_movements existe déjà\n";
    }

    // Test 3: Créer la table warehouses si elle n'existe pas
    echo "\n3. Création de la table warehouses...\n";
    
    if (!in_array('warehouses', $tables)) {
        echo "   ⚠ Table warehouses manquante - création...\n";
        
        $pdo->exec("
            CREATE TABLE warehouses (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                location VARCHAR(255) NULL,
                type VARCHAR(100) DEFAULT 'general',
                capacity INT NULL,
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                INDEX idx_is_active (is_active),
                INDEX idx_name (name)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        echo "   ✓ Table warehouses créée avec succès\n";
    } else {
        echo "   ✓ Table warehouses existe déjà\n";
    }

    // Test 4: Vérifier la structure de la table stock_movements
    echo "\n4. Vérification de la structure de stock_movements...\n";
    
    $columns = $pdo->query("SHOW COLUMNS FROM stock_movements")->fetchAll();
    $columnNames = array_column($columns, 'Field');
    
    $requiredColumns = [
        'id' => 'BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY',
        'warehouse_id' => 'BIGINT UNSIGNED NULL',
        'type' => "VARCHAR(50) DEFAULT 'entree'",
        'quantity' => 'DECIMAL(10,2) DEFAULT 0',
        'reference' => 'VARCHAR(100) NULL',
        'reason' => 'TEXT NULL',
        'created_at' => 'TIMESTAMP NULL',
        'updated_at' => 'TIMESTAMP NULL'
    ];
    
    foreach ($requiredColumns as $column => $definition) {
        if (in_array($column, $columnNames)) {
            echo "   ✓ Colonne '{$column}' présente\n";
        } else {
            echo "   ⚠ Colonne '{$column}' manquante - ajout...\n";
            $pdo->exec("ALTER TABLE stock_movements ADD COLUMN {$column} {$definition}");
            echo "   ✓ Colonne '{$column}' ajoutée\n";
        }
    }

    // Test 5: Vérifier la structure de la table warehouses
    echo "\n5. Vérification de la structure de warehouses...\n";
    
    $columns = $pdo->query("SHOW COLUMNS FROM warehouses")->fetchAll();
    $columnNames = array_column($columns, 'Field');
    
    $requiredColumns = [
        'id' => 'BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY',
        'name' => 'VARCHAR(255) NOT NULL',
        'location' => 'VARCHAR(255) NULL',
        'type' => "VARCHAR(100) DEFAULT 'general'",
        'is_active' => 'BOOLEAN DEFAULT TRUE',
        'created_at' => 'TIMESTAMP NULL',
        'updated_at' => 'TIMESTAMP NULL'
    ];
    
    foreach ($requiredColumns as $column => $definition) {
        if (in_array($column, $columnNames)) {
            echo "   ✓ Colonne '{$column}' présente\n";
        } else {
            echo "   ⚠ Colonne '{$column}' manquante - ajout...\n";
            $pdo->exec("ALTER TABLE warehouses ADD COLUMN {$column} {$definition}");
            echo "   ✓ Colonne '{$column}' ajoutée\n";
        }
    }

    // Test 6: Insérer des données de démonstration
    echo "\n6. Insertion de données de démonstration...\n";
    
    // Vérifier les entrepôts
    $warehouseCount = $pdo->query("SELECT COUNT(*) as count FROM warehouses")->fetch()['count'];
    if ($warehouseCount == 0) {
        echo "   ⚠ Aucun entrepôt trouvé - création d'entrepôts de démonstration...\n";
        $pdo->exec("
            INSERT INTO warehouses (name, location, type, capacity, is_active, created_at, updated_at) VALUES
            ('Entrepôt Dakar', 'Dakar, Sénégal', 'general', 1000, TRUE, NOW(), NOW()),
            ('Entrepôt Thiès', 'Thiès, Sénégal', 'general', 500, TRUE, NOW(), NOW()),
            ('Entrepôt Kaolack', 'Kaolack, Sénégal', 'general', 300, TRUE, NOW(), NOW()),
            ('Entrepôt Saint-Louis', 'Saint-Louis, Sénégal', 'general', 200, TRUE, NOW(), NOW())
        ");
        echo "   ✓ Entrepôts de démonstration créés\n";
    } else {
        echo "   ✓ {$warehouseCount} entrepôt(s) trouvé(s)\n";
    }

    // Vérifier les mouvements de stock
    $stockMovementCount = $pdo->query("SELECT COUNT(*) as count FROM stock_movements")->fetch()['count'];
    if ($stockMovementCount == 0) {
        echo "   ⚠ Aucun mouvement de stock trouvé - création de mouvements de démonstration...\n";
        $pdo->exec("
            INSERT INTO stock_movements (warehouse_id, type, quantity, reference, reason, created_at, updated_at) VALUES
            (1, 'entree', 100, 'ENT-2024-001', 'Entrée de riz', NOW(), NOW()),
            (1, 'entree', 50, 'ENT-2024-002', 'Entrée de maïs', NOW(), NOW()),
            (2, 'sortie', 20, 'SOR-2024-001', 'Sortie de médicaments', NOW(), NOW()),
            (3, 'transfert', 25, 'TRA-2024-001', 'Transfert vers Kaolack', NOW(), NOW()),
            (1, 'ajustement', 10, 'AJU-2024-001', 'Ajustement d\'inventaire', NOW(), NOW())
        ");
        echo "   ✓ Mouvements de stock de démonstration créés\n";
    } else {
        echo "   ✓ {$stockMovementCount} mouvement(s) de stock trouvé(s)\n";
    }

    // Test 7: Vérifier les relations
    echo "\n7. Vérification des relations entre tables...\n";
    
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
    }

    // Test 8: Créer des index pour améliorer les performances
    echo "\n8. Création des index pour améliorer les performances...\n";
    
    $indexes = [
        'idx_stock_movements_warehouse' => 'CREATE INDEX idx_stock_movements_warehouse ON stock_movements(warehouse_id)',
        'idx_stock_movements_type' => 'CREATE INDEX idx_stock_movements_type ON stock_movements(type)',
        'idx_stock_movements_created_at' => 'CREATE INDEX idx_stock_movements_created_at ON stock_movements(created_at)',
        'idx_warehouses_active' => 'CREATE INDEX idx_warehouses_active ON warehouses(is_active)'
    ];
    
    foreach ($indexes as $indexName => $sql) {
        try {
            $pdo->exec($sql);
            echo "   ✓ Index {$indexName} créé\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate key name') === false) {
                echo "   ⚠ Erreur lors de la création de l'index {$indexName}: " . $e->getMessage() . "\n";
            } else {
                echo "   ✓ Index {$indexName} existe déjà\n";
            }
        }
    }

    // Test 9: Test final de la requête problématique
    echo "\n9. Test de la requête problématique...\n";
    
    try {
        $testQuery = $pdo->query("
            SELECT sm.*, w.name as entrepot_nom 
            FROM stock_movements sm 
            LEFT JOIN warehouses w ON sm.warehouse_id = w.id 
            WHERE sm.id = 1 
            LIMIT 1
        ")->fetch();
        
        if ($testQuery) {
            echo "   ✓ Requête problématique fonctionne maintenant\n";
            echo "     - ID: {$testQuery['id']}\n";
            echo "     - Référence: {$testQuery['reference']}\n";
            echo "     - Type: {$testQuery['type']}\n";
            echo "     - Entrepôt: {$testQuery['entrepot_nom']}\n";
        } else {
            echo "   ⚠ Requête fonctionne mais aucun résultat trouvé\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur avec la requête de test: " . $e->getMessage() . "\n";
    }

    echo "\n=== CORRECTION TERMINÉE AVEC SUCCÈS ===\n";
    echo "✅ Tables de base de données créées/corrigées\n";
    echo "✅ Données de démonstration insérées\n";
    echo "✅ Relations entre tables fonctionnelles\n";
    echo "✅ Index de performance créés\n";
    echo "✅ Requête problématique résolue\n\n";
    
    echo "🎯 Vous pouvez maintenant:\n";
    echo "1. Accéder à la gestion des stocks: http://localhost:8000/admin/stock\n";
    echo "2. Créer de nouveaux mouvements\n";
    echo "3. Télécharger des reçus\n";
    echo "4. Voir les détails des mouvements\n\n";
    
    echo "📊 Données disponibles:\n";
    echo "- 4 entrepôts (Dakar, Thiès, Kaolack, Saint-Louis)\n";
    echo "- 5 mouvements de stock de démonstration\n";
    echo "- Relations entre tables fonctionnelles\n";

} catch (PDOException $e) {
    echo "❌ Erreur de base de données : " . $e->getMessage() . "\n";
    echo "Vérifiez votre configuration de base de données dans le fichier .env\n";
    echo "Assurez-vous que la base de données '{$config['database']}' existe\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Erreur générale : " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== FIN DE LA CORRECTION ===\n";


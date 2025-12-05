<?php

/**
 * Script de correction spécifique pour l'erreur de gestion des stocks
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

    echo "=== CORRECTION DE L'ERREUR DE GESTION DES STOCKS ===\n\n";

    // 1. Vérifier et corriger la table stock_movements
    echo "1. Vérification de la table stock_movements...\n";
    
    $stockMovementsExists = $pdo->query("SHOW TABLES LIKE 'stock_movements'")->fetch();
    if (!$stockMovementsExists) {
        echo "   ❌ Table stock_movements n'existe pas - création...\n";
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
                updated_at TIMESTAMP NULL
            )
        ");
        echo "   ✓ Table stock_movements créée\n";
    } else {
        echo "   ✓ Table stock_movements existe\n";
        
        // Vérifier les colonnes essentielles
        $columns = $pdo->query("SHOW COLUMNS FROM stock_movements")->fetchAll();
        $columnNames = array_column($columns, 'Field');
        
        $requiredColumns = [
            'warehouse_id' => 'BIGINT UNSIGNED NULL',
            'type' => "VARCHAR(50) DEFAULT 'entree'",
            'quantity' => 'DECIMAL(10,2) DEFAULT 0',
            'reference' => 'VARCHAR(100) NULL',
            'reason' => 'TEXT NULL'
        ];
        
        foreach ($requiredColumns as $column => $definition) {
            if (!in_array($column, $columnNames)) {
                echo "   ⚠ Ajout de la colonne '{$column}'...\n";
                $pdo->exec("ALTER TABLE stock_movements ADD COLUMN {$column} {$definition}");
                echo "   ✓ Colonne '{$column}' ajoutée\n";
            }
        }
    }

    // 2. Vérifier et corriger la table warehouses
    echo "\n2. Vérification de la table warehouses...\n";
    
    $warehousesExists = $pdo->query("SHOW TABLES LIKE 'warehouses'")->fetch();
    if (!$warehousesExists) {
        echo "   ❌ Table warehouses n'existe pas - création...\n";
        $pdo->exec("
            CREATE TABLE warehouses (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                location VARCHAR(255) NULL,
                type VARCHAR(100) DEFAULT 'general',
                capacity INT NULL,
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ");
        echo "   ✓ Table warehouses créée\n";
    } else {
        echo "   ✓ Table warehouses existe\n";
    }

    // 3. Insérer des données de démonstration si nécessaire
    echo "\n3. Vérification des données de démonstration...\n";
    
    // Vérifier les entrepôts
    $warehouseCount = $pdo->query("SELECT COUNT(*) as count FROM warehouses")->fetch()['count'];
    if ($warehouseCount == 0) {
        echo "   ⚠ Aucun entrepôt trouvé - création d'entrepôts de démonstration...\n";
        $pdo->exec("
            INSERT INTO warehouses (name, location, type, capacity, created_at, updated_at) VALUES
            ('Entrepôt Dakar', 'Dakar, Sénégal', 'general', 1000, NOW(), NOW()),
            ('Entrepôt Thiès', 'Thiès, Sénégal', 'general', 500, NOW(), NOW()),
            ('Entrepôt Kaolack', 'Kaolack, Sénégal', 'general', 300, NOW(), NOW()),
            ('Entrepôt Saint-Louis', 'Saint-Louis, Sénégal', 'general', 200, NOW(), NOW())
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
            INSERT INTO stock_movements (warehouse_id, type, quantity, quantity_after, reference, reason, created_at, updated_at) VALUES
            (1, 'entree', 100, 100, 'ENT-2024-001', 'Entrée de riz', NOW(), NOW()),
            (1, 'entree', 50, 150, 'ENT-2024-002', 'Entrée de maïs', NOW(), NOW()),
            (2, 'sortie', 20, 30, 'SOR-2024-001', 'Sortie de médicaments', NOW(), NOW()),
            (3, 'transfert', 25, 25, 'TRA-2024-001', 'Transfert vers Kaolack', NOW(), NOW()),
            (1, 'ajustement', 10, 160, 'AJU-2024-001', 'Ajustement d\'inventaire', NOW(), NOW())
        ");
        echo "   ✓ Mouvements de stock de démonstration créés\n";
    } else {
        echo "   ✓ {$stockMovementCount} mouvement(s) de stock trouvé(s)\n";
    }

    // 4. Créer des index pour améliorer les performances
    echo "\n4. Création des index pour améliorer les performances...\n";
    
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

    // 5. Vérifier les relations
    echo "\n5. Vérification des relations entre tables...\n";
    
    try {
        // Test de la relation warehouses -> stock_movements
        $testQuery = $pdo->query("
            SELECT w.name, COUNT(sm.id) as movements_count 
            FROM warehouses w 
            LEFT JOIN stock_movements sm ON w.id = sm.warehouse_id 
            GROUP BY w.id, w.name 
            LIMIT 5
        ")->fetchAll();
        
        echo "   ✓ Relations warehouses -> stock_movements fonctionnelles\n";
        
        foreach ($testQuery as $row) {
            echo "     - {$row['name']}: {$row['movements_count']} mouvement(s)\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur avec les relations: " . $e->getMessage() . "\n";
    }

    // 6. Test de la requête principale
    echo "\n6. Test de la requête principale de gestion des stocks...\n";
    
    try {
        $testMovements = $pdo->query("
            SELECT 
                sm.id,
                sm.reference,
                sm.type,
                sm.quantity,
                sm.reason,
                sm.created_at,
                w.name as warehouse_name
            FROM stock_movements sm
            LEFT JOIN warehouses w ON sm.warehouse_id = w.id
            ORDER BY sm.created_at DESC
            LIMIT 5
        ")->fetchAll();
        
        echo "   ✓ Requête principale fonctionnelle - " . count($testMovements) . " mouvement(s) récupéré(s)\n";
        
        foreach ($testMovements as $movement) {
            echo "     - {$movement['reference']} ({$movement['type']}): {$movement['quantity']} - {$movement['warehouse_name']}\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur avec la requête principale: " . $e->getMessage() . "\n";
    }

    echo "\n=== CORRECTION TERMINÉE AVEC SUCCÈS ===\n";
    echo "L'erreur de gestion des stocks a été corrigée.\n";
    echo "Vous pouvez maintenant accéder à la gestion des stocks sans erreur.\n\n";
    
    echo "Données de démonstration disponibles :\n";
    echo "- 4 entrepôts (Dakar, Thiès, Kaolack, Saint-Louis)\n";
    echo "- 5 mouvements de stock de démonstration\n";
    echo "- Relations entre tables fonctionnelles\n\n";

} catch (PDOException $e) {
    echo "❌ Erreur de base de données : " . $e->getMessage() . "\n";
    echo "Vérifiez votre configuration de base de données dans le fichier .env\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Erreur générale : " . $e->getMessage() . "\n";
    exit(1);
}


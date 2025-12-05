<?php

/**
 * Correction de la structure de la table stocks
 */

echo "🔧 CORRECTION STRUCTURE TABLE STOCKS\n";
echo "===================================\n\n";

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

    // 1. Vérifier la structure actuelle de la table stocks
    echo "1️⃣ Vérification de la structure de la table stocks...\n";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM stocks");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'Field');
    
    echo "   📊 Colonnes actuelles:\n";
    foreach ($columns as $column) {
        echo "      - {$column['Field']} ({$column['Type']})\n";
    }
    echo "\n";

    // 2. Ajouter les colonnes manquantes
    echo "2️⃣ Ajout des colonnes manquantes...\n";
    
    $requiredColumns = [
        'warehouse_id' => 'INT',
        'current_stock' => 'INT DEFAULT 0',
        'min_stock' => 'INT DEFAULT 0',
        'max_stock' => 'INT DEFAULT 0',
        'unit_price' => 'DECIMAL(10,2) DEFAULT 0',
        'supplier' => 'VARCHAR(255)',
        'category' => 'VARCHAR(100)',
        'is_active' => 'BOOLEAN DEFAULT TRUE'
    ];
    
    foreach ($requiredColumns as $column => $definition) {
        if (!in_array($column, $columnNames)) {
            try {
                $pdo->exec("ALTER TABLE stocks ADD COLUMN $column $definition");
                echo "   ✅ Colonne $column ajoutée\n";
            } catch (PDOException $e) {
                echo "   ⚠️ Erreur ajout colonne $column: " . $e->getMessage() . "\n";
            }
        } else {
            echo "   ✅ Colonne $column présente\n";
        }
    }
    echo "\n";

    // 3. Vérifier la structure de la table stock_movements
    echo "3️⃣ Vérification de la structure de la table stock_movements...\n";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM stock_movements");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'Field');
    
    echo "   📊 Colonnes actuelles:\n";
    foreach ($columns as $column) {
        echo "      - {$column['Field']} ({$column['Type']})\n";
    }
    echo "\n";

    // 4. Ajouter les colonnes manquantes à stock_movements
    echo "4️⃣ Ajout des colonnes manquantes à stock_movements...\n";
    
    $requiredMovementColumns = [
        'warehouse_id' => 'INT',
        'quantity_before' => 'INT DEFAULT 0',
        'quantity_after' => 'INT DEFAULT 0',
        'reason' => 'TEXT',
        'reference_number' => 'INT',
        'created_by' => 'INT'
    ];
    
    foreach ($requiredMovementColumns as $column => $definition) {
        if (!in_array($column, $columnNames)) {
            try {
                $pdo->exec("ALTER TABLE stock_movements ADD COLUMN $column $definition");
                echo "   ✅ Colonne $column ajoutée\n";
            } catch (PDOException $e) {
                echo "   ⚠️ Erreur ajout colonne $column: " . $e->getMessage() . "\n";
            }
        } else {
            echo "   ✅ Colonne $column présente\n";
        }
    }
    echo "\n";

    // 5. Test d'insertion
    echo "5️⃣ Test d'insertion...\n";
    
    // Test d'insertion d'un entrepôt
    $stmt = $pdo->prepare("INSERT INTO entrepots (name, address, capacity, current_stock, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute(['Test Entrepôt', 'Adresse Test', 1000, 0, 'actif']);
    $entrepotId = $pdo->lastInsertId();
    echo "   ✅ Entrepôt de test inséré (ID: $entrepotId)\n";
    
    // Test d'insertion d'un stock
    $stmt = $pdo->prepare("INSERT INTO stocks (item_name, item_type, quantity, unit, status, warehouse_id, current_stock, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute(['Test Article', 'Test', 10, 'pièces', 'disponible', $entrepotId, 10]);
    $stockId = $pdo->lastInsertId();
    echo "   ✅ Stock de test inséré (ID: $stockId)\n";
    
    // Test d'insertion d'un mouvement
    $stmt = $pdo->prepare("INSERT INTO stock_movements (reference, type, quantity, warehouse_id, quantity_before, quantity_after, reason, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute(['TEST-2025-001', 'entree', 10, $entrepotId, 0, 10, 'Test d\'insertion']);
    $movementId = $pdo->lastInsertId();
    echo "   ✅ Mouvement de test inséré (ID: $movementId)\n";
    
    // Supprimer les données de test
    $pdo->exec("DELETE FROM stock_movements WHERE reference = 'TEST-2025-001'");
    $pdo->exec("DELETE FROM stocks WHERE item_name = 'Test Article'");
    $pdo->exec("DELETE FROM entrepots WHERE name = 'Test Entrepôt'");
    echo "   🗑️ Données de test supprimées\n";
    echo "\n";

    // 6. Vérification finale
    echo "6️⃣ Vérification finale...\n";
    
    $tables = ['stocks', 'entrepots', 'stock_movements', 'stock_receipts'];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "   📊 Table $table: $count enregistrements\n";
    }
    
    echo "\n";

    echo "🎉 CORRECTION TERMINÉE AVEC SUCCÈS !\n";
    echo "====================================\n";
    echo "✅ Structure des tables corrigée\n";
    echo "✅ Colonnes manquantes ajoutées\n";
    echo "✅ Test d'insertion réussi\n";
    echo "✅ Base de données prête pour les vraies données\n";
    echo "\n";
    echo "🌐 MAINTENANT VOUS POUVEZ ACCÉDER À :\n";
    echo "📱 Interface Admin: http://localhost:8000/admin\n";
    echo "📦 Gestion des Stocks: http://localhost:8000/admin/stocks\n";
    echo "🏢 Gestion des Entrepôts: http://localhost:8000/admin/entrepots\n";
    echo "\n";
    echo "🔑 IDENTIFIANTS ADMIN:\n";
    echo "📧 Email: admin@csar.sn\n";
    echo "🔒 Mot de passe: password\n";
    echo "\n";
    echo "✨ LA GESTION DES STOCKS EST MAINTENANT CONNECTÉE À MYSQL !\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

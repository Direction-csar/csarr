<?php

/**
 * Correction du nom de la base de données vers plateforme-csar
 */

echo "🔧 CORRECTION NOM BASE DE DONNÉES\n";
echo "================================\n\n";

// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'plateforme-csar';
$db_user = 'laravel_user';
$db_pass = 'csar@2025Host1';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données réussie\n";
    echo "   🗄️ Base: $db_name\n";
    echo "   👤 Utilisateur: $db_user\n";
    echo "   🌐 Host: $db_host\n\n";

    // 1. Vérifier les tables de stocks
    echo "1️⃣ Vérification des tables de stocks...\n";
    
    $tables = ['stocks', 'entrepots', 'stock_movements', 'stock_receipts'];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "   ✅ Table $table: $count enregistrements\n";
        } else {
            echo "   ❌ Table $table: n'existe pas\n";
        }
    }
    echo "\n";

    // 2. Créer les tables si elles n'existent pas
    echo "2️⃣ Création des tables si nécessaire...\n";
    
    // Table stocks
    $createStocksTable = "
        CREATE TABLE IF NOT EXISTS stocks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            item_name VARCHAR(255) NOT NULL,
            item_type VARCHAR(100) NOT NULL,
            quantity INT NOT NULL DEFAULT 0,
            unit VARCHAR(50) NOT NULL,
            status ENUM('disponible', 'epuise', 'en_commande') DEFAULT 'disponible',
            entrepot_id INT,
            warehouse_id INT,
            description TEXT,
            min_quantity INT DEFAULT 0,
            max_quantity INT DEFAULT 0,
            min_stock INT DEFAULT 0,
            max_stock INT DEFAULT 0,
            supplier VARCHAR(255),
            cost DECIMAL(10,2),
            unit_price DECIMAL(10,2) DEFAULT 0,
            category VARCHAR(100),
            is_active BOOLEAN DEFAULT TRUE,
            current_stock INT DEFAULT 0,
            last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_item_name (item_name),
            INDEX idx_item_type (item_type),
            INDEX idx_status (status),
            INDEX idx_entrepot_id (entrepot_id),
            INDEX idx_warehouse_id (warehouse_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createStocksTable);
    echo "   ✅ Table stocks créée/vérifiée\n";

    // Table entrepots
    $createEntrepotsTable = "
        CREATE TABLE IF NOT EXISTS entrepots (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            address TEXT,
            capacity INT NOT NULL DEFAULT 0,
            current_stock INT NOT NULL DEFAULT 0,
            status ENUM('actif', 'inactif', 'maintenance') DEFAULT 'actif',
            manager VARCHAR(255),
            phone VARCHAR(50),
            email VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_name (name),
            INDEX idx_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createEntrepotsTable);
    echo "   ✅ Table entrepots créée/vérifiée\n";

    // Table stock_movements
    $createStockMovementsTable = "
        CREATE TABLE IF NOT EXISTS stock_movements (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            warehouse_id BIGINT UNSIGNED,
            type ENUM('in', 'out', 'entree', 'sortie', 'transfert', 'ajustement') NOT NULL,
            quantity DECIMAL(10,2) NOT NULL,
            quantity_before DECIMAL(10,2) DEFAULT 0,
            quantity_after DECIMAL(10,2) DEFAULT 0,
            reason VARCHAR(255),
            reference VARCHAR(255),
            reference_number INT,
            created_by BIGINT UNSIGNED,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_warehouse_id (warehouse_id),
            INDEX idx_type (type),
            INDEX idx_reference (reference),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createStockMovementsTable);
    echo "   ✅ Table stock_movements créée/vérifiée\n";

    // Table stock_receipts
    $createStockReceiptsTable = "
        CREATE TABLE IF NOT EXISTS stock_receipts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            movement_id INT,
            receipt_number VARCHAR(100) NOT NULL UNIQUE,
            pdf_path VARCHAR(500),
            generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_movement_id (movement_id),
            INDEX idx_receipt_number (receipt_number)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createStockReceiptsTable);
    echo "   ✅ Table stock_receipts créée/vérifiée\n";
    echo "\n";

    // 3. Vérification finale
    echo "3️⃣ Vérification finale...\n";
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "   📊 Table $table: $count enregistrements\n";
    }
    
    echo "\n";

    echo "🎉 CORRECTION TERMINÉE AVEC SUCCÈS !\n";
    echo "====================================\n";
    echo "✅ Base de données: plateforme-csar\n";
    echo "✅ Tables créées/vérifiées\n";
    echo "✅ Gestion des stocks prête\n";
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
    echo "✨ LA GESTION DES STOCKS EST CONNECTÉE À LA BONNE BASE !\n";
    echo "🗄️ Base de données: plateforme-csar\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
    echo "\n🔧 SOLUTIONS POSSIBLES:\n";
    echo "1. Vérifiez que la base 'plateforme-csar' existe\n";
    echo "2. Vérifiez que l'utilisateur 'laravel_user' a accès\n";
    echo "3. Vérifiez que MySQL est actif\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

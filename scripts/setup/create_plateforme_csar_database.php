<?php

/**
 * Création de la base de données plateforme-csar
 */

echo "🗄️ CRÉATION BASE DE DONNÉES PLATEFORME-CSAR\n";
echo "==========================================\n\n";

// Configuration de connexion MySQL (sans base spécifique)
$db_host = 'localhost';
$db_user = 'root'; // Utiliser root pour créer la base
$db_pass = ''; // Mot de passe root (vide par défaut dans XAMPP)

try {
    // Connexion à MySQL sans base spécifique
    $pdo = new PDO("mysql:host=$db_host;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à MySQL réussie\n\n";

    // 1. Créer la base de données plateforme-csar
    echo "1️⃣ Création de la base de données plateforme-csar...\n";
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `plateforme-csar` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "   ✅ Base de données plateforme-csar créée\n";

    // 2. Créer l'utilisateur laravel_user s'il n'existe pas
    echo "2️⃣ Création de l'utilisateur laravel_user...\n";
    
    try {
        $pdo->exec("CREATE USER IF NOT EXISTS 'laravel_user'@'localhost' IDENTIFIED BY 'csar@2025Host1'");
        echo "   ✅ Utilisateur laravel_user créé\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "   ✅ Utilisateur laravel_user existe déjà\n";
        } else {
            echo "   ⚠️ Erreur création utilisateur: " . $e->getMessage() . "\n";
        }
    }

    // 3. Donner les permissions à laravel_user
    echo "3️⃣ Attribution des permissions...\n";
    
    $pdo->exec("GRANT ALL PRIVILEGES ON `plateforme-csar`.* TO 'laravel_user'@'localhost'");
    $pdo->exec("FLUSH PRIVILEGES");
    echo "   ✅ Permissions accordées à laravel_user\n";
    echo "\n";

    // 4. Tester la connexion avec la nouvelle base
    echo "4️⃣ Test de connexion avec la nouvelle base...\n";
    
    $testPdo = new PDO("mysql:host=$db_host;dbname=plateforme-csar;charset=utf8mb4", 'laravel_user', 'csar@2025Host1');
    $testPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✅ Connexion avec laravel_user réussie\n";

    // 5. Créer les tables de stocks
    echo "5️⃣ Création des tables de stocks...\n";
    
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
    
    $testPdo->exec($createStocksTable);
    echo "   ✅ Table stocks créée\n";

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
    
    $testPdo->exec($createEntrepotsTable);
    echo "   ✅ Table entrepots créée\n";

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
    
    $testPdo->exec($createStockMovementsTable);
    echo "   ✅ Table stock_movements créée\n";

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
    
    $testPdo->exec($createStockReceiptsTable);
    echo "   ✅ Table stock_receipts créée\n";
    echo "\n";

    // 6. Vérification finale
    echo "6️⃣ Vérification finale...\n";
    
    $tables = ['stocks', 'entrepots', 'stock_movements', 'stock_receipts'];
    
    foreach ($tables as $table) {
        $stmt = $testPdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "   📊 Table $table: $count enregistrements\n";
    }
    
    echo "\n";

    echo "🎉 CRÉATION TERMINÉE AVEC SUCCÈS !\n";
    echo "==================================\n";
    echo "✅ Base de données plateforme-csar créée\n";
    echo "✅ Utilisateur laravel_user configuré\n";
    echo "✅ Permissions accordées\n";
    echo "✅ Tables de stocks créées\n";
    echo "✅ Connexion testée et fonctionnelle\n";
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
    echo "1. Vérifiez que MySQL est démarré\n";
    echo "2. Vérifiez que l'utilisateur root a les permissions\n";
    echo "3. Essayez avec le mot de passe root de votre XAMPP\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

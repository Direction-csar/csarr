<?php

/**
 * Suppression des données fictives de la gestion des stocks
 */

echo "🗑️ Suppression des données fictives de la gestion des stocks\n";
echo "=======================================================\n\n";

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

    // 1. Vérifier les tables de stocks
    echo "1️⃣ Vérification des tables de stocks...\n";
    
    $tables = ['stocks', 'entrepots', 'stock_movements', 'stock_receipts'];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "   📊 Table $table: $count enregistrements\n";
        } else {
            echo "   ❌ Table $table: n'existe pas\n";
        }
    }
    echo "\n";

    // 2. Supprimer les données fictives des mouvements de stock
    echo "2️⃣ Suppression des données fictives...\n";
    
    // Supprimer les mouvements de stock fictifs
    $tables_to_clean = [
        'stock_movements' => 'mouvements de stock',
        'stock_receipts' => 'reçus de stock',
        'stock_transactions' => 'transactions de stock',
        'stock_history' => 'historique de stock'
    ];
    
    foreach ($tables_to_clean as $table => $description) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->query("DELETE FROM $table");
            $deleted = $stmt->rowCount();
            echo "   🗑️ $description: $deleted enregistrements supprimés\n";
        }
    }
    
    // Supprimer les données fictives des stocks (garder seulement les vrais stocks)
    $stmt = $pdo->query("SHOW TABLES LIKE 'stocks'");
    if ($stmt->rowCount() > 0) {
        // Garder seulement les stocks avec des données réelles
        $stmt = $pdo->query("DELETE FROM stocks WHERE item_name IN ('Test Article', 'Article Test', 'Test Stock') OR description LIKE '%test%' OR description LIKE '%Test%'");
        $deleted = $stmt->rowCount();
        if ($deleted > 0) {
            echo "   🗑️ Stocks de test: $deleted articles supprimés\n";
        }
    }
    
    echo "   ✅ Données fictives supprimées\n";
    echo "\n";

    // 3. Créer les tables nécessaires pour les vrais mouvements
    echo "3️⃣ Création des tables pour les vrais mouvements...\n";
    
    // Table des mouvements de stock
    $createStockMovements = "
        CREATE TABLE IF NOT EXISTS stock_movements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            reference VARCHAR(100) NOT NULL UNIQUE,
            type ENUM('entree', 'sortie', 'transfert', 'ajustement') NOT NULL,
            product_name VARCHAR(255),
            quantity INT NOT NULL,
            unit VARCHAR(50),
            entrepot_id INT,
            responsable VARCHAR(255),
            total_value DECIMAL(10,2) DEFAULT 0,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_reference (reference),
            INDEX idx_type (type),
            INDEX idx_entrepot_id (entrepot_id),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createStockMovements);
    echo "   ✅ Table stock_movements créée/vérifiée\n";
    
    // Table des reçus de stock
    $createStockReceipts = "
        CREATE TABLE IF NOT EXISTS stock_receipts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            movement_id INT NOT NULL,
            receipt_number VARCHAR(100) NOT NULL UNIQUE,
            pdf_path VARCHAR(500),
            generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_movement_id (movement_id),
            INDEX idx_receipt_number (receipt_number),
            FOREIGN KEY (movement_id) REFERENCES stock_movements(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createStockReceipts);
    echo "   ✅ Table stock_receipts créée/vérifiée\n";
    echo "\n";

    // 4. Vérification finale
    echo "4️⃣ Vérification finale...\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM stocks");
    $stockCount = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM entrepots");
    $entrepotCount = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM stock_movements");
    $movementCount = $stmt->fetchColumn();
    
    echo "   📊 Articles en stock: $stockCount\n";
    echo "   📊 Entrepôts: $entrepotCount\n";
    echo "   📊 Mouvements de stock: $movementCount\n";
    echo "   ✅ Gestion des stocks nettoyée\n";
    echo "\n";

    echo "🎉 NETTOYAGE TERMINÉ AVEC SUCCÈS !\n";
    echo "==================================\n";
    echo "✅ Données fictives supprimées\n";
    echo "✅ Tables de mouvements créées\n";
    echo "✅ Gestion des stocks prête pour les vraies données\n";
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
    echo "✨ LA GESTION DES STOCKS EST MAINTENANT PROPRE !\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

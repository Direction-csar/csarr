<?php

/**
 * Correction des reçus de stock et création des tables
 */

echo "🔧 Correction des reçus de stock\n";
echo "==============================\n\n";

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

    // 1. Créer la table stock_receipts sans clé étrangère d'abord
    echo "1️⃣ Création de la table stock_receipts...\n";
    
    $createStockReceipts = "
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
    
    $pdo->exec($createStockReceipts);
    echo "   ✅ Table stock_receipts créée\n";

    // 2. Vérifier les tables existantes
    echo "2️⃣ Vérification des tables...\n";
    
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

    // 3. Nettoyer les données fictives
    echo "3️⃣ Nettoyage des données fictives...\n";
    
    // Supprimer les mouvements fictifs
    $stmt = $pdo->query("DELETE FROM stock_movements WHERE reference LIKE 'ENT-2024-%' OR reference LIKE 'SOR-2024-%' OR reference LIKE 'TRA-2024-%' OR reference LIKE 'AJU-2024-%'");
    $deleted = $stmt->rowCount();
    echo "   🗑️ Mouvements fictifs supprimés: $deleted\n";
    
    // Supprimer les reçus fictifs
    $stmt = $pdo->query("DELETE FROM stock_receipts WHERE receipt_number LIKE 'REC-2024-%'");
    $deleted = $stmt->rowCount();
    echo "   🗑️ Reçus fictifs supprimés: $deleted\n";
    
    echo "   ✅ Données fictives nettoyées\n";
    echo "\n";

    // 4. Vérification finale
    echo "4️⃣ Vérification finale...\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM stocks");
    $stockCount = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM entrepots");
    $entrepotCount = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM stock_movements");
    $movementCount = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM stock_receipts");
    $receiptCount = $stmt->fetchColumn();
    
    echo "   📊 Articles en stock: $stockCount\n";
    echo "   📊 Entrepôts: $entrepotCount\n";
    echo "   📊 Mouvements de stock: $movementCount\n";
    echo "   📊 Reçus de stock: $receiptCount\n";
    echo "   ✅ Tables de stock opérationnelles\n";
    echo "\n";

    echo "🎉 CORRECTION TERMINÉE AVEC SUCCÈS !\n";
    echo "====================================\n";
    echo "✅ Tables de reçus créées\n";
    echo "✅ Données fictives supprimées\n";
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
    echo "✨ LA GESTION DES STOCKS EST MAINTENANT PROPRE !\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

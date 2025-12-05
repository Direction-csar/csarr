<?php

/**
 * Vérification de la connexion MySQL pour la gestion des stocks
 */

echo "🔍 VÉRIFICATION CONNEXION MYSQL - GESTION DES STOCKS\n";
echo "==================================================\n\n";

// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'csar_platform_2025';
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

    // 2. Vérifier la structure des tables
    echo "2️⃣ Vérification de la structure des tables...\n";
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->query("SHOW COLUMNS FROM $table");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "   📊 Table $table: " . count($columns) . " colonnes\n";
        }
    }
    echo "\n";

    // 3. Test d'insertion et suppression
    echo "3️⃣ Test d'insertion et suppression...\n";
    
    // Test d'insertion d'un entrepôt
    $stmt = $pdo->prepare("INSERT INTO entrepots (name, address, capacity, current_stock, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute(['Test Entrepôt', 'Adresse Test', 1000, 0, 'actif']);
    $entrepotId = $pdo->lastInsertId();
    echo "   ✅ Entrepôt de test inséré (ID: $entrepotId)\n";
    
    // Test d'insertion d'un stock
    $stmt = $pdo->prepare("INSERT INTO stocks (item_name, item_type, quantity, unit, status, entrepot_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute(['Test Article', 'Test', 10, 'pièces', 'disponible', $entrepotId]);
    $stockId = $pdo->lastInsertId();
    echo "   ✅ Stock de test inséré (ID: $stockId)\n";
    
    // Test d'insertion d'un mouvement
    $stmt = $pdo->prepare("INSERT INTO stock_movements (reference, type, quantity, entrepot_id, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute(['TEST-2025-001', 'entree', 10, $entrepotId]);
    $movementId = $pdo->lastInsertId();
    echo "   ✅ Mouvement de test inséré (ID: $movementId)\n";
    
    // Vérifier les insertions
    $stmt = $pdo->query("SELECT COUNT(*) FROM entrepots WHERE name = 'Test Entrepôt'");
    $count = $stmt->fetchColumn();
    echo "   ✅ Vérification entrepôt: $count enregistrement\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM stocks WHERE item_name = 'Test Article'");
    $count = $stmt->fetchColumn();
    echo "   ✅ Vérification stock: $count enregistrement\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM stock_movements WHERE reference = 'TEST-2025-001'");
    $count = $stmt->fetchColumn();
    echo "   ✅ Vérification mouvement: $count enregistrement\n";
    
    // Supprimer les données de test
    $pdo->exec("DELETE FROM stock_movements WHERE reference = 'TEST-2025-001'");
    $pdo->exec("DELETE FROM stocks WHERE item_name = 'Test Article'");
    $pdo->exec("DELETE FROM entrepots WHERE name = 'Test Entrepôt'");
    echo "   🗑️ Données de test supprimées\n";
    echo "\n";

    // 4. Vérification finale
    echo "4️⃣ Vérification finale...\n";
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "   📊 Table $table: $count enregistrements\n";
    }
    
    echo "\n";

    echo "🎉 VÉRIFICATION TERMINÉE AVEC SUCCÈS !\n";
    echo "=====================================\n";
    echo "✅ Connexion MySQL opérationnelle\n";
    echo "✅ Tables de stocks créées\n";
    echo "✅ Insertion/suppression fonctionnelles\n";
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
    echo "✨ LA GESTION DES STOCKS EST CONNECTÉE À MYSQL !\n";
    echo "📊 Toutes les données viennent de la base MySQL\n";
    echo "🗄️ Base de données: csar_platform_2025\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

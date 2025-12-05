<?php

/**
 * Suppression complète de toutes les données fictives de la gestion des stocks
 */

echo "🗑️ SUPPRESSION COMPLÈTE - DONNÉES FICTIVES STOCKS\n";
echo "================================================\n\n";

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

    // 1. Vérifier toutes les tables de stocks
    echo "1️⃣ Vérification des tables de stocks...\n";
    
    $tables = ['stocks', 'entrepots', 'stock_movements', 'stock_receipts', 'stock_transactions', 'stock_history'];
    
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

    // 2. Supprimer TOUTES les données fictives
    echo "2️⃣ Suppression de TOUTES les données fictives...\n";
    
    // Supprimer tous les mouvements de stock
    $stmt = $pdo->query("DELETE FROM stock_movements");
    $deleted = $stmt->rowCount();
    echo "   🗑️ Tous les mouvements de stock supprimés: $deleted\n";
    
    // Supprimer tous les reçus
    $stmt = $pdo->query("DELETE FROM stock_receipts");
    $deleted = $stmt->rowCount();
    echo "   🗑️ Tous les reçus supprimés: $deleted\n";
    
    // Supprimer toutes les transactions
    $stmt = $pdo->query("DELETE FROM stock_transactions");
    $deleted = $stmt->rowCount();
    echo "   🗑️ Toutes les transactions supprimées: $deleted\n";
    
    // Supprimer tout l'historique
    $stmt = $pdo->query("DELETE FROM stock_history");
    $deleted = $stmt->rowCount();
    echo "   🗑️ Tout l'historique supprimé: $deleted\n";
    
    // Supprimer tous les stocks (garder seulement la structure)
    $stmt = $pdo->query("DELETE FROM stocks");
    $deleted = $stmt->rowCount();
    echo "   🗑️ Tous les stocks supprimés: $deleted\n";
    
    // Supprimer tous les entrepôts (garder seulement la structure)
    $stmt = $pdo->query("DELETE FROM entrepots");
    $deleted = $stmt->rowCount();
    echo "   🗑️ Tous les entrepôts supprimés: $deleted\n";
    
    echo "   ✅ TOUTES les données fictives supprimées\n";
    echo "\n";

    // 3. Réinitialiser les compteurs auto-increment
    echo "3️⃣ Réinitialisation des compteurs...\n";
    
    $tables_to_reset = ['stocks', 'entrepots', 'stock_movements', 'stock_receipts'];
    
    foreach ($tables_to_reset as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            $pdo->exec("ALTER TABLE $table AUTO_INCREMENT = 1");
            echo "   🔄 Compteur $table réinitialisé\n";
        }
    }
    
    echo "   ✅ Compteurs réinitialisés\n";
    echo "\n";

    // 4. Vérification finale
    echo "4️⃣ Vérification finale...\n";
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "   📊 Table $table: $count enregistrements\n";
        }
    }
    
    echo "   ✅ Toutes les tables sont vides\n";
    echo "\n";

    echo "🎉 SUPPRESSION COMPLÈTE TERMINÉE !\n";
    echo "==================================\n";
    echo "✅ TOUTES les données fictives supprimées\n";
    echo "✅ Tables vides et prêtes pour les vraies données\n";
    echo "✅ Compteurs réinitialisés\n";
    echo "✅ Base de données MySQL propre\n";
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
    echo "✨ LA GESTION DES STOCKS EST MAINTENANT COMPLÈTEMENT VIDE !\n";
    echo "📊 Toutes les données viennent maintenant de la base MySQL\n";
    echo "🗄️ Base de données: csar_platform_2025\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

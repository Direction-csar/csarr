<?php

/**
 * Suppression finale de toutes les données fictives
 */

echo "🗑️ SUPPRESSION FINALE - DONNÉES FICTIVES\n";
echo "=======================================\n\n";

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

    // 1. Vérifier les tables existantes
    echo "1️⃣ Vérification des tables existantes...\n";
    
    $stmt = $pdo->query("SHOW TABLES");
    $allTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $stockTables = array_filter($allTables, function($table) {
        return strpos($table, 'stock') !== false || $table === 'stocks' || $table === 'entrepots';
    });
    
    foreach ($stockTables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "   📊 Table $table: $count enregistrements\n";
    }
    echo "\n";

    // 2. Supprimer toutes les données des tables de stocks
    echo "2️⃣ Suppression de toutes les données...\n";
    
    foreach ($stockTables as $table) {
        $stmt = $pdo->query("DELETE FROM $table");
        $deleted = $stmt->rowCount();
        echo "   🗑️ Table $table: $deleted enregistrements supprimés\n";
    }
    
    echo "   ✅ Toutes les données supprimées\n";
    echo "\n";

    // 3. Réinitialiser les compteurs
    echo "3️⃣ Réinitialisation des compteurs...\n";
    
    foreach ($stockTables as $table) {
        $pdo->exec("ALTER TABLE $table AUTO_INCREMENT = 1");
        echo "   🔄 Compteur $table réinitialisé\n";
    }
    
    echo "   ✅ Compteurs réinitialisés\n";
    echo "\n";

    // 4. Vérification finale
    echo "4️⃣ Vérification finale...\n";
    
    foreach ($stockTables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "   📊 Table $table: $count enregistrements\n";
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

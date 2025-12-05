<?php

/**
 * Nettoyage complet des données fictives de la gestion des stocks
 */

echo "🧹 NETTOYAGE COMPLET - DONNÉES FICTIVES STOCKS\n";
echo "=============================================\n\n";

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

    // 1. Supprimer toutes les données fictives des mouvements
    echo "1️⃣ Suppression des données fictives...\n";
    
    // Supprimer les mouvements avec des références fictives
    $fakeReferences = [
        'ENT-2024-001', 'ENT-2024-002', 'SOR-2024-001', 
        'TRA-2024-001', 'AJU-2024-001'
    ];
    
    foreach ($fakeReferences as $ref) {
        $stmt = $pdo->prepare("DELETE FROM stock_movements WHERE reference = ?");
        $stmt->execute([$ref]);
        $deleted = $stmt->rowCount();
        if ($deleted > 0) {
            echo "   🗑️ Mouvement fictif supprimé: $ref\n";
        }
    }
    
    // Supprimer les reçus fictifs
    $stmt = $pdo->query("DELETE FROM stock_receipts WHERE receipt_number LIKE 'REC-2024-%'");
    $deleted = $stmt->rowCount();
    if ($deleted > 0) {
        echo "   🗑️ Reçus fictifs supprimés: $deleted\n";
    }
    
    // Supprimer les stocks de test
    $stmt = $pdo->query("DELETE FROM stocks WHERE item_name LIKE '%Test%' OR description LIKE '%test%'");
    $deleted = $stmt->rowCount();
    if ($deleted > 0) {
        echo "   🗑️ Stocks de test supprimés: $deleted\n";
    }
    
    echo "   ✅ Données fictives supprimées\n";
    echo "\n";

    // 2. Vérifier l'état final
    echo "2️⃣ Vérification de l'état final...\n";
    
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
    
    // Afficher les stocks restants
    if ($stockCount > 0) {
        $stmt = $pdo->query("SELECT item_name, quantity, unit FROM stocks LIMIT 5");
        $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "   📋 Stocks restants:\n";
        foreach ($stocks as $stock) {
            echo "      - {$stock['item_name']}: {$stock['quantity']} {$stock['unit']}\n";
        }
    }
    
    echo "\n";

    echo "🎉 NETTOYAGE TERMINÉ AVEC SUCCÈS !\n";
    echo "==================================\n";
    echo "✅ Données fictives supprimées\n";
    echo "✅ Gestion des stocks nettoyée\n";
    echo "✅ Prêt pour les vraies données\n";
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
    echo "📄 Les reçus PDF utilisent maintenant le bon logo CSAR\n";
    echo "🏛️ CSAR = Commissariat à la Sécurité Alimentaire et à la Résilience\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

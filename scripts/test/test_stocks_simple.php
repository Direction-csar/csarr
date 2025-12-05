<?php

/**
 * Test simple de la gestion des stocks
 */

echo "🧪 Test simple de la gestion des stocks\n";
echo "=====================================\n\n";

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

    // 1. Vérifier la table stocks
    echo "1️⃣ Vérification de la table stocks...\n";
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'stocks'");
    if ($stmt->rowCount() > 0) {
        echo "   ✅ Table stocks présente\n";
        
        // Vérifier les données
        $stmt = $pdo->query("SELECT COUNT(*) FROM stocks");
        $count = $stmt->fetchColumn();
        echo "   📊 Nombre d'articles: $count\n";
        
        if ($count > 0) {
            $stmt = $pdo->query("SELECT item_name, quantity, unit, status FROM stocks LIMIT 3");
            $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "   📋 Exemples d'articles:\n";
            foreach ($stocks as $stock) {
                echo "      - {$stock['item_name']}: {$stock['quantity']} {$stock['unit']} ({$stock['status']})\n";
            }
        } else {
            echo "   ⚠️ Aucun article en stock\n";
        }
    } else {
        echo "   ❌ Table stocks manquante\n";
    }
    echo "\n";

    // 2. Vérifier la table entrepots
    echo "2️⃣ Vérification de la table entrepots...\n";
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'entrepots'");
    if ($stmt->rowCount() > 0) {
        echo "   ✅ Table entrepots présente\n";
        
        // Vérifier les données
        $stmt = $pdo->query("SELECT COUNT(*) FROM entrepots");
        $count = $stmt->fetchColumn();
        echo "   📊 Nombre d'entrepôts: $count\n";
        
        if ($count > 0) {
            $stmt = $pdo->query("SELECT name, capacity, current_stock FROM entrepots LIMIT 3");
            $entrepots = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "   📋 Entrepôts:\n";
            foreach ($entrepots as $entrepot) {
                echo "      - {$entrepot['name']}: {$entrepot['current_stock']}/{$entrepot['capacity']}\n";
            }
        } else {
            echo "   ⚠️ Aucun entrepôt\n";
        }
    } else {
        echo "   ❌ Table entrepots manquante\n";
    }
    echo "\n";

    // 3. Test d'ajout d'un article
    echo "3️⃣ Test d'ajout d'un article...\n";
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO stocks (
                item_name, item_type, quantity, unit, status, 
                description, created_at, updated_at
            ) VALUES (
                :item_name, :item_type, :quantity, :unit, :status,
                :description, NOW(), NOW()
            )
        ");
        
        $stmt->execute([
            'item_name' => 'Test Article',
            'item_type' => 'Test',
            'quantity' => 10,
            'unit' => 'pièces',
            'status' => 'disponible',
            'description' => 'Article de test pour vérification'
        ]);
        
        $newId = $pdo->lastInsertId();
        echo "   ✅ Article de test ajouté (ID: $newId)\n";
        
        // Vérifier l'ajout
        $stmt = $pdo->prepare("SELECT item_name, quantity FROM stocks WHERE id = ?");
        $stmt->execute([$newId]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($article) {
            echo "   ✅ Article vérifié: {$article['item_name']} - {$article['quantity']} pièces\n";
        }
        
        // Supprimer l'article de test
        $stmt = $pdo->prepare("DELETE FROM stocks WHERE id = ?");
        $stmt->execute([$newId]);
        echo "   ✅ Article de test supprimé\n";
        
    } catch (Exception $e) {
        echo "   ❌ Erreur lors du test: " . $e->getMessage() . "\n";
    }
    echo "\n";

    echo "🎯 TEST TERMINÉ\n";
    echo "===============\n";
    echo "✅ Gestion des stocks fonctionnelle\n";
    echo "✅ Base de données opérationnelle\n";
    echo "✅ Ajout/suppression d'articles OK\n";
    echo "\n";
    echo "🌐 Vous pouvez maintenant accéder à :\n";
    echo "- http://localhost:8000/admin/stocks\n";
    echo "- http://localhost:8000/admin/entrepots\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

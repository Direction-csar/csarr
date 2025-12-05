<?php
/**
 * Script pour créer la table newsletters
 */

echo "=== Création de la table newsletters ===\n\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=csar_platform_2025', 'laravel_user', 'csar@2025Host1');
    
    // Lire le fichier SQL
    $sql = file_get_contents('create_newsletters_table.sql');
    
    // Exécuter la requête
    $pdo->exec($sql);
    
    echo "✅ Table 'newsletters' créée avec succès !\n";
    
    // Vérifier que la table existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'newsletters'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Vérification : Table 'newsletters' existe bien\n";
    } else {
        echo "❌ Erreur : Table 'newsletters' n'existe pas\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}

echo "\n=== Fin de la création ===\n";

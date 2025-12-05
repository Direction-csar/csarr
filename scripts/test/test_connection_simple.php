<?php

echo "🔍 Test de connexion simple\n";
echo "==========================\n\n";

// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'csar_platform_2025';
$db_user = 'laravel_user';
$db_pass = 'csar@2025Host1';

try {
    // Test de connexion
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données réussie\n";
    
    // Test simple
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch();
    echo "✅ Test de requête réussi: " . $result['test'] . "\n";
    
    // Vérifier les tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "✅ Tables trouvées: " . count($tables) . "\n";
    
    if (in_array('stocks', $tables)) {
        echo "✅ Table stocks présente\n";
    } else {
        echo "❌ Table stocks manquante\n";
    }
    
    if (in_array('entrepots', $tables)) {
        echo "✅ Table entrepots présente\n";
    } else {
        echo "❌ Table entrepots manquante\n";
    }
    
    echo "\n🎉 Connexion OK - Prêt pour la correction !\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
    echo "\n🔧 Vérifiez:\n";
    echo "1. XAMPP est démarré\n";
    echo "2. MySQL est actif\n";
    echo "3. Base de données csar_platform_2025 existe\n";
    echo "4. Utilisateur laravel_user existe\n";
}

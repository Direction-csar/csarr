<?php

// Script pour supprimer toutes les données fictives de la plateforme CSAR

$host = '127.0.0.1';
$dbname = 'csar_platform';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données réussie\n";
    
    // Liste des tables à vider (données fictives)
    $tablesToClear = [
        'sim_reports',
        'news', 
        'newsletters',
        'public_contents',
        'speeches',
        'gallery_images',
        'contact_messages',
        'public_requests'
    ];
    
    foreach ($tablesToClear as $table) {
        try {
            // Vérifier si la table existe
            $checkTable = $pdo->query("SHOW TABLES LIKE '$table'")->fetch();
            
            if ($checkTable) {
                $pdo->exec("TRUNCATE TABLE $table");
                echo "✅ Table '$table' vidée avec succès\n";
            } else {
                echo "⚠️  Table '$table' n'existe pas\n";
            }
        } catch (PDOException $e) {
            echo "❌ Erreur avec la table '$table': " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n🎉 Toutes les données fictives ont été supprimées !\n";
    echo "Votre plateforme CSAR affichera maintenant les messages par défaut.\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion à la base de données : " . $e->getMessage() . "\n";
}

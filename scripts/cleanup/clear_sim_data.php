<?php

// Script pour supprimer les données fictives des rapports SIM
require __DIR__.'/vendor/autoload.php';

// Configuration de la base de données directement
$config = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'database' => 'csar_platform',
    'username' => 'root',
    'password' => ''
];

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4", 
        $config['username'], 
        $config['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données 'csar_platform' réussie\n";
    
    // Supprimer les données fictives des rapports SIM
    $result = $pdo->exec("DELETE FROM sim_reports");
    echo "✅ Supprimé $result enregistrement(s) de la table sim_reports\n";
    
    // Supprimer les données fictives des actualités
    $result = $pdo->exec("DELETE FROM news");
    echo "✅ Supprimé $result enregistrement(s) de la table news\n";
    
    // Supprimer les données fictives des newsletters
    $result = $pdo->exec("DELETE FROM newsletters");
    echo "✅ Supprimé $result enregistrement(s) de la table newsletters\n";
    
    // Supprimer les données fictives des messages de contact
    $result = $pdo->exec("DELETE FROM contact_messages");
    echo "✅ Supprimé $result enregistrement(s) de la table contact_messages\n";
    
    // Supprimer les données fictives des demandes publiques
    $result = $pdo->exec("DELETE FROM public_requests");
    echo "✅ Supprimé $result enregistrement(s) de la table public_requests\n";
    
    echo "\n🎉 Toutes les données fictives ont été supprimées !\n";
    echo "La page SIM affichera maintenant : 'Aucune donnée disponible pour le moment'\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré et que la base 'csar_platform' existe.\n";
}

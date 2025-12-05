<?php

/**
 * Script pour nettoyer la base de données CSAR
 * Supprime toutes les données de test et prépare la plateforme pour les tests manuels
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

// Configuration de la base de données
$config = [
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'port' => '3306',
    'database' => 'csar',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];

try {
    // Connexion à la base de données
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}",
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    echo "🔧 Nettoyage de la base de données CSAR...\n\n";

    // Désactiver les contraintes de clés étrangères temporairement
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // Liste des tables à nettoyer (garder les tables de structure)
    $tablesToClean = [
        'demandes',
        'stock_movements', 
        'stocks',
        'warehouses',
        'notifications',
        'messages',
        'users' // On garde seulement l'admin
    ];

    foreach ($tablesToClean as $table) {
        try {
            if ($table === 'users') {
                // Garder seulement l'utilisateur admin
                $pdo->exec("DELETE FROM users WHERE email != 'admin@csar.sn'");
                echo "✅ Table 'users' nettoyée (admin conservé)\n";
            } else {
                $pdo->exec("TRUNCATE TABLE {$table}");
                echo "✅ Table '{$table}' vidée\n";
            }
        } catch (PDOException $e) {
            echo "⚠️  Table '{$table}' non trouvée ou erreur: " . $e->getMessage() . "\n";
        }
    }

    // Réactiver les contraintes de clés étrangères
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    // Vérifier l'état de la base
    echo "\n📊 État de la base de données après nettoyage:\n";
    echo "==========================================\n";
    
    foreach ($tablesToClean as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM {$table}");
            $count = $stmt->fetch()['count'];
            echo "📋 {$table}: {$count} enregistrement(s)\n";
        } catch (PDOException $e) {
            echo "❌ {$table}: Table non accessible\n";
        }
    }

    echo "\n🎉 Nettoyage terminé avec succès!\n";
    echo "💡 Votre plateforme CSAR est maintenant prête pour les tests manuels.\n";
    echo "🔐 Connexion admin: admin@csar.sn / password\n\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion à la base de données: " . $e->getMessage() . "\n";
    echo "💡 Vérifiez que MySQL est démarré et que la base 'csar' existe.\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

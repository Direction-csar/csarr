<?php
/**
 * 🔍 VÉRIFICATION DES TABLES MYSQL
 * 
 * Ce script vérifie quelles tables existent dans la base de données
 */

require_once 'vendor/autoload.php';

// Configuration de la base de données
$config = [
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'plateforme-csar',
    'username' => 'root',
    'password' => '', // Mot de passe MySQL de XAMPP
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

    echo "🔗 Connexion à la base de données réussie\n";
    echo "🔍 VÉRIFICATION DES TABLES MYSQL\n";
    echo "===============================\n\n";

    // Lister toutes les tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "📋 Tables existantes dans la base de données :\n";
    foreach ($tables as $table) {
        echo "   - {$table}\n";
    }
    echo "\n";

    // Vérifier les tables importantes
    $importantTables = [
        'users' => 'Utilisateurs',
        'public_requests' => 'Demandes publiques',
        'personnel' => 'Personnel',
        'personnels' => 'Personnels',
        'demandes' => 'Demandes',
        'notifications' => 'Notifications',
        'messages' => 'Messages',
        'news' => 'Actualités',
        'warehouses' => 'Entrepôts',
        'stock_movements' => 'Mouvements de stock',
        'sim_reports' => 'Rapports SIM'
    ];

    echo "🔍 Vérification des tables importantes :\n";
    foreach ($importantTables as $table => $description) {
        if (in_array($table, $tables)) {
            $count = $pdo->query("SELECT COUNT(*) as count FROM {$table}")->fetch()['count'];
            echo "   ✅ {$description} ({$table}): {$count} enregistrements\n";
        } else {
            echo "   ❌ {$description} ({$table}): Table non trouvée\n";
        }
    }
    echo "\n";

    // Vérifier la structure de la table users
    if (in_array('users', $tables)) {
        echo "👥 Structure de la table users :\n";
        $stmt = $pdo->query("DESCRIBE users");
        $columns = $stmt->fetchAll();
        foreach ($columns as $column) {
            echo "   - {$column['Field']} ({$column['Type']})\n";
        }
        echo "\n";
    }

    // Vérifier la structure de la table public_requests
    if (in_array('public_requests', $tables)) {
        echo "📋 Structure de la table public_requests :\n";
        $stmt = $pdo->query("DESCRIBE public_requests");
        $columns = $stmt->fetchAll();
        foreach ($columns as $column) {
            echo "   - {$column['Field']} ({$column['Type']})\n";
        }
        echo "\n";
    }

} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    echo "🔧 Vérifiez la configuration de la base de données\n";
}

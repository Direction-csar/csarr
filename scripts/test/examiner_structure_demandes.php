<?php
/**
 * 🔍 EXAMINER LA STRUCTURE DE LA TABLE DEMANDES
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
    echo "🔍 EXAMEN DE LA STRUCTURE DE LA TABLE DEMANDES\n";
    echo "==============================================\n\n";

    // Examiner la structure de la table demandes
    echo "📋 Structure de la table demandes :\n";
    $stmt = $pdo->query("DESCRIBE demandes");
    $columns = $stmt->fetchAll();
    foreach ($columns as $column) {
        echo "   - {$column['Field']} ({$column['Type']})\n";
    }
    echo "\n";

    // Examiner le contenu de la table demandes
    echo "📋 Contenu de la table demandes :\n";
    $stmt = $pdo->query("SELECT * FROM demandes LIMIT 5");
    $demandes = $stmt->fetchAll();
    
    if (empty($demandes)) {
        echo "   ✅ Table demandes vide\n";
    } else {
        echo "   📊 Exemples d'enregistrements :\n";
        foreach ($demandes as $demande) {
            echo "   - ID: {$demande['id']}\n";
            foreach ($demande as $key => $value) {
                if ($key !== 'id') {
                    echo "     {$key}: {$value}\n";
                }
            }
            echo "   ---\n";
        }
    }
    echo "\n";

    // Examiner la structure de la table public_requests
    echo "📋 Structure de la table public_requests :\n";
    $stmt = $pdo->query("DESCRIBE public_requests");
    $columns = $stmt->fetchAll();
    foreach ($columns as $column) {
        echo "   - {$column['Field']} ({$column['Type']})\n";
    }
    echo "\n";

    // Examiner le contenu de la table public_requests
    echo "📋 Contenu de la table public_requests :\n";
    $stmt = $pdo->query("SELECT * FROM public_requests LIMIT 5");
    $publicRequests = $stmt->fetchAll();
    
    if (empty($publicRequests)) {
        echo "   ✅ Table public_requests vide\n";
    } else {
        echo "   📊 Exemples d'enregistrements :\n";
        foreach ($publicRequests as $request) {
            echo "   - ID: {$request['id']}\n";
            foreach ($request as $key => $value) {
                if ($key !== 'id') {
                    echo "     {$key}: {$value}\n";
                }
            }
            echo "   ---\n";
        }
    }

} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    echo "🔧 Vérifiez la configuration de la base de données\n";
}

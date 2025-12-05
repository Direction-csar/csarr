<?php
/**
 * Diagnostic simple des pages CSAR
 */

echo "=== Diagnostic CSAR ===\n\n";

// Test 1: Vérification de la base de données
echo "1. Test de connexion à la base de données...\n";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=csar_platform_2025', 'laravel_user', 'csar@2025Host1');
    echo "   ✅ Connexion à la base de données réussie\n";
} catch (PDOException $e) {
    echo "   ❌ Erreur de connexion à la base: " . $e->getMessage() . "\n";
}

// Test 2: Vérification des tables
echo "\n2. Vérification des tables...\n";
try {
    $tables = ['users', 'messages', 'newsletter_subscribers', 'sim_reports'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "   ✅ Table '$table' existe\n";
        } else {
            echo "   ❌ Table '$table' manquante\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ Erreur lors de la vérification des tables: " . $e->getMessage() . "\n";
}

// Test 3: Test simple HTTP
echo "\n3. Test HTTP simple...\n";
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 5
    ]
]);

$urls = [
    'http://localhost:8000/admin/login' => 'Connexion Admin',
    'http://localhost:8000/sim-reports' => 'SIM Reports'
];

foreach ($urls as $url => $name) {
    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        echo "   ❌ $name: Erreur HTTP\n";
    } else {
        $status = http_response_code();
        echo "   ✅ $name: HTTP $status\n";
    }
}

echo "\n=== Fin du diagnostic ===\n";

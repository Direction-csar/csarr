<?php
/**
 * Test final des pages CSAR corrigées
 */

echo "=== Test Final des Pages CSAR ===\n\n";

// Configuration
$base_url = 'http://localhost:8000';
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'User-Agent: CSAR Test Script',
        'timeout' => 10
    ]
]);

// Fonction de test
function testPage($url, $name, $expected_content = null) {
    global $context;
    
    echo "Test: $name\n";
    echo "URL: $url\n";
    
    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        echo "   ❌ Erreur: Impossible d'accéder à la page\n";
        echo "   Erreur: " . error_get_last()['message'] . "\n";
        return false;
    }
    
    $status = http_response_code();
    echo "   Status HTTP: $status\n";
    
    if ($status == 200) {
        if ($expected_content && strpos($response, $expected_content) !== false) {
            echo "   ✅ Page accessible avec contenu attendu\n";
            return true;
        } elseif (!$expected_content) {
            echo "   ✅ Page accessible\n";
            return true;
        } else {
            echo "   ⚠️  Page accessible mais contenu inattendu\n";
            return false;
        }
    } else {
        echo "   ❌ Erreur HTTP: $status\n";
        return false;
    }
}

// Tests des pages
$tests = [
    // Pages publiques
    ['/admin/login', 'Page de connexion Admin', 'login'],
    ['/sim-reports', 'Page SIM Reports Public', 'Rapports SIM'],
    
    // Pages admin (nécessitent authentification)
    ['/admin/communication', 'Page Communication Admin', 'Communication'],
    ['/admin/newsletter', 'Page Newsletter Admin', 'Newsletter'],
];

$results = [];
foreach ($tests as $test) {
    $url = $base_url . $test[0];
    $result = testPage($url, $test[1], $test[2]);
    $results[] = $result;
    echo "\n";
}

// Résumé
echo "=== Résumé des Tests ===\n";
$success_count = array_sum($results);
$total_count = count($results);
echo "Tests réussis: $success_count/$total_count\n";

if ($success_count == $total_count) {
    echo "🎉 Toutes les pages fonctionnent correctement !\n";
} else {
    echo "⚠️  Certaines pages nécessitent encore des corrections.\n";
}

echo "\n=== Fin des tests ===\n";

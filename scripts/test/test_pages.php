<?php
/**
 * Script de test pour vérifier les pages qui ne fonctionnent pas
 */

echo "=== Test des pages CSAR ===\n\n";

// Test 1: Communication Admin
echo "1. Test de la page Communication Admin...\n";
$url = 'http://localhost:8000/admin/communication';
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'User-Agent: Test Script',
        'timeout' => 10
    ]
]);

$response = @file_get_contents($url, false, $context);
if ($response === false) {
    echo "   ❌ Erreur: Impossible d'accéder à la page Communication Admin\n";
    echo "   Erreur: " . error_get_last()['message'] . "\n";
} else {
    if (strpos($response, 'Communication') !== false) {
        echo "   ✅ Page Communication Admin accessible\n";
    } else {
        echo "   ⚠️  Page accessible mais contenu inattendu\n";
    }
}

// Test 2: Newsletter Admin
echo "\n2. Test de la page Newsletter Admin...\n";
$url = 'http://localhost:8000/admin/newsletter';
$response = @file_get_contents($url, false, $context);
if ($response === false) {
    echo "   ❌ Erreur: Impossible d'accéder à la page Newsletter Admin\n";
    echo "   Erreur: " . error_get_last()['message'] . "\n";
} else {
    if (strpos($response, 'Newsletter') !== false) {
        echo "   ✅ Page Newsletter Admin accessible\n";
    } else {
        echo "   ⚠️  Page accessible mais contenu inattendu\n";
    }
}

// Test 3: SIM Reports Public
echo "\n3. Test de la page SIM Reports Public...\n";
$url = 'http://localhost:8000/sim-reports';
$response = @file_get_contents($url, false, $context);
if ($response === false) {
    echo "   ❌ Erreur: Impossible d'accéder à la page SIM Reports\n";
    echo "   Erreur: " . error_get_last()['message'] . "\n";
} else {
    if (strpos($response, 'Rapports SIM') !== false) {
        echo "   ✅ Page SIM Reports accessible\n";
    } else {
        echo "   ⚠️  Page accessible mais contenu inattendu\n";
    }
}

echo "\n=== Fin des tests ===\n";

<?php
/**
 * Script de test pour vérifier les pages avec authentification
 */

echo "=== Test des pages CSAR avec authentification ===\n\n";

// Test 1: Page d'accueil (doit fonctionner)
echo "1. Test de la page d'accueil...\n";
$url = 'http://localhost:8000/';
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'User-Agent: Test Script',
        'timeout' => 10
    ]
]);

$response = @file_get_contents($url, false, $context);
if ($response === false) {
    echo "   ❌ Erreur: Impossible d'accéder à la page d'accueil\n";
    echo "   Erreur: " . error_get_last()['message'] . "\n";
} else {
    if (strpos($response, 'CSAR') !== false || strpos($response, 'html') !== false) {
        echo "   ✅ Page d'accueil accessible\n";
    } else {
        echo "   ⚠️  Page accessible mais contenu inattendu\n";
    }
}

// Test 2: Page de connexion admin
echo "\n2. Test de la page de connexion admin...\n";
$url = 'http://localhost:8000/admin/login';
$response = @file_get_contents($url, false, $context);
if ($response === false) {
    echo "   ❌ Erreur: Impossible d'accéder à la page de connexion admin\n";
    echo "   Erreur: " . error_get_last()['message'] . "\n";
} else {
    if (strpos($response, 'login') !== false || strpos($response, 'connexion') !== false) {
        echo "   ✅ Page de connexion admin accessible\n";
    } else {
        echo "   ⚠️  Page accessible mais contenu inattendu\n";
    }
}

// Test 3: SIM Reports Public (sans authentification)
echo "\n3. Test de la page SIM Reports Public...\n";
$url = 'http://localhost:8000/sim-reports';
$response = @file_get_contents($url, false, $context);
if ($response === false) {
    echo "   ❌ Erreur: Impossible d'accéder à la page SIM Reports\n";
    echo "   Erreur: " . error_get_last()['message'] . "\n";
} else {
    if (strpos($response, 'Rapports SIM') !== false || strpos($response, 'html') !== false) {
        echo "   ✅ Page SIM Reports accessible\n";
    } else {
        echo "   ⚠️  Page accessible mais contenu inattendu\n";
        echo "   Contenu reçu: " . substr($response, 0, 200) . "...\n";
    }
}

// Test 4: Vérification des routes
echo "\n4. Vérification des routes...\n";
$routes_to_test = [
    'http://localhost:8000/fr' => 'Page française',
    'http://localhost:8000/en' => 'Page anglaise',
    'http://localhost:8000/fr/a-propos' => 'Page À propos',
];

foreach ($routes_to_test as $url => $description) {
    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        echo "   ❌ $description: Erreur\n";
    } else {
        echo "   ✅ $description: Accessible\n";
    }
}

echo "\n=== Fin des tests ===\n";

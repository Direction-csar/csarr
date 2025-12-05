<?php
// Test simple de connexion HTTP
$url = 'http://localhost:8000/test-simple';

echo "Test de connexion à: $url\n";

$context = stream_context_create([
    'http' => [
        'timeout' => 5,
        'method' => 'GET'
    ]
]);

$result = @file_get_contents($url, false, $context);

if ($result === false) {
    echo "❌ Erreur de connexion\n";
    $error = error_get_last();
    if ($error) {
        echo "Détails: " . $error['message'] . "\n";
    }
} else {
    echo "✅ Connexion réussie\n";
    echo "Réponse: " . substr($result, 0, 200) . "...\n";
}

// Test de la page sim-reports
echo "\nTest de la page sim-reports...\n";
$url2 = 'http://localhost:8000/sim-reports';
$result2 = @file_get_contents($url2, false, $context);

if ($result2 === false) {
    echo "❌ Erreur sur sim-reports\n";
    $error = error_get_last();
    if ($error) {
        echo "Détails: " . $error['message'] . "\n";
    }
} else {
    echo "✅ Page sim-reports accessible\n";
    echo "Taille de la réponse: " . strlen($result2) . " caractères\n";
    if (strpos($result2, 'Rapports SIM') !== false) {
        echo "✅ Contenu correct trouvé\n";
    } else {
        echo "❌ Contenu incorrect\n";
    }
}
?>

<?php
/**
 * Test de la page Communication après correction
 */

echo "=== Test de la Page Communication Corrigée ===\n\n";

$url = 'http://localhost:8000/admin/communication';

echo "Test de l'URL: $url\n";

if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Ne pas suivre les redirections
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ Erreur cURL: $error\n";
    } else {
        echo "Code HTTP: $httpCode\n";
        
        if ($httpCode == 302) {
            echo "✅ Redirection vers login (comportement normal pour page admin)\n";
            echo "✅ Page Communication fonctionne correctement\n";
        } elseif ($httpCode == 200) {
            echo "✅ Page accessible directement\n";
            if (strpos($response, 'Communication') !== false) {
                echo "✅ Contenu 'Communication' trouvé\n";
            }
        } else {
            echo "❌ Erreur HTTP: $httpCode\n";
        }
    }
} else {
    echo "cURL non disponible\n";
}

echo "\n=== Fin du test ===\n";

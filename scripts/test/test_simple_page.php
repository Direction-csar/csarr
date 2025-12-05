<?php
/**
 * Test simple d'une page spécifique
 */

echo "=== Test Simple de la Page Newsletter ===\n\n";

// Test avec curl pour avoir plus de détails
$url = 'http://localhost:8000/admin/newsletter';

echo "Test de l'URL: $url\n";

// Utiliser curl si disponible, sinon file_get_contents
if (function_exists('curl_init')) {
    echo "Utilisation de cURL...\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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
        echo "✅ Code HTTP: $httpCode\n";
        
        if ($httpCode == 200) {
            echo "✅ Page accessible\n";
            // Extraire le body de la réponse
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $body = substr($response, $headerSize);
            if (strpos($body, 'Newsletter') !== false) {
                echo "✅ Contenu Newsletter trouvé\n";
            } else {
                echo "⚠️  Contenu Newsletter non trouvé\n";
            }
        } else {
            echo "❌ Erreur HTTP: $httpCode\n";
            // Afficher les premiers caractères de la réponse
            echo "Réponse: " . substr($response, 0, 500) . "...\n";
        }
    }
} else {
    echo "cURL non disponible, utilisation de file_get_contents...\n";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 10
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        echo "❌ Erreur: " . error_get_last()['message'] . "\n";
    } else {
        echo "✅ Page accessible\n";
        if (strpos($response, 'Newsletter') !== false) {
            echo "✅ Contenu Newsletter trouvé\n";
        } else {
            echo "⚠️  Contenu Newsletter non trouvé\n";
        }
    }
}

echo "\n=== Fin du test ===\n";

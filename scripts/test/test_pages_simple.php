<?php
echo "=== Test des pages CSAR ===" . PHP_EOL . PHP_EOL;

$pages = [
    'Page publique SIM Reports' => 'http://localhost:8000/sim-reports',
    'Page admin Communication' => 'http://localhost:8000/admin/communication',
    'Page admin Newsletter' => 'http://localhost:8000/admin/newsletter',
    'Page admin Login' => 'http://localhost:8000/admin/login'
];

foreach ($pages as $name => $url) {
    echo "Test de : $name" . PHP_EOL;
    echo "URL : $url" . PHP_EOL;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ Erreur cURL : $error" . PHP_EOL;
    } else {
        echo "✅ Code HTTP : $httpCode" . PHP_EOL;
        if ($httpCode == 302) {
            echo "   → Redirection (normal pour les pages admin non connectées)" . PHP_EOL;
        } elseif ($httpCode == 200) {
            echo "   → Page accessible" . PHP_EOL;
        } elseif ($httpCode == 500) {
            echo "   → Erreur serveur interne" . PHP_EOL;
        }
    }
    echo PHP_EOL;
}

echo "=== Fin du test ===" . PHP_EOL;

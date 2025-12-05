<?php
/**
 * Script de débogage pour identifier l'erreur 500
 */

echo "=== Débogage Erreur 500 ===\n\n";

// Test 1: Vérifier la configuration PHP
echo "1. Configuration PHP:\n";
echo "   PHP Version: " . PHP_VERSION . "\n";
echo "   Extensions: " . (extension_loaded('pdo_mysql') ? '✅ PDO MySQL' : '❌ PDO MySQL manquant') . "\n";
echo "   Extensions: " . (extension_loaded('mbstring') ? '✅ mbstring' : '❌ mbstring manquant') . "\n";
echo "   Extensions: " . (extension_loaded('openssl') ? '✅ OpenSSL' : '❌ OpenSSL manquant') . "\n";

// Test 2: Vérifier la connexion à la base de données
echo "\n2. Test de connexion à la base de données:\n";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=csar_platform_2025', 'laravel_user', 'csar@2025Host1');
    echo "   ✅ Connexion à la base de données réussie\n";
} catch (PDOException $e) {
    echo "   ❌ Erreur de connexion: " . $e->getMessage() . "\n";
}

// Test 3: Vérifier les fichiers Laravel essentiels
echo "\n3. Vérification des fichiers Laravel:\n";
$files = [
    '.env' => 'Fichier de configuration',
    'artisan' => 'Script Artisan',
    'composer.json' => 'Configuration Composer',
    'app/Http/Kernel.php' => 'Kernel HTTP',
    'config/app.php' => 'Configuration app',
    'config/database.php' => 'Configuration base de données'
];

foreach ($files as $file => $description) {
    if (file_exists($file)) {
        echo "   ✅ $description ($file)\n";
    } else {
        echo "   ❌ $description ($file) manquant\n";
    }
}

// Test 4: Vérifier les permissions
echo "\n4. Vérification des permissions:\n";
$dirs = ['storage', 'bootstrap/cache'];
foreach ($dirs as $dir) {
    if (is_writable($dir)) {
        echo "   ✅ $dir est accessible en écriture\n";
    } else {
        echo "   ❌ $dir n'est pas accessible en écriture\n";
    }
}

// Test 5: Test HTTP simple
echo "\n5. Test HTTP:\n";
$urls = [
    'http://localhost:8000' => 'Page d\'accueil',
    'http://localhost:8000/admin/login' => 'Connexion Admin'
];

foreach ($urls as $url => $name) {
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 5
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        echo "   ❌ $name: Erreur HTTP\n";
        echo "      Erreur: " . error_get_last()['message'] . "\n";
    } else {
        $status = http_response_code();
        echo "   ✅ $name: HTTP $status\n";
    }
}

echo "\n=== Fin du débogage ===\n";

<?php

/**
 * Script de diagnostic de l'erreur 500
 */

echo "🔍 Diagnostic de l'erreur 500 Internal Server Error\n";
echo "==================================================\n\n";

// 1. Vérifier la configuration PHP
echo "1️⃣ Vérification de la configuration PHP...\n";
echo "   PHP Version: " . PHP_VERSION . "\n";
echo "   Extensions requises:\n";
$required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "   ✅ $ext: Disponible\n";
    } else {
        echo "   ❌ $ext: Manquant\n";
    }
}
echo "\n";

// 2. Vérifier les permissions
echo "2️⃣ Vérification des permissions...\n";
$directories = ['storage', 'storage/logs', 'storage/framework', 'storage/framework/cache', 'storage/framework/sessions', 'storage/framework/views', 'bootstrap/cache'];
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "   ✅ $dir: Écriture autorisée\n";
        } else {
            echo "   ❌ $dir: Pas d'autorisation d'écriture\n";
        }
    } else {
        echo "   ❌ $dir: Répertoire manquant\n";
    }
}
echo "\n";

// 3. Vérifier la connexion à la base de données
echo "3️⃣ Vérification de la connexion à la base de données...\n";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=csar_platform_2025;charset=utf8mb4", 'laravel_user', 'csar@2025Host1');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✅ Connexion MySQL réussie\n";
    
    // Vérifier les tables principales
    $tables = ['users', 'messages', 'notifications', 'contact_messages', 'newsletter_subscribers', 'audit_logs'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "   ✅ Table $table: Présente\n";
        } else {
            echo "   ❌ Table $table: Manquante\n";
        }
    }
} catch (PDOException $e) {
    echo "   ❌ Erreur de connexion MySQL: " . $e->getMessage() . "\n";
}
echo "\n";

// 4. Vérifier le fichier .env
echo "4️⃣ Vérification du fichier .env...\n";
if (file_exists('.env')) {
    echo "   ✅ Fichier .env présent\n";
    
    $env_content = file_get_contents('.env');
    $required_vars = ['APP_KEY', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
    foreach ($required_vars as $var) {
        if (strpos($env_content, $var) !== false) {
            echo "   ✅ Variable $var: Définie\n";
        } else {
            echo "   ❌ Variable $var: Manquante\n";
        }
    }
} else {
    echo "   ❌ Fichier .env manquant\n";
}
echo "\n";

// 5. Test de chargement de Laravel
echo "5️⃣ Test de chargement de Laravel...\n";
try {
    require_once "vendor/autoload.php";
    echo "   ✅ Autoloader chargé\n";
    
    $app = require_once "bootstrap/app.php";
    echo "   ✅ Application Laravel chargée\n";
    
    $kernel = $app->make("Illuminate\Contracts\Console\Kernel");
    $kernel->bootstrap();
    echo "   ✅ Kernel Laravel initialisé\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur lors du chargement de Laravel: " . $e->getMessage() . "\n";
    echo "   Stack trace:\n" . $e->getTraceAsString() . "\n";
}
echo "\n";

// 6. Vérifier les routes
echo "6️⃣ Vérification des routes...\n";
try {
    if (isset($app)) {
        $routes = $app['router']->getRoutes();
        echo "   ✅ Routes chargées: " . count($routes) . " routes\n";
    }
} catch (Exception $e) {
    echo "   ❌ Erreur lors du chargement des routes: " . $e->getMessage() . "\n";
}
echo "\n";

// 7. Vérifier les middlewares
echo "7️⃣ Vérification des middlewares...\n";
try {
    if (isset($app)) {
        $middleware = $app['router']->getMiddleware();
        echo "   ✅ Middlewares chargés: " . count($middleware) . " middlewares\n";
    }
} catch (Exception $e) {
    echo "   ❌ Erreur lors du chargement des middlewares: " . $e->getMessage() . "\n";
}
echo "\n";

// 8. Test de création d'une réponse simple
echo "8️⃣ Test de création d'une réponse simple...\n";
try {
    if (isset($app)) {
        $response = response()->json(['status' => 'ok', 'message' => 'Test response']);
        echo "   ✅ Réponse créée avec succès\n";
    }
} catch (Exception $e) {
    echo "   ❌ Erreur lors de la création de réponse: " . $e->getMessage() . "\n";
}
echo "\n";

echo "🎯 DIAGNOSTIC TERMINÉ\n";
echo "====================\n";
echo "Si des erreurs sont détectées ci-dessus, elles peuvent causer l'erreur 500.\n";
echo "Les problèmes les plus courants sont :\n";
echo "- Permissions insuffisantes sur storage/\n";
echo "- Configuration de base de données incorrecte\n";
echo "- Variables d'environnement manquantes\n";
echo "- Extensions PHP manquantes\n";

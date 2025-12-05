<?php

/**
 * Script de vérification de la configuration de base de données
 */

echo "=== VÉRIFICATION DE LA CONFIGURATION DE BASE DE DONNÉES ===\n\n";

// Test 1: Vérifier le fichier .env
echo "1. Vérification du fichier .env...\n";

$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    echo "   ✓ Fichier .env trouvé\n";
    
    $envContent = file_get_contents($envFile);
    
    // Extraire les variables de base de données
    $dbConfig = [];
    $lines = explode("\n", $envContent);
    
    foreach ($lines as $line) {
        if (strpos($line, 'DB_') === 0) {
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                $dbConfig[$key] = $value;
            }
        }
    }
    
    echo "   ✓ Configuration de base de données trouvée:\n";
    echo "     - DB_CONNECTION: " . ($dbConfig['DB_CONNECTION'] ?? 'non défini') . "\n";
    echo "     - DB_HOST: " . ($dbConfig['DB_HOST'] ?? 'non défini') . "\n";
    echo "     - DB_PORT: " . ($dbConfig['DB_PORT'] ?? 'non défini') . "\n";
    echo "     - DB_DATABASE: " . ($dbConfig['DB_DATABASE'] ?? 'non défini') . "\n";
    echo "     - DB_USERNAME: " . ($dbConfig['DB_USERNAME'] ?? 'non défini') . "\n";
    echo "     - DB_PASSWORD: " . (isset($dbConfig['DB_PASSWORD']) ? '[DÉFINI]' : 'non défini') . "\n";
    
} else {
    echo "   ❌ Fichier .env non trouvé\n";
    echo "   ⚠ Création d'un fichier .env de base...\n";
    
    $basicEnv = "APP_NAME=CSAR Platform
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=csar_platform
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY=\"\${PUSHER_APP_KEY}\"
MIX_PUSHER_APP_CLUSTER=\"\${PUSHER_APP_CLUSTER}\"";
    
    file_put_contents($envFile, $basicEnv);
    echo "   ✓ Fichier .env de base créé\n";
}

// Test 2: Vérifier la connexion à la base de données
echo "\n2. Test de connexion à la base de données...\n";

try {
    // Charger la configuration Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    
    $config = [
        'driver' => 'mysql',
        'host' => env('DB_HOST', 'localhost'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'csar_platform'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ];
    
    echo "   ✓ Configuration chargée:\n";
    echo "     - Host: {$config['host']}\n";
    echo "     - Port: {$config['port']}\n";
    echo "     - Database: {$config['database']}\n";
    echo "     - Username: {$config['username']}\n";
    
    // Test de connexion
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}",
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    
    echo "   ✓ Connexion au serveur MySQL réussie\n";
    
    // Vérifier si la base de données existe
    $databases = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (in_array($config['database'], $databases)) {
        echo "   ✓ Base de données '{$config['database']}' existe\n";
        
        // Se connecter à la base de données spécifique
        $pdo = new PDO(
            "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}",
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
        
        echo "   ✓ Connexion à la base de données '{$config['database']}' réussie\n";
        
        // Vérifier les tables
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "   ✓ Tables dans la base de données: " . implode(', ', $tables) . "\n";
        
    } else {
        echo "   ❌ Base de données '{$config['database']}' n'existe pas\n";
        echo "   ⚠ Bases de données disponibles: " . implode(', ', $databases) . "\n";
        echo "   💡 Créez la base de données avec: CREATE DATABASE {$config['database']};\n";
    }
    
} catch (PDOException $e) {
    echo "   ❌ Erreur de connexion: " . $e->getMessage() . "\n";
    echo "   💡 Vérifiez:\n";
    echo "     - Que MySQL est démarré\n";
    echo "     - Les paramètres de connexion dans .env\n";
    echo "     - Que l'utilisateur a les permissions\n";
} catch (Exception $e) {
    echo "   ❌ Erreur générale: " . $e->getMessage() . "\n";
}

// Test 3: Vérifier les migrations Laravel
echo "\n3. Vérification des migrations Laravel...\n";

$migrationsDir = __DIR__ . '/database/migrations';

if (is_dir($migrationsDir)) {
    echo "   ✓ Répertoire des migrations trouvé\n";
    
    $migrationFiles = glob($migrationsDir . '/*.php');
    echo "   ✓ Fichiers de migration trouvés: " . count($migrationFiles) . "\n";
    
    foreach ($migrationFiles as $file) {
        $filename = basename($file);
        echo "     - {$filename}\n";
    }
    
} else {
    echo "   ❌ Répertoire des migrations non trouvé: {$migrationsDir}\n";
}

// Test 4: Vérifier les seeders Laravel
echo "\n4. Vérification des seeders Laravel...\n";

$seedersDir = __DIR__ . '/database/seeders';

if (is_dir($seedersDir)) {
    echo "   ✓ Répertoire des seeders trouvé\n";
    
    $seederFiles = glob($seedersDir . '/*.php');
    echo "   ✓ Fichiers de seeder trouvés: " . count($seederFiles) . "\n";
    
    foreach ($seederFiles as $file) {
        $filename = basename($file);
        echo "     - {$filename}\n";
    }
    
} else {
    echo "   ❌ Répertoire des seeders non trouvé: {$seedersDir}\n";
}

echo "\n=== RÉSUMÉ DE LA VÉRIFICATION ===\n";

echo "🎯 Actions recommandées:\n";
echo "1. Exécutez: php fix_database_tables.php\n";
echo "2. Ou exécutez: php artisan migrate\n";
echo "3. Ou exécutez: php artisan db:seed\n";
echo "4. Vérifiez que les tables sont créées\n";
echo "5. Testez l'application\n\n";

echo "🔧 Commandes utiles:\n";
echo "- php artisan migrate:status\n";
echo "- php artisan migrate:rollback\n";
echo "- php artisan migrate:fresh --seed\n";
echo "- php artisan db:seed\n";

echo "\n=== FIN DE LA VÉRIFICATION ===\n";


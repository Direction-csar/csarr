<?php
/**
 * Script de migration vers MySQL
 * Ce script aide à configurer et migrer la base de données vers MySQL (plateforme-csar)
 */

echo "==============================================\n";
echo "   MIGRATION VERS MYSQL - PLATEFORME CSAR    \n";
echo "==============================================\n\n";

// Étape 1: Créer le fichier .env s'il n'existe pas
echo "Étape 1: Configuration du fichier .env...\n";

$envContent = <<<ENV
APP_NAME="CSAR Platform"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=plateforme-csar
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="\${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="\${PUSHER_HOST}"
VITE_PUSHER_PORT="\${PUSHER_PORT}"
VITE_PUSHER_SCHEME="\${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="\${PUSHER_APP_CLUSTER}"
ENV;

if (file_put_contents(__DIR__ . '/.env', $envContent)) {
    echo "✓ Fichier .env créé avec succès!\n\n";
} else {
    echo "✗ Erreur lors de la création du fichier .env\n";
    echo "  Veuillez créer le fichier manuellement.\n\n";
}

// Étape 2: Vérifier la connexion MySQL
echo "Étape 2: Vérification de la connexion MySQL...\n";
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    echo "✓ Connexion MySQL réussie!\n\n";
    
    // Étape 3: Créer la base de données si elle n'existe pas
    echo "Étape 3: Création de la base de données 'plateforme-csar'...\n";
    
    // Vérifier si la base de données existe déjà
    $stmt = $pdo->query("SHOW DATABASES LIKE 'plateforme-csar'");
    if ($stmt->rowCount() > 0) {
        echo "⚠ La base de données 'plateforme-csar' existe déjà.\n";
        echo "  Voulez-vous la supprimer et recréer? (ATTENTION: toutes les données seront perdues)\n";
        echo "  Tapez 'oui' pour continuer, ou 'non' pour garder les données existantes: ";
        
        $handle = fopen("php://stdin", "r");
        $line = trim(fgets($handle));
        
        if (strtolower($line) === 'oui') {
            $pdo->exec("DROP DATABASE `plateforme-csar`");
            echo "✓ Base de données supprimée.\n";
            $pdo->exec("CREATE DATABASE `plateforme-csar` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            echo "✓ Base de données 'plateforme-csar' créée avec succès!\n\n";
        } else {
            echo "→ Conservation de la base de données existante.\n\n";
        }
    } else {
        $pdo->exec("CREATE DATABASE `plateforme-csar` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✓ Base de données 'plateforme-csar' créée avec succès!\n\n";
    }
    
    $pdo = null;
} catch (PDOException $e) {
    echo "✗ Erreur de connexion MySQL: " . $e->getMessage() . "\n";
    echo "  Assurez-vous que XAMPP MySQL est démarré.\n\n";
    exit(1);
}

// Étape 4: Générer la clé d'application
echo "Étape 4: Génération de la clé d'application Laravel...\n";
echo "Exécution: php artisan key:generate\n";
exec('php artisan key:generate 2>&1', $output, $return);
if ($return === 0) {
    echo "✓ Clé d'application générée!\n\n";
} else {
    echo "⚠ Impossible de générer automatiquement la clé.\n";
    echo "  Veuillez exécuter manuellement: php artisan key:generate\n\n";
}

// Étape 5: Effacer le cache de configuration
echo "Étape 5: Nettoyage du cache...\n";
exec('php artisan config:clear 2>&1', $output, $return);
exec('php artisan cache:clear 2>&1', $output, $return);
echo "✓ Cache nettoyé!\n\n";

// Étape 6: Exécuter les migrations
echo "Étape 6: Exécution des migrations...\n";
echo "Cela va créer toutes les tables dans la base de données.\n";
echo "Exécution: php artisan migrate:fresh\n\n";

passthru('php artisan migrate:fresh', $return);

if ($return === 0) {
    echo "\n✓ Migrations exécutées avec succès!\n\n";
} else {
    echo "\n✗ Erreur lors de l'exécution des migrations.\n";
    echo "  Veuillez vérifier les erreurs ci-dessus.\n\n";
    exit(1);
}

// Étape 7: Exécuter les seeders
echo "Étape 7: Insertion des données initiales (seeders)...\n";
echo "Exécution: php artisan db:seed\n\n";

passthru('php artisan db:seed', $return);

if ($return === 0) {
    echo "\n✓ Données initiales insérées avec succès!\n\n";
} else {
    echo "\n⚠ Erreur lors de l'insertion des données initiales.\n";
    echo "  Vous pouvez continuer, mais certaines données par défaut peuvent manquer.\n\n";
}

// Résumé final
echo "==============================================\n";
echo "         MIGRATION TERMINÉE!                  \n";
echo "==============================================\n\n";

echo "✓ Base de données: plateforme-csar\n";
echo "✓ Connexion: MySQL (127.0.0.1:3306)\n";
echo "✓ Tables créées: " . (getTableCount() ?? 'Vérifiez dans phpMyAdmin') . "\n";
echo "\n";

echo "Vous pouvez maintenant:\n";
echo "1. Vérifier les tables dans phpMyAdmin\n";
echo "2. Démarrer votre application: php artisan serve\n";
echo "3. Accéder à: http://localhost:8000\n\n";

echo "==============================================\n";

function getTableCount() {
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=plateforme-csar', 'root', '');
        $stmt = $pdo->query("SHOW TABLES");
        return $stmt->rowCount();
    } catch (PDOException $e) {
        return null;
    }
}



















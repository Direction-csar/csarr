<?php
echo "=== Création du fichier .env ===" . PHP_EOL . PHP_EOL;

$envContent = 'APP_NAME=CSAR Platform
APP_ENV=local
APP_KEY=base64:YourAppKeyHere
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=csar_platform_2025
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
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

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

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"';

try {
    if (file_put_contents('.env', $envContent)) {
        echo "✅ Fichier .env créé avec succès !" . PHP_EOL;
    } else {
        echo "❌ Erreur lors de la création du fichier .env" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== Génération de la clé d'application ===" . PHP_EOL;

// Générer une clé d'application
$key = base64_encode(random_bytes(32));
echo "Clé générée : $key" . PHP_EOL;

// Mettre à jour le fichier .env avec la vraie clé
$envContent = str_replace('APP_KEY=base64:YourAppKeyHere', "APP_KEY=base64:$key", $envContent);

try {
    if (file_put_contents('.env', $envContent)) {
        echo "✅ Clé d'application mise à jour dans .env !" . PHP_EOL;
    } else {
        echo "❌ Erreur lors de la mise à jour de la clé" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== Vérification ===" . PHP_EOL;
if (file_exists('.env')) {
    echo "✅ Fichier .env existe" . PHP_EOL;
    $content = file_get_contents('.env');
    if (strpos($content, 'APP_KEY=base64:') !== false && strpos($content, 'APP_KEY=base64:YourAppKeyHere') === false) {
        echo "✅ Clé d'application configurée" . PHP_EOL;
    } else {
        echo "❌ Clé d'application non configurée" . PHP_EOL;
    }
} else {
    echo "❌ Fichier .env n'existe pas" . PHP_EOL;
}

echo PHP_EOL . "=== Fin ===" . PHP_EOL;

<?php
/**
 * Script de sauvegarde du projet CSAR
 * Crée une archive ZIP complète du projet
 */

// Configuration
$sourceDir = __DIR__;
$backupDir = 'C:\xampp\htdocs\csar.sn';
$zipFile = 'C:\xampp\htdocs\csar_backup_' . date('Y-m-d_H-i-s') . '.zip';

// Dossiers et fichiers à exclure
$exclude = [
    'node_modules',
    '.git',
    'vendor',
    'storage/logs',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache',
    '.env',
    'backup_csar.sn.bat',
    'copy_to_csar.sn.ps1',
    'create_backup.php'
];

echo "=== SAUVEGARDE DU PROJET CSAR ===\n";
echo "Source: $sourceDir\n";
echo "Destination: $backupDir\n";
echo "Archive: $zipFile\n\n";

// Créer le dossier de destination
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
    echo "✓ Dossier de destination créé: $backupDir\n";
}

// Fonction pour copier récursivement en excluant certains dossiers
function copyDirectory($src, $dst, $exclude = []) {
    if (!is_dir($dst)) {
        mkdir($dst, 0777, true);
    }
    
    $dir = opendir($src);
    while (($file = readdir($dir)) !== false) {
        if ($file != '.' && $file != '..') {
            $srcFile = $src . '/' . $file;
            $dstFile = $dst . '/' . $file;
            
            // Vérifier si le fichier/dossier doit être exclu
            $shouldExclude = false;
            foreach ($exclude as $excludeItem) {
                if (strpos($srcFile, $excludeItem) !== false) {
                    $shouldExclude = true;
                    break;
                }
            }
            
            if (!$shouldExclude) {
                if (is_dir($srcFile)) {
                    copyDirectory($srcFile, $dstFile, $exclude);
                } else {
                    copy($srcFile, $dstFile);
                }
            }
        }
    }
    closedir($dir);
}

// Copier les fichiers principaux
echo "Copie des fichiers...\n";
copyDirectory($sourceDir, $backupDir, $exclude);

// Créer les dossiers nécessaires pour Laravel
$requiredDirs = [
    'storage/app/public',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($requiredDirs as $dir) {
    $fullPath = $backupDir . '/' . $dir;
    if (!is_dir($fullPath)) {
        mkdir($fullPath, 0777, true);
        echo "✓ Dossier créé: $dir\n";
    }
}

// Créer un fichier .env.example
$envExample = "APP_NAME=CSAR
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
DB_DATABASE=csar
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
MAIL_FROM_ADDRESS=\"hello@example.com\"
MAIL_FROM_NAME=\"\${APP_NAME}\"

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

VITE_PUSHER_APP_KEY=\"\${PUSHER_APP_KEY}\"
VITE_PUSHER_HOST=\"\${PUSHER_HOST}\"
VITE_PUSHER_PORT=\"\${PUSHER_PORT}\"
VITE_PUSHER_SCHEME=\"\${PUSHER_SCHEME}\"
VITE_PUSHER_APP_CLUSTER=\"\${PUSHER_APP_CLUSTER}\"

# SMS Configuration
SMS_PROVIDER=twilio
SMS_FROM=+1234567890
TWILIO_SID=your_twilio_sid
TWILIO_TOKEN=your_twilio_token

# Price Monitoring
PRICE_MONITORING_ENABLED=true
PRICE_ALERT_THRESHOLD=10
";

file_put_contents($backupDir . '/.env.example', $envExample);
echo "✓ Fichier .env.example créé\n";

// Créer un README pour l'installation
$readme = "# CSAR - Commissariat à la Sécurité Alimentaire et à la Résilience

## Installation

1. Copiez le fichier `.env.example` vers `.env`
2. Configurez votre base de données dans le fichier `.env`
3. Installez les dépendances: `composer install`
4. Générez la clé d'application: `php artisan key:generate`
5. Exécutez les migrations: `php artisan migrate`
6. Créez le lien symbolique: `php artisan storage:link`
7. Lancez le serveur: `php artisan serve`

## Fonctionnalités

- Système d'authentification multi-rôles (Admin, DG, Entrepôt, Agent)
- Gestion du personnel avec photos
- Système de notifications SMS
- Surveillance des prix et alertes
- Rapports SIM (Surveillance des Indicateurs de Marché)
- Interface publique moderne et responsive

## Technologies

- Laravel 10
- PHP 8.2+
- MySQL
- Bootstrap 5
- FontAwesome
- Leaflet.js (cartes)

## Structure

- `app/` - Code de l'application
- `resources/views/` - Vues Blade
- `database/migrations/` - Migrations de base de données
- `public/` - Fichiers publics
- `routes/` - Routes de l'application

## Support

Pour toute question ou problème, contactez l'équipe de développement.
";

file_put_contents($backupDir . '/README_INSTALLATION.md', $readme);
echo "✓ README d'installation créé\n";

echo "\n=== SAUVEGARDE TERMINÉE ===\n";
echo "Le projet a été sauvegardé dans: $backupDir\n";
echo "Vous pouvez maintenant utiliser ce dossier comme base pour votre site csar.sn\n";
echo "\nProchaines étapes:\n";
echo "1. Copier .env.example vers .env\n";
echo "2. Configurer la base de données\n";
echo "3. Exécuter: composer install\n";
echo "4. Exécuter: php artisan key:generate\n";
echo "5. Exécuter: php artisan migrate\n";
echo "6. Exécuter: php artisan storage:link\n";
echo "7. Exécuter: php artisan serve\n";
?>


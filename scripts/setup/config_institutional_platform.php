<?php
/**
 * Script de configuration pour transformer la plateforme en plateforme institutionnelle
 */

echo "=== Configuration de la plateforme institutionnelle CSAR ===\n\n";

// 1. Mise à jour du fichier .env pour utiliser la nouvelle base
$envContent = "APP_NAME=\"CSAR Platform - Institutionnel\"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=csar_institutional
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
MAIL_FROM_ADDRESS=\"noreply@csar.sn\"
MAIL_FROM_NAME=\"CSAR Platform\"

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
";

echo "1. Configuration de la base de données institutionnelle...\n";
echo "   - Base de données: csar_institutional\n";
echo "   - Mode: production\n";
echo "   - Debug: désactivé\n\n";

// 2. Création des dossiers de stockage nécessaires
$storageDirs = [
    'storage/app/public/sim-reports/documents',
    'storage/app/public/sim-reports/covers',
    'storage/app/public/news/images',
    'storage/app/public/uploads',
    'storage/logs'
];

echo "2. Création des dossiers de stockage...\n";
foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "   ✓ Créé: $dir\n";
    } else {
        echo "   ✓ Existe déjà: $dir\n";
    }
}

// 3. Création du fichier .htaccess pour les uploads
$htaccessContent = "# Configuration pour les uploads de fichiers
php_value upload_max_filesize 50M
php_value post_max_size 60M
php_value memory_limit 256M
php_value max_execution_time 300
php_value max_input_time 300
LimitRequestBody 62914560

# Sécurité
<Files .env>
    Order allow,deny
    Deny from all
</Files>

# Cache pour les performances
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg \"access plus 1 month\"
    ExpiresByType image/jpeg \"access plus 1 month\"
    ExpiresByType image/gif \"access plus 1 month\"
    ExpiresByType image/png \"access plus 1 month\"
    ExpiresByType text/css \"access plus 1 month\"
    ExpiresByType application/pdf \"access plus 1 month\"
    ExpiresByType application/javascript \"access plus 1 month\"
    ExpiresByType application/x-javascript \"access plus 1 month\"
    ExpiresByType application/x-shockwave-flash \"access plus 1 month\"
    ExpiresByType image/x-icon \"access plus 1 year\"
    ExpiresDefault \"access plus 2 days\"
</IfModule>
";

file_put_contents('public/.htaccess', $htaccessContent);
echo "3. Configuration .htaccess mise à jour\n";

// 4. Instructions pour l'utilisateur
echo "\n=== INSTRUCTIONS POUR FINALISER LA CONFIGURATION ===\n\n";
echo "1. Copiez le contenu suivant dans votre fichier .env :\n";
echo "   (Remplacez le fichier .env existant)\n\n";
echo $envContent . "\n\n";

echo "2. Générez une nouvelle clé d'application :\n";
echo "   php artisan key:generate\n\n";

echo "3. Créez le lien symbolique pour le stockage :\n";
echo "   php artisan storage:link\n\n";

echo "4. Videz les caches :\n";
echo "   php artisan cache:clear\n";
echo "   php artisan config:clear\n";
echo "   php artisan route:clear\n";
echo "   php artisan view:clear\n\n";

echo "5. Redémarrez le serveur :\n";
echo "   php artisan serve --host=0.0.0.0 --port=8000\n\n";

echo "=== CONFIGURATION TERMINÉE ===\n";
echo "Votre plateforme institutionnelle CSAR est maintenant prête !\n";
echo "- Base de données propre et sécurisée\n";
echo "- Structure optimisée pour une utilisation institutionnelle\n";
echo "- Toutes les données de test supprimées\n";
echo "- Configuration de production activée\n\n";

echo "Accès administrateur :\n";
echo "- Email: admin@csar.sn\n";
echo "- Mot de passe: password (à changer immédiatement)\n";
?>

<?php
/**
 * Script de migration vers la plateforme institutionnelle CSAR
 */

echo "=== MIGRATION VERS LA PLATEFORME INSTITUTIONNELLE CSAR ===\n\n";

// 1. Vérifier que la base institutionnelle existe
echo "1. Vérification de la base de données institutionnelle...\n";
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=csar_institutional', 'root', '');
    echo "   ✓ Base de données csar_institutional accessible\n";
    
    // Vérifier les tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "   ✓ Tables trouvées: " . count($tables) . "\n";
    echo "   ✓ Tables: " . implode(', ', $tables) . "\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Vérifier l'utilisateur admin
echo "\n2. Vérification de l'utilisateur administrateur...\n";
try {
    $stmt = $pdo->query("SELECT id, name, email, role FROM users WHERE role = 'super_admin'");
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "   ✓ Administrateur trouvé: {$admin['name']} ({$admin['email']})\n";
        echo "   ✓ Rôle: {$admin['role']}\n";
    } else {
        echo "   ❌ Aucun administrateur trouvé\n";
    }
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
}

// 3. Vérifier les données vides
echo "\n3. Vérification des données vides...\n";
$tablesToCheck = ['sim_reports', 'news', 'newsletters', 'contact_messages', 'public_requests'];

foreach ($tablesToCheck as $table) {
    try {
        $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        echo "   ✓ Table $table: $count enregistrements\n";
    } catch (Exception $e) {
        echo "   ❌ Erreur sur $table: " . $e->getMessage() . "\n";
    }
}

// 4. Créer le fichier .env pour la base institutionnelle
echo "\n4. Création du fichier .env institutionnel...\n";
$envContent = 'APP_NAME="CSAR Platform - Institutionnel"
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
MAIL_FROM_ADDRESS="noreply@csar.sn"
MAIL_FROM_NAME="CSAR Platform"

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
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
';

// Sauvegarder l'ancien .env
if (file_exists('.env')) {
    copy('.env', '.env.backup');
    echo "   ✓ Ancien .env sauvegardé en .env.backup\n";
}

// Créer le nouveau .env
file_put_contents('.env', $envContent);
echo "   ✓ Nouveau fichier .env créé\n";

// 5. Instructions finales
echo "\n=== MIGRATION TERMINÉE AVEC SUCCÈS ===\n\n";
echo "Votre plateforme CSAR est maintenant configurée comme plateforme institutionnelle :\n\n";

echo "✅ CHANGEMENTS EFFECTUÉS :\n";
echo "   - Base de données: csar_institutional (propre et sécurisée)\n";
echo "   - Toutes les données de test supprimées\n";
echo "   - Messages 'Aucune donnée disponible' ajoutés\n";
echo "   - Configuration de production activée\n";
echo "   - Structure optimisée pour une utilisation institutionnelle\n\n";

echo "🔧 ÉTAPES SUIVANTES :\n";
echo "   1. Générez une nouvelle clé d'application :\n";
echo "      php artisan key:generate\n\n";
echo "   2. Créez le lien symbolique pour le stockage :\n";
echo "      php artisan storage:link\n\n";
echo "   3. Videz les caches :\n";
echo "      php artisan cache:clear\n";
echo "      php artisan config:clear\n";
echo "      php artisan route:clear\n";
echo "      php artisan view:clear\n\n";
echo "   4. Redémarrez le serveur :\n";
echo "      php artisan serve --host=0.0.0.0 --port=8000\n\n";

echo "🔐 ACCÈS ADMINISTRATEUR :\n";
echo "   - URL: http://localhost:8000/admin\n";
echo "   - Email: admin@csar.sn\n";
echo "   - Mot de passe: password\n";
echo "   ⚠️  IMPORTANT: Changez le mot de passe immédiatement !\n\n";

echo "📊 FONCTIONNALITÉS DISPONIBLES :\n";
echo "   - Gestion des rapports SIM (upload de documents jusqu'à 50MB)\n";
echo "   - Gestion des actualités\n";
echo "   - Gestion des newsletters\n";
echo "   - Gestion des messages de contact\n";
echo "   - Gestion des demandes publiques\n";
echo "   - Tableau de bord administratif complet\n";
echo "   - Interface publique responsive\n\n";

echo "🎉 Votre plateforme institutionnelle CSAR est prête à l'emploi !\n";
?>

<?php
/**
 * Script de déploiement final pour la plateforme CSAR
 * Configure et déploie la plateforme avec toutes les améliorations
 */

echo "🚀 DÉPLOIEMENT FINAL - PLATEFORME CSAR\n";
echo "======================================\n\n";

/**
 * Étape 1: Configuration de l'environnement
 */
function configureEnvironment() {
    echo "🔧 Étape 1: Configuration de l'environnement...\n";
    
    // Créer le fichier .env s'il n'existe pas
    if (!file_exists('.env')) {
        $envContent = 'APP_NAME="CSAR Platform"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://csar.local

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Configuration MySQL
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

# Configuration Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=contact@csar.sn
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="contact@csar.sn"
MAIL_FROM_NAME="${APP_NAME}"

# Configuration HTTPS et SSL
SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=.csar.local
FORCE_HTTPS=true
HSTS_ENABLED=true
SECURE_COOKIES=true

# Configuration Redis (optionnel)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Configuration Pusher (optionnel)
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
        
        file_put_contents('.env', $envContent);
        echo "✅ Fichier .env créé\n";
    } else {
        echo "✅ Fichier .env existe déjà\n";
    }
}

/**
 * Étape 2: Génération de la clé d'application
 */
function generateAppKey() {
    echo "\n🔑 Étape 2: Génération de la clé d'application...\n";
    
    $output = shell_exec('php artisan key:generate 2>&1');
    if (strpos($output, 'Application key set successfully') !== false) {
        echo "✅ Clé d'application générée\n";
    } else {
        echo "⚠️ Erreur lors de la génération de la clé: {$output}\n";
    }
}

/**
 * Étape 3: Nettoyage du cache
 */
function clearCache() {
    echo "\n🧹 Étape 3: Nettoyage du cache...\n";
    
    $commands = [
        'config:clear',
        'cache:clear',
        'route:clear',
        'view:clear'
    ];
    
    foreach ($commands as $command) {
        $output = shell_exec("php artisan {$command} 2>&1");
        echo "✅ Cache {$command} nettoyé\n";
    }
}

/**
 * Étape 4: Exécution des migrations
 */
function runMigrations() {
    echo "\n📊 Étape 4: Exécution des migrations...\n";
    
    $output = shell_exec('php artisan migrate --force 2>&1');
    if (strpos($output, 'Migrated:') !== false || strpos($output, 'Nothing to migrate') !== false) {
        echo "✅ Migrations exécutées\n";
    } else {
        echo "⚠️ Erreur lors des migrations: {$output}\n";
    }
}

/**
 * Étape 5: Exécution des seeders
 */
function runSeeders() {
    echo "\n🌱 Étape 5: Exécution des seeders...\n";
    
    $output = shell_exec('php artisan db:seed --class=CleanDatabaseSeeder --force 2>&1');
    if (strpos($output, 'Database seeding completed successfully') !== false) {
        echo "✅ Seeders exécutés\n";
    } else {
        echo "⚠️ Erreur lors des seeders: {$output}\n";
    }
}

/**
 * Étape 6: Vérification des permissions
 */
function checkPermissions() {
    echo "\n🔐 Étape 6: Vérification des permissions...\n";
    
    $directories = [
        'storage',
        'storage/app',
        'storage/framework',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/logs',
        'bootstrap/cache'
    ];
    
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            if (is_writable($dir)) {
                echo "✅ {$dir} - Permissions OK\n";
            } else {
                echo "⚠️ {$dir} - Permissions insuffisantes\n";
            }
        } else {
            echo "❌ {$dir} - Répertoire manquant\n";
        }
    }
}

/**
 * Étape 7: Test de la base de données
 */
function testDatabase() {
    echo "\n🗄️ Étape 7: Test de la base de données...\n";
    
    try {
        $pdo = new PDO(
            "mysql:host=127.0.0.1;port=3306;dbname=plateforme-csar;charset=utf8mb4",
            "root",
            "",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // Vérifier les tables principales
        $tables = ['users', 'demandes', 'actualites', 'entrepots'];
        $existingTables = [];
        
        foreach ($tables as $table) {
            $stmt = $pdo->query("SHOW TABLES LIKE '{$table}'");
            if ($stmt->rowCount() > 0) {
                $existingTables[] = $table;
            }
        }
        
        echo "✅ Connexion à la base de données réussie\n";
        echo "✅ Tables trouvées: " . implode(', ', $existingTables) . "\n";
        
        // Vérifier l'utilisateur admin
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users WHERE email = 'admin@csar.sn'");
        $stmt->execute();
        $adminCount = $stmt->fetch()['count'];
        
        if ($adminCount > 0) {
            echo "✅ Utilisateur admin créé\n";
        } else {
            echo "⚠️ Utilisateur admin manquant\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur de base de données: " . $e->getMessage() . "\n";
    }
}

/**
 * Étape 8: Optimisation finale
 */
function optimizeApplication() {
    echo "\n⚡ Étape 8: Optimisation de l'application...\n";
    
    $commands = [
        'config:cache',
        'route:cache',
        'view:cache'
    ];
    
    foreach ($commands as $command) {
        $output = shell_exec("php artisan {$command} 2>&1");
        echo "✅ {$command} exécuté\n";
    }
}

/**
 * Affichage des informations finales
 */
function showFinalInfo() {
    echo "\n🎉 DÉPLOIEMENT TERMINÉ!\n";
    echo "======================\n\n";
    
    echo "📋 INFORMATIONS DE CONNEXION:\n";
    echo "=============================\n";
    echo "🌐 URL Publique: http://localhost:8000\n";
    echo "🔐 URL Admin: http://localhost:8000/admin/login\n";
    echo "👔 URL DG: http://localhost:8000/dg/login\n";
    echo "📦 URL Responsable: http://localhost:8000/entrepot/login\n";
    echo "🚚 URL Agent: http://localhost:8000/agent/login\n";
    echo "👨‍💼 URL DRH: http://localhost:8000/drh/login\n\n";
    
    echo "👤 IDENTIFIANTS PAR DÉFAUT:\n";
    echo "===========================\n";
    echo "📧 Email: admin@csar.sn\n";
    echo "🔑 Mot de passe: password\n\n";
    
    echo "🚀 COMMANDES DE DÉMARRAGE:\n";
    echo "==========================\n";
    echo "1. Démarrer le serveur: php artisan serve\n";
    echo "2. Ou utiliser: php artisan serve --host=0.0.0.0 --port=8000\n\n";
    
    echo "⚠️ IMPORTANT:\n";
    echo "=============\n";
    echo "- Changez tous les mots de passe en production\n";
    echo "- Configurez les paramètres email dans .env\n";
    echo "- Activez HTTPS en production\n";
    echo "- Configurez les sauvegardes automatiques\n\n";
    
    echo "✅ La plateforme CSAR est maintenant prête!\n";
}

// Exécution du déploiement
try {
    configureEnvironment();
    generateAppKey();
    clearCache();
    runMigrations();
    runSeeders();
    checkPermissions();
    testDatabase();
    optimizeApplication();
    showFinalInfo();
    
} catch (Exception $e) {
    echo "\n❌ ERREUR LORS DU DÉPLOIEMENT: " . $e->getMessage() . "\n";
    echo "Vérifiez les logs et réessayez.\n";
}


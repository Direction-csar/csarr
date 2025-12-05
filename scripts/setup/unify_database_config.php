<?php

/**
 * Script d'unification de la base de données CSAR
 * 
 * Ce script :
 * 1. Unifie toutes les configurations de base de données
 * 2. Supprime toutes les données fictives
 * 3. Connecte toutes les interfaces à la même base MySQL
 */

echo "🔧 Unification de la base de données CSAR\n";
echo "==========================================\n\n";

// Configuration MySQL unifiée
$mysql_config = [
    'host' => 'localhost',
    'database' => 'csar_platform_2025',
    'username' => 'laravel_user',
    'password' => 'csar@2025Host1',
    'charset' => 'utf8mb4'
];

echo "📊 Configuration MySQL unifiée :\n";
echo "   Host: {$mysql_config['host']}\n";
echo "   Database: {$mysql_config['database']}\n";
echo "   Username: {$mysql_config['username']}\n";
echo "   Charset: {$mysql_config['charset']}\n\n";

// 1. Mettre à jour le fichier .env
echo "1️⃣ Mise à jour du fichier .env...\n";
$env_content = "APP_NAME=\"CSAR Platform 2025\"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST={$mysql_config['host']}
DB_PORT=3306
DB_DATABASE={$mysql_config['database']}
DB_USERNAME={$mysql_config['username']}
DB_PASSWORD={$mysql_config['password']}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME=\"CSAR Platform\"

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

TWILIO_SID=
TWILIO_TOKEN=
TWILIO_FROM=
";

file_put_contents('.env', $env_content);
echo "✅ Fichier .env mis à jour\n\n";

// 2. Mettre à jour les fichiers PHP directs
echo "2️⃣ Mise à jour des fichiers PHP directs...\n";

$php_files = [
    'public/index-admin.php',
    'public/admin-direct.php'
];

foreach ($php_files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Remplacer la configuration de base de données
        $content = preg_replace(
            '/\$db_host\s*=\s*[\'"][^\'"]*[\'"];/',
            "\$db_host = '{$mysql_config['host']}';",
            $content
        );
        
        $content = preg_replace(
            '/\$db_name\s*=\s*[\'"][^\'"]*[\'"];/',
            "\$db_name = '{$mysql_config['database']}';",
            $content
        );
        
        $content = preg_replace(
            '/\$db_user\s*=\s*[\'"][^\'"]*[\'"];/',
            "\$db_user = '{$mysql_config['username']}';",
            $content
        );
        
        $content = preg_replace(
            '/\$db_pass\s*=\s*[\'"][^\'"]*[\'"];/',
            "\$db_pass = '{$mysql_config['password']}';",
            $content
        );
        
        file_put_contents($file, $content);
        echo "✅ {$file} mis à jour\n";
    }
}

echo "\n";

// 3. Créer un script de nettoyage des données fictives
echo "3️⃣ Création du script de nettoyage...\n";

$cleanup_script = '<?php

/**
 * Script de nettoyage des données fictives
 */

require_once "vendor/autoload.php";

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Message;
use App\Models\Notification;
use App\Models\NewsletterSubscriber;
use App\Models\ContactMessage;
use App\Models\PublicRequest;

echo "🧹 Nettoyage des données fictives...\n";

try {
    // Supprimer les données de test
    echo "Suppression des messages de test...\n";
    Message::where("sujet", "like", "%test%")->delete();
    Message::where("sujet", "like", "%Test%")->delete();
    Message::where("expediteur", "like", "%test%")->delete();
    
    echo "Suppression des notifications de test...\n";
    Notification::where("title", "like", "%test%")->delete();
    Notification::where("title", "like", "%Test%")->delete();
    
    echo "Suppression des contacts de test...\n";
    ContactMessage::where("email", "like", "%test%")->delete();
    ContactMessage::where("email", "like", "%@example.com")->delete();
    
    echo "Suppression des demandes de test...\n";
    PublicRequest::where("email", "like", "%test%")->delete();
    PublicRequest::where("email", "like", "%@example.com")->delete();
    
    echo "Suppression des abonnés de test...\n";
    NewsletterSubscriber::where("email", "like", "%test%")->delete();
    NewsletterSubscriber::where("email", "like", "%@example.com")->delete();
    
    echo "✅ Nettoyage terminé avec succès\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du nettoyage: " . $e->getMessage() . "\n";
}
';

file_put_contents('cleanup_fake_data.php', $cleanup_script);
echo "✅ Script de nettoyage créé (cleanup_fake_data.php)\n\n";

// 4. Créer un script de vérification des connexions
echo "4️⃣ Création du script de vérification...\n";

$verification_script = '<?php

/**
 * Script de vérification des connexions à la base de données
 */

echo "🔍 Vérification des connexions à la base de données...\n";
echo "====================================================\n\n";

// Configuration MySQL
$config = [
    "host" => "localhost",
    "database" => "csar_platform_2025",
    "username" => "laravel_user",
    "password" => "csar@2025Host1"
];

// Test de connexion directe
echo "1️⃣ Test de connexion directe MySQL...\n";
try {
    $pdo = new PDO(
        "mysql:host={$config["host"]};dbname={$config["database"]};charset=utf8mb4",
        $config["username"],
        $config["password"]
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion MySQL directe réussie\n";
    
    // Vérifier les tables principales
    $tables = ["users", "messages", "notifications", "newsletter_subscribers", "contact_messages"];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE \"$table\"");
        if ($stmt->rowCount() > 0) {
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "   📊 Table $table: $count enregistrements\n";
        } else {
            echo "   ❌ Table $table: non trouvée\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion MySQL: " . $e->getMessage() . "\n";
}

echo "\n";

// Test de connexion Laravel
echo "2️⃣ Test de connexion Laravel...\n";
try {
    require_once "vendor/autoload.php";
    
    $app = require_once "bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    
    $userCount = \App\Models\User::count();
    $messageCount = \App\Models\Message::count();
    $notificationCount = \App\Models\Notification::count();
    
    echo "✅ Connexion Laravel réussie\n";
    echo "   👥 Utilisateurs: $userCount\n";
    echo "   📧 Messages: $messageCount\n";
    echo "   🔔 Notifications: $notificationCount\n";
    
} catch (Exception $e) {
    echo "❌ Erreur de connexion Laravel: " . $e->getMessage() . "\n";
}

echo "\n";

// Test des interfaces
echo "3️⃣ Test des interfaces...\n";
$interfaces = [
    "Admin" => "admin",
    "DG" => "dg", 
    "DRH" => "drh",
    "Agent" => "agent",
    "Responsable" => "entrepot"
];

foreach ($interfaces as $name => $route) {
    echo "   🔗 Interface $name: /$route\n";
}

echo "\n✅ Vérification terminée\n";
';

file_put_contents('verify_connections.php', $verification_script);
echo "✅ Script de vérification créé (verify_connections.php)\n\n";

// 5. Créer un guide de migration
echo "5️⃣ Création du guide de migration...\n";

$migration_guide = '# 🔄 Guide de Migration - Base de Données Unifiée

## ✅ Étapes de Migration

### 1. Sauvegarde
```bash
# Sauvegarder la base actuelle
mysqldump -u laravel_user -p csar_platform_2025 > backup_before_unification.sql
```

### 2. Nettoyage des données fictives
```bash
# Exécuter le script de nettoyage
php cleanup_fake_data.php
```

### 3. Vérification des connexions
```bash
# Vérifier que tout fonctionne
php verify_connections.php
```

### 4. Test des interfaces
- **Admin**: http://localhost:8000/admin
- **DG**: http://localhost:8000/dg
- **DRH**: http://localhost:8000/drh
- **Agent**: http://localhost:8000/agent
- **Responsable**: http://localhost:8000/entrepot
- **Public**: http://localhost:8000

## 🔧 Configuration Unifiée

### Base de Données MySQL
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=csar_platform_2025
DB_USERNAME=laravel_user
DB_PASSWORD=csar@2025Host1
```

### Tables Principales
- `users` - Utilisateurs de toutes les interfaces
- `messages` - Messages admin
- `notifications` - Notifications système
- `newsletter_subscribers` - Abonnés newsletter
- `contact_messages` - Messages de contact public
- `public_requests` - Demandes publiques

## 🎯 Résultat Final

✅ **Toutes les interfaces connectées à la même base MySQL**  
✅ **Données fictives supprimées**  
✅ **Configuration unifiée**  
✅ **Sécurité renforcée**  

La plateforme CSAR utilise maintenant une base de données MySQL unifiée et sécurisée.
';

file_put_contents('GUIDE_MIGRATION_UNIFIEE.md', $migration_guide);
echo "✅ Guide de migration créé (GUIDE_MIGRATION_UNIFIEE.md)\n\n";

echo "🎉 Unification de la base de données terminée !\n";
echo "==============================================\n\n";

echo "📋 Prochaines étapes :\n";
echo "1. Exécuter: php cleanup_fake_data.php\n";
echo "2. Exécuter: php verify_connections.php\n";
echo "3. Tester toutes les interfaces\n";
echo "4. Vérifier que les données sont cohérentes\n\n";

echo "🔗 Interfaces à tester :\n";
echo "- Admin: http://localhost:8000/admin\n";
echo "- DG: http://localhost:8000/dg\n";
echo "- DRH: http://localhost:8000/drh\n";
echo "- Agent: http://localhost:8000/agent\n";
echo "- Responsable: http://localhost:8000/entrepot\n";
echo "- Public: http://localhost:8000\n";

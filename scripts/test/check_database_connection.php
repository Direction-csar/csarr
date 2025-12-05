<?php

/**
 * Script de vérification de la connexion à la base de données
 */

echo "🔍 Vérification de la connexion à la base de données...\n";
echo "======================================================\n\n";

// Configurations à tester
$configs = [
    [
        'name' => 'Configuration actuelle',
        'host' => 'localhost',
        'database' => 'csar_platform_2025',
        'username' => 'laravel_user',
        'password' => 'csar@2025Host1'
    ],
    [
        'name' => 'Configuration XAMPP par défaut',
        'host' => 'localhost',
        'database' => 'csar_platform_2025',
        'username' => 'root',
        'password' => ''
    ],
    [
        'name' => 'Configuration alternative',
        'host' => '127.0.0.1',
        'database' => 'csar_platform_2025',
        'username' => 'root',
        'password' => ''
    ]
];

foreach ($configs as $config) {
    echo "🧪 Test: {$config['name']}\n";
    echo "   Host: {$config['host']}\n";
    echo "   Database: {$config['database']}\n";
    echo "   Username: {$config['username']}\n";
    echo "   Password: " . (empty($config['password']) ? '(vide)' : '***') . "\n";
    
    try {
        $pdo = new PDO(
            "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
            $config['username'],
            $config['password']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "   ✅ Connexion réussie !\n";
        
        // Vérifier les tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "   📊 Tables trouvées: " . count($tables) . "\n";
        
        if (count($tables) > 0) {
            echo "   📋 Tables: " . implode(', ', array_slice($tables, 0, 5));
            if (count($tables) > 5) {
                echo " et " . (count($tables) - 5) . " autres...";
            }
            echo "\n";
        }
        
        // Cette configuration fonctionne, on l'utilise
        echo "\n🎯 Configuration fonctionnelle trouvée !\n";
        echo "Utilisation de cette configuration pour la suite...\n\n";
        
        // Mettre à jour les fichiers avec cette configuration
        updateConfigFiles($config);
        break;
        
    } catch (PDOException $e) {
        echo "   ❌ Erreur: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

function updateConfigFiles($config) {
    echo "🔧 Mise à jour des fichiers de configuration...\n";
    
    // Mettre à jour le fichier .env
    $env_content = "APP_NAME=\"CSAR Platform 2025\"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST={$config['host']}
DB_PORT=3306
DB_DATABASE={$config['database']}
DB_USERNAME={$config['username']}
DB_PASSWORD={$config['password']}

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
";

    file_put_contents('.env', $env_content);
    echo "✅ Fichier .env mis à jour\n";
    
    // Mettre à jour les fichiers PHP directs
    $php_files = [
        'public/index-admin.php',
        'public/admin-direct.php'
    ];
    
    foreach ($php_files as $file) {
        if (file_exists($file)) {
            $content = file_get_contents($file);
            
            $content = preg_replace(
                '/\$db_host\s*=\s*[\'"][^\'"]*[\'"];/',
                "\$db_host = '{$config['host']}';",
                $content
            );
            
            $content = preg_replace(
                '/\$db_name\s*=\s*[\'"][^\'"]*[\'"];/',
                "\$db_name = '{$config['database']}';",
                $content
            );
            
            $content = preg_replace(
                '/\$db_user\s*=\s*[\'"][^\'"]*[\'"];/',
                "\$db_user = '{$config['username']}';",
                $content
            );
            
            $content = preg_replace(
                '/\$db_pass\s*=\s*[\'"][^\'"]*[\'"];/',
                "\$db_pass = '{$config['password']}';",
                $content
            );
            
            file_put_contents($file, $content);
            echo "✅ {$file} mis à jour\n";
        }
    }
    
    echo "\n🎉 Configuration unifiée terminée !\n";
    echo "Toutes les interfaces utilisent maintenant la même base de données.\n";
}

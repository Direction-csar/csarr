<?php

/**
 * Script de création de la base de données et des tables CSAR
 */

echo "🏗️ Création de la base de données CSAR...\n";
echo "==========================================\n\n";

// Configuration MySQL (utiliser root pour créer la base)
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_pass = '';

try {
    // Connexion à MySQL sans spécifier de base
    $pdo = new PDO("mysql:host=$mysql_host;charset=utf8mb4", $mysql_user, $mysql_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à MySQL réussie\n\n";

    // Créer la base de données
    $database_name = 'csar_platform_2025';
    echo "1️⃣ Création de la base de données '$database_name'...\n";
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Base de données créée\n\n";

    // Sélectionner la base de données
    $pdo->exec("USE `$database_name`");
    echo "2️⃣ Connexion à la base de données...\n";
    echo "✅ Connecté à la base '$database_name'\n\n";

    // Créer les tables principales
    echo "3️⃣ Création des tables...\n";

    // Table users
    $sql_users = "
    CREATE TABLE IF NOT EXISTS `users` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        `email_verified_at` timestamp NULL DEFAULT NULL,
        `password` varchar(255) NOT NULL,
        `role` varchar(50) NOT NULL DEFAULT 'user',
        `role_id` int(11) DEFAULT NULL,
        `two_factor_secret` text DEFAULT NULL,
        `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
        `two_factor_verified_at` timestamp NULL DEFAULT NULL,
        `two_factor_recovery_codes` text DEFAULT NULL,
        `remember_token` varchar(100) DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `users_email_unique` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_users);
    echo "✅ Table 'users' créée\n";

    // Table messages
    $sql_messages = "
    CREATE TABLE IF NOT EXISTS `messages` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `sujet` varchar(255) NOT NULL,
        `contenu` text NOT NULL,
        `expediteur` varchar(255) NOT NULL,
        `email_expediteur` varchar(255) NOT NULL,
        `telephone_expediteur` varchar(20) DEFAULT NULL,
        `lu` tinyint(1) NOT NULL DEFAULT 0,
        `reponse` text DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_messages);
    echo "✅ Table 'messages' créée\n";

    // Table notifications
    $sql_notifications = "
    CREATE TABLE IF NOT EXISTS `notifications` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `type` varchar(50) NOT NULL DEFAULT 'info',
        `title` varchar(255) NOT NULL,
        `message` text NOT NULL,
        `user_id` bigint(20) unsigned DEFAULT NULL,
        `read` tinyint(1) NOT NULL DEFAULT 0,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `notifications_user_id_foreign` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_notifications);
    echo "✅ Table 'notifications' créée\n";

    // Table contact_messages
    $sql_contact_messages = "
    CREATE TABLE IF NOT EXISTS `contact_messages` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `full_name` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        `phone` varchar(20) DEFAULT NULL,
        `subject` varchar(255) NOT NULL,
        `message` text NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_contact_messages);
    echo "✅ Table 'contact_messages' créée\n";

    // Table newsletter_subscribers
    $sql_newsletter = "
    CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `email` varchar(255) NOT NULL,
        `status` varchar(20) NOT NULL DEFAULT 'active',
        `subscribed_at` timestamp NULL DEFAULT NULL,
        `unsubscribed_at` timestamp NULL DEFAULT NULL,
        `source` varchar(50) DEFAULT 'website',
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `newsletter_subscribers_email_unique` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_newsletter);
    echo "✅ Table 'newsletter_subscribers' créée\n";

    // Table public_requests
    $sql_public_requests = "
    CREATE TABLE IF NOT EXISTS `public_requests` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        `phone` varchar(20) DEFAULT NULL,
        `type` varchar(100) NOT NULL,
        `description` text NOT NULL,
        `status` varchar(20) NOT NULL DEFAULT 'pending',
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_public_requests);
    echo "✅ Table 'public_requests' créée\n";

    // Table audit_logs
    $sql_audit_logs = "
    CREATE TABLE IF NOT EXISTS `audit_logs` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `action` varchar(100) NOT NULL,
        `model_type` varchar(100) NOT NULL,
        `model_id` bigint(20) unsigned DEFAULT NULL,
        `user_id` bigint(20) unsigned DEFAULT NULL,
        `ip_address` varchar(45) DEFAULT NULL,
        `user_agent` text DEFAULT NULL,
        `data` json DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `audit_logs_user_id_foreign` (`user_id`),
        KEY `audit_logs_model_type_model_id_index` (`model_type`, `model_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql_audit_logs);
    echo "✅ Table 'audit_logs' créée\n";

    echo "\n4️⃣ Insertion des utilisateurs par défaut...\n";

    // Insérer les utilisateurs par défaut
    $users = [
        [
            'name' => 'Administrateur CSAR',
            'email' => 'admin@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'admin',
            'role_id' => 1
        ],
        [
            'name' => 'Directeur Général',
            'email' => 'dg@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'dg',
            'role_id' => 2
        ],
        [
            'name' => 'Directeur RH',
            'email' => 'drh@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'drh',
            'role_id' => 3
        ],
        [
            'name' => 'Responsable Entrepôt',
            'email' => 'responsable@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'responsable',
            'role_id' => 4
        ],
        [
            'name' => 'Agent CSAR',
            'email' => 'agent@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'agent',
            'role_id' => 5
        ]
    ];

    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password, role, role_id, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ON DUPLICATE KEY UPDATE 
        name = VALUES(name), 
        password = VALUES(password), 
        role = VALUES(role), 
        role_id = VALUES(role_id)
    ");

    foreach ($users as $user) {
        $stmt->execute([
            $user['name'],
            $user['email'],
            $user['password'],
            $user['role'],
            $user['role_id']
        ]);
        echo "✅ Utilisateur {$user['email']} créé/mis à jour\n";
    }

    echo "\n5️⃣ Création de l'utilisateur MySQL pour l'application...\n";

    // Créer l'utilisateur MySQL pour l'application
    $app_user = 'laravel_user';
    $app_password = 'csar@2025Host1';

    try {
        $pdo->exec("CREATE USER IF NOT EXISTS '$app_user'@'localhost' IDENTIFIED BY '$app_password'");
        $pdo->exec("GRANT ALL PRIVILEGES ON `$database_name`.* TO '$app_user'@'localhost'");
        $pdo->exec("FLUSH PRIVILEGES");
        echo "✅ Utilisateur MySQL '$app_user' créé avec les permissions\n";
    } catch (PDOException $e) {
        echo "⚠️ Utilisateur MySQL peut-être déjà existant: " . $e->getMessage() . "\n";
    }

    echo "\n🎉 Base de données CSAR créée avec succès !\n";
    echo "==========================================\n\n";

    echo "📊 Résumé :\n";
    echo "- Base de données: $database_name\n";
    echo "- Utilisateur MySQL: $app_user\n";
    echo "- Tables créées: 7\n";
    echo "- Utilisateurs par défaut: 5\n\n";

    echo "🔗 Comptes de connexion :\n";
    echo "- Admin: admin@csar.sn / password\n";
    echo "- DG: dg@csar.sn / password\n";
    echo "- DRH: drh@csar.sn / password\n";
    echo "- Responsable: responsable@csar.sn / password\n";
    echo "- Agent: agent@csar.sn / password\n\n";

    echo "✅ Toutes les interfaces peuvent maintenant se connecter à la même base MySQL !\n";

} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré et que l'utilisateur 'root' a les permissions.\n";
}

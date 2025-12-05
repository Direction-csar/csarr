<?php

/**
 * Création des utilisateurs par défaut dans la base plateforme-csar
 */

echo "👥 CRÉATION UTILISATEURS PAR DÉFAUT\n";
echo "==================================\n\n";

// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'plateforme-csar';
$db_user = 'laravel_user';
$db_pass = 'csar@2025Host1';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données réussie\n\n";

    // 1. Créer la table users si elle n'existe pas
    echo "1️⃣ Création de la table users...\n";
    
    $createUsersTable = "
        CREATE TABLE IF NOT EXISTS users (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            email_verified_at TIMESTAMP NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'dg', 'drh', 'responsable', 'agent') NOT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
            remember_token VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_email (email),
            INDEX idx_role (role),
            INDEX idx_is_active (is_active)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createUsersTable);
    echo "   ✅ Table users créée/vérifiée\n";

    // 2. Vérifier si des utilisateurs existent
    echo "2️⃣ Vérification des utilisateurs existants...\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $count = $stmt->fetchColumn();
    echo "   📊 Utilisateurs existants: $count\n";

    // 3. Créer les utilisateurs par défaut
    echo "3️⃣ Création des utilisateurs par défaut...\n";
    
    $users = [
        [
            'name' => 'Administrateur CSAR',
            'email' => 'admin@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'admin',
            'is_active' => true,
            'status' => 'active'
        ],
        [
            'name' => 'Directeur Général',
            'email' => 'dg@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'dg',
            'is_active' => true,
            'status' => 'active'
        ],
        [
            'name' => 'Directeur RH',
            'email' => 'drh@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'drh',
            'is_active' => true,
            'status' => 'active'
        ],
        [
            'name' => 'Responsable Entrepôt',
            'email' => 'responsable@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'responsable',
            'is_active' => true,
            'status' => 'active'
        ],
        [
            'name' => 'Agent Terrain',
            'email' => 'agent@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'agent',
            'is_active' => true,
            'status' => 'active'
        ]
    ];
    
    $insertStmt = $pdo->prepare("
        INSERT INTO users (name, email, password, role, is_active, status, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    foreach ($users as $user) {
        try {
            $insertStmt->execute([
                $user['name'],
                $user['email'],
                $user['password'],
                $user['role'],
                $user['is_active'],
                $user['status']
            ]);
            echo "   ✅ Utilisateur créé: {$user['email']} ({$user['role']})\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                echo "   ⚠️ Utilisateur existe déjà: {$user['email']}\n";
            } else {
                echo "   ❌ Erreur création {$user['email']}: " . $e->getMessage() . "\n";
            }
        }
    }
    echo "\n";

    // 4. Vérification finale
    echo "4️⃣ Vérification finale...\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $count = $stmt->fetchColumn();
    echo "   📊 Total utilisateurs: $count\n";
    
    $stmt = $pdo->query("SELECT email, role, is_active FROM users ORDER BY role");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "   📋 Utilisateurs créés:\n";
    foreach ($users as $user) {
        $status = $user['is_active'] ? '✅ Actif' : '❌ Inactif';
        echo "      - {$user['email']} ({$user['role']}) - $status\n";
    }
    echo "\n";

    echo "🎉 CRÉATION TERMINÉE AVEC SUCCÈS !\n";
    echo "==================================\n";
    echo "✅ Table users créée\n";
    echo "✅ Utilisateurs par défaut créés\n";
    echo "✅ Tous les comptes sont actifs\n";
    echo "✅ Mot de passe: password (pour tous)\n";
    echo "\n";
    echo "🌐 MAINTENANT VOUS POUVEZ ACCÉDER À :\n";
    echo "📱 Interface Admin: http://localhost:8000/admin\n";
    echo "📦 Gestion des Stocks: http://localhost:8000/admin/stocks\n";
    echo "🏢 Gestion des Entrepôts: http://localhost:8000/admin/entrepots\n";
    echo "\n";
    echo "🔑 IDENTIFIANTS ADMIN:\n";
    echo "📧 Email: admin@csar.sn\n";
    echo "🔒 Mot de passe: password\n";
    echo "\n";
    echo "🔑 AUTRES IDENTIFIANTS:\n";
    echo "📧 DG: dg@csar.sn / password\n";
    echo "📧 DRH: drh@csar.sn / password\n";
    echo "📧 Responsable: responsable@csar.sn / password\n";
    echo "📧 Agent: agent@csar.sn / password\n";
    echo "\n";
    echo "✨ LA PLATEFORME EST PRÊTE AVEC LA BONNE BASE !\n";
    echo "🗄️ Base de données: plateforme-csar\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

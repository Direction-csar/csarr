<?php
/**
 * Script pour créer les utilisateurs et rôles de la plateforme CSAR
 */

echo "🔧 CRÉATION DES UTILISATEURS ET RÔLES - PLATEFORME CSAR\n";
echo "======================================================\n\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=csar_platform', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données réussie\n\n";
    
    // 1. Créer la table roles si elle n'existe pas
    echo "1. Création de la table roles\n";
    echo "-----------------------------\n";
    
    $sql = "CREATE TABLE IF NOT EXISTS roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "   ✅ Table 'roles' créée/vérifiée\n";
    
    // 2. Créer la table users si elle n'existe pas
    echo "\n2. Création de la table users\n";
    echo "-----------------------------\n";
    
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        email_verified_at TIMESTAMP NULL,
        password VARCHAR(255) NOT NULL,
        role_id INT,
        phone VARCHAR(20),
        is_active BOOLEAN DEFAULT TRUE,
        remember_token VARCHAR(100),
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        FOREIGN KEY (role_id) REFERENCES roles(id)
    )";
    
    $pdo->exec($sql);
    echo "   ✅ Table 'users' créée/vérifiée\n";
    
    // 3. Insérer les rôles
    echo "\n3. Insertion des rôles\n";
    echo "---------------------\n";
    
    $roles = [
        ['id' => 1, 'name' => 'admin', 'description' => 'Administrateur système'],
        ['id' => 2, 'name' => 'dg', 'description' => 'Directeur Général'],
        ['id' => 3, 'name' => 'responsable', 'description' => 'Responsable Entrepôt'],
        ['id' => 4, 'name' => 'agent', 'description' => 'Agent CSAR'],
        ['id' => 5, 'name' => 'drh', 'description' => 'Direction des Ressources Humaines']
    ];
    
    foreach ($roles as $role) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO roles (id, name, description) VALUES (?, ?, ?)");
        $stmt->execute([$role['id'], $role['name'], $role['description']]);
        echo "   ✅ Rôle '{$role['name']}' créé/vérifié\n";
    }
    
    // 4. Créer les utilisateurs
    echo "\n4. Création des utilisateurs\n";
    echo "---------------------------\n";
    
    $users = [
        [
            'name' => 'Administrateur',
            'email' => 'admin@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role_id' => 1,
            'phone' => '+221 70 123 45 67'
        ],
        [
            'name' => 'Directeur Général',
            'email' => 'dg@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role_id' => 2,
            'phone' => '+221 70 123 45 68'
        ],
        [
            'name' => 'Responsable Entrepôt',
            'email' => 'responsable@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role_id' => 3,
            'phone' => '+221 70 123 45 69'
        ],
        [
            'name' => 'Agent CSAR',
            'email' => 'agent@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role_id' => 4,
            'phone' => '+221 70 123 45 70'
        ],
        [
            'name' => 'DRH',
            'email' => 'drh@csar.sn',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role_id' => 5,
            'phone' => '+221 70 123 45 71'
        ]
    ];
    
    foreach ($users as $user) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO users (name, email, password, role_id, phone, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, TRUE, NOW(), NOW())");
        $stmt->execute([$user['name'], $user['email'], $user['password'], $user['role_id'], $user['phone']]);
        echo "   ✅ Utilisateur '{$user['name']}' créé/vérifié\n";
    }
    
    // 5. Vérification finale
    echo "\n5. Vérification finale\n";
    echo "---------------------\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $userCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "   📊 Nombre d'utilisateurs: {$userCount}\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM roles");
    $roleCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "   📊 Nombre de rôles: {$roleCount}\n";
    
    echo "\n";
    
    // 6. Afficher les identifiants de connexion
    echo "6. Identifiants de connexion\n";
    echo "---------------------------\n";
    
    $stmt = $pdo->query("SELECT u.name, u.email, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.role_id");
    $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($allUsers as $user) {
        $roleUrl = match($user['role_name']) {
            'admin' => 'admin',
            'dg' => 'dg',
            'responsable' => 'entrepot',
            'agent' => 'agent',
            'drh' => 'drh',
            default => 'login'
        };
        
        echo "   👤 {$user['name']}:\n";
        echo "      URL: http://localhost:8000/{$roleUrl}/login\n";
        echo "      Email: {$user['email']}\n";
        echo "      Password: password\n";
        echo "\n";
    }
    
    echo "🎉 CRÉATION TERMINÉE AVEC SUCCÈS!\n";
    echo "\n";
    echo "📋 Prochaines étapes:\n";
    echo "1. Démarrez le serveur Laravel: php artisan serve --host=0.0.0.0 --port=8000\n";
    echo "2. Ouvrez votre navigateur et allez sur l'URL correspondante à votre rôle\n";
    echo "3. Connectez-vous avec les identifiants ci-dessus\n";
    echo "4. En cas d'erreur 419, videz le cache de votre navigateur\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n";

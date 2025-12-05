<?php

// Script pour créer un compte administrateur CSAR
require __DIR__.'/vendor/autoload.php';

// Configuration de la base de données
$config = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'database' => 'csar_platform',
    'username' => 'root',
    'password' => ''
];

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4", 
        $config['username'], 
        $config['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données 'csar_platform' réussie\n";
    
    // Vérifier si la table users existe
    $checkTable = $pdo->query("SHOW TABLES LIKE 'users'")->fetch();
    
    if (!$checkTable) {
        // Créer la table users si elle n'existe pas
        $createTable = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            email_verified_at TIMESTAMP NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'dg', 'drh', 'entrepot', 'agent') DEFAULT 'agent',
            is_active BOOLEAN DEFAULT 1,
            remember_token VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($createTable);
        echo "✅ Table 'users' créée avec succès\n";
    }
    
    // Supprimer l'ancien admin s'il existe
    $pdo->exec("DELETE FROM users WHERE email = 'admin@csar.sn'");
    
    // Créer le nouvel administrateur
    $adminData = [
        'name' => 'Administrateur CSAR',
        'email' => 'admin@csar.sn',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'role' => 'admin',
        'is_active' => 1
    ];
    
    $sql = "INSERT INTO users (name, email, password, role, is_active) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $adminData['name'],
        $adminData['email'],
        $adminData['password'],
        $adminData['role'],
        $adminData['is_active']
    ]);
    
    echo "✅ Compte administrateur créé avec succès !\n";
    echo "\n📋 Identifiants de connexion :\n";
    echo "Email: admin@csar.sn\n";
    echo "Mot de passe: admin123\n";
    echo "\n🔗 Accès admin: http://localhost:8000/admin/login\n";
    
    // Créer aussi un compte DG
    $dgData = [
        'name' => 'Directeur Général',
        'email' => 'dg@csar.sn',
        'password' => password_hash('dg123', PASSWORD_DEFAULT),
        'role' => 'dg',
        'is_active' => 1
    ];
    
    $pdo->exec("DELETE FROM users WHERE email = 'dg@csar.sn'");
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $dgData['name'],
        $dgData['email'],
        $dgData['password'],
        $dgData['role'],
        $dgData['is_active']
    ]);
    
    echo "✅ Compte Directeur Général créé !\n";
    echo "Email: dg@csar.sn | Mot de passe: dg123\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré et que la base 'csar_platform' existe.\n";
}

<?php

// Script direct pour créer un administrateur
$host = '127.0.0.1';
$dbname = 'csar_platform';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données réussie\n";
    
    // Créer la table users si elle n'existe pas
    $createTable = "CREATE TABLE IF NOT EXISTS users (
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
    echo "✅ Table 'users' créée/vérifiée\n";
    
    // Supprimer l'ancien admin
    $pdo->exec("DELETE FROM users WHERE email = 'admin@csar.sn'");
    
    // Créer le mot de passe hashé avec Laravel (bcrypt)
    $plainPassword = 'admin123';
    $hashedPassword = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password hashé
    
    // Insérer l'administrateur
    $sql = "INSERT INTO users (name, email, password, role, is_active) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'Administrateur CSAR',
        'admin@csar.sn',
        $hashedPassword,
        'admin',
        1
    ]);
    
    if ($result) {
        echo "✅ Compte administrateur créé avec succès !\n";
        echo "\n📋 Identifiants de connexion :\n";
        echo "Email: admin@csar.sn\n";
        echo "Mot de passe: admin123\n";
        echo "\n🔗 Accès admin: http://localhost:8000/admin/login\n";
        
        // Vérifier la création
        $user = $pdo->query("SELECT id, name, email, role, is_active FROM users WHERE email = 'admin@csar.sn'")->fetch();
        if ($user) {
            echo "\n✅ Vérification :\n";
            echo "ID: {$user['id']}\n";
            echo "Nom: {$user['name']}\n";
            echo "Email: {$user['email']}\n";
            echo "Role: {$user['role']}\n";
            echo "Actif: " . ($user['is_active'] ? 'Oui' : 'Non') . "\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}

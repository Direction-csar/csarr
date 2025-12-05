<?php
/**
 * Script de réparation pour corriger le problème de connexion
 */

// Configuration de la base de données
$host = 'localhost';
$dbname = 'csar_platform';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== RÉPARATION DE LA CONNEXION CSAR ===\n\n";
    
    // 1. Vérifier si la table users existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        echo "❌ Table users manquante - Création en cours...\n";
        
        // Créer la table users
        $sql = "CREATE TABLE users (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            email_verified_at TIMESTAMP NULL,
            password VARCHAR(255) NOT NULL,
            role_id INT DEFAULT 1,
            phone VARCHAR(20),
            is_active BOOLEAN DEFAULT TRUE,
            remember_token VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $pdo->exec($sql);
        echo "✅ Table users créée\n";
    } else {
        echo "✅ Table users existe\n";
    }
    
    // 2. Créer l'utilisateur admin si nécessaire
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute(['admin@csar.sn']);
    $adminExists = $stmt->fetchColumn();
    
    if ($adminExists == 0) {
        echo "❌ Utilisateur admin manquant - Création en cours...\n";
        
        $hashedPassword = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role_id, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute(['Administrateur', 'admin@csar.sn', $hashedPassword, 1, true]);
        echo "✅ Utilisateur admin créé\n";
    } else {
        echo "✅ Utilisateur admin existe\n";
    }
    
    // 3. Vérifier le mot de passe admin
    $stmt = $pdo->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->execute(['admin@csar.sn']);
    $storedPassword = $stmt->fetchColumn();
    
    if ($storedPassword && password_verify('password', $storedPassword)) {
        echo "✅ Mot de passe admin correct\n";
    } else {
        echo "❌ Mot de passe admin incorrect - Réinitialisation...\n";
        $hashedPassword = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, 'admin@csar.sn']);
        echo "✅ Mot de passe admin réinitialisé\n";
    }
    
    // 4. Afficher les informations de connexion
    echo "\n=== INFORMATIONS DE CONNEXION ===\n";
    echo "URL: http://localhost:8000/admin/login\n";
    echo "Email: admin@csar.sn\n";
    echo "Password: password\n\n";
    
    // 5. Test de connexion
    echo "=== TEST DE CONNEXION ===\n";
    $stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE email = ?");
    $stmt->execute(['admin@csar.sn']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "✅ Utilisateur trouvé:\n";
        echo "   ID: " . $user['id'] . "\n";
        echo "   Nom: " . $user['name'] . "\n";
        echo "   Email: " . $user['email'] . "\n";
    } else {
        echo "❌ Utilisateur non trouvé\n";
    }
    
    echo "\n=== RÉPARATION TERMINÉE ===\n";
    echo "Vous pouvez maintenant essayer de vous connecter avec:\n";
    echo "Email: admin@csar.sn\n";
    echo "Password: password\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de base de données: " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré dans XAMPP\n";
}
?>

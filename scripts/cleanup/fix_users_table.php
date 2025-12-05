<?php

// Script pour corriger la structure de la table users
$host = '127.0.0.1';
$dbname = 'csar_platform';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données réussie\n";
    
    // Vérifier la structure actuelle de la table users
    $columns = $pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_COLUMN);
    echo "📋 Colonnes actuelles : " . implode(', ', $columns) . "\n";
    
    // Ajouter les colonnes manquantes
    $alterations = [
        "ALTER TABLE users ADD COLUMN role ENUM('admin', 'dg', 'drh', 'entrepot', 'agent') DEFAULT 'agent'",
        "ALTER TABLE users ADD COLUMN is_active BOOLEAN DEFAULT 1"
    ];
    
    foreach ($alterations as $sql) {
        try {
            $pdo->exec($sql);
            echo "✅ Colonne ajoutée avec succès\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                echo "⚠️  Colonne déjà existante\n";
            } else {
                echo "❌ Erreur : " . $e->getMessage() . "\n";
            }
        }
    }
    
    // Vérifier la nouvelle structure
    $newColumns = $pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_COLUMN);
    echo "\n📋 Nouvelles colonnes : " . implode(', ', $newColumns) . "\n";
    
    // Créer l'utilisateur admin
    $pdo->exec("DELETE FROM users WHERE email = 'admin@csar.sn'");
    
    $sql = "INSERT INTO users (name, email, password, role, is_active) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'Administrateur CSAR',
        'admin@csar.sn',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password hashé
        'admin',
        1
    ]);
    
    if ($result) {
        echo "\n✅ Compte administrateur créé avec succès !\n";
        echo "📋 Identifiants de connexion :\n";
        echo "Email: admin@csar.sn\n";
        echo "Mot de passe: admin123\n";
        echo "\n🔗 Accès admin: http://localhost:8000/admin/login\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}

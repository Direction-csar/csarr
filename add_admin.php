<?php

// Script simple pour ajouter rapidement un utilisateur admin

require_once 'vendor/autoload.php';

// Configuration de la base de données
$config = [
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'port' => '3306',
    'database' => 'csar',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}", 
        $config['username'], 
        $config['password']
    );
    
    echo "🔧 Ajout rapide d'un utilisateur admin\n";
    echo str_repeat("=", 40) . "\n\n";
    
    // Paramètres par défaut ou demander à l'utilisateur
    $name = $argv[1] ?? null;
    $email = $argv[2] ?? null;
    $password = $argv[3] ?? null;
    
    if (!$name) {
        echo "Nom complet: ";
        $name = trim(fgets(STDIN));
    }
    
    if (!$email) {
        echo "Email: ";
        $email = trim(fgets(STDIN));
    }
    
    if (!$password) {
        echo "Mot de passe: ";
        $password = trim(fgets(STDIN));
    }
    
    if (empty($name) || empty($email) || empty($password)) {
        echo "❌ Tous les champs sont obligatoires!\n";
        exit(1);
    }
    
    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        echo "❌ Cet email existe déjà!\n";
        exit(1);
    }
    
    // Créer l'utilisateur admin
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, email_verified_at, password, role, is_active, created_at, updated_at) 
        VALUES (?, ?, NOW(), ?, 'admin', 1, NOW(), NOW())
    ");
    
    $stmt->execute([$name, $email, $hashedPassword]);
    
    echo "✅ Utilisateur admin créé avec succès!\n";
    echo "👤 Nom: $name\n";
    echo "📧 Email: $email\n";
    echo "🔑 Mot de passe: $password\n";
    echo "🎯 Rôle: admin\n\n";
    
    // Afficher tous les utilisateurs admin
    $stmt = $pdo->query("SELECT id, name, email, created_at FROM users WHERE role = 'admin' ORDER BY created_at DESC");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📋 Liste des administrateurs:\n";
    echo str_repeat("-", 80) . "\n";
    printf("%-3s %-25s %-30s %-20s\n", "ID", "Nom", "Email", "Créé le");
    echo str_repeat("-", 80) . "\n";
    
    foreach ($admins as $admin) {
        printf("%-3s %-25s %-30s %-20s\n", 
            $admin['id'], 
            substr($admin['name'], 0, 25), 
            substr($admin['email'], 0, 30), 
            date('d/m/Y H:i', strtotime($admin['created_at']))
        );
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion à MySQL: " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré et que les paramètres sont corrects.\n";
}

echo "\n🎯 Utilisation:\n";
echo "php add_admin.php \"Nom Complet\" \"email@example.com\" \"motdepasse\"\n";
echo "ou simplement: php add_admin.php (pour saisie interactive)\n";

<?php

// Script pour configurer la base de données MySQL et créer un utilisateur admin par défaut

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Configuration de la base de données MySQL
$config = [
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'port' => '3306',
    'database' => 'csar',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
];

try {
    // Test de connexion à MySQL
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}", 
        $config['username'], 
        $config['password']
    );
    
    echo "✅ Connexion à MySQL réussie!\n";
    
    // Créer la base de données si elle n'existe pas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Base de données '{$config['database']}' créée/vérifiée!\n";
    
    // Se connecter à la base de données spécifique
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}", 
        $config['username'], 
        $config['password']
    );
    
    echo "✅ Connexion à la base de données '{$config['database']}' réussie!\n";
    
    // Vérifier si la table users existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table 'users' existe!\n";
        
        // Vérifier si l'utilisateur admin existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute(['admin@csar.sn']);
        $count = $stmt->fetchColumn();
        
        if ($count == 0) {
            // Créer l'utilisateur admin par défaut
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, email_verified_at, password, role, created_at, updated_at) 
                VALUES (?, ?, NOW(), ?, ?, NOW(), NOW())
            ");
            
            $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt->execute([
                'Administrateur CSAR',
                'admin@csar.sn',
                $hashedPassword,
                'admin'
            ]);
            
            echo "✅ Utilisateur admin créé avec succès!\n";
            echo "📧 Email: admin@csar.sn\n";
            echo "🔑 Mot de passe: admin123\n";
        } else {
            echo "ℹ️  Utilisateur admin existe déjà!\n";
        }
        
        // Afficher tous les utilisateurs
        $stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "\n📋 Liste des utilisateurs:\n";
        echo str_repeat("-", 80) . "\n";
        printf("%-3s %-25s %-30s %-10s %-20s\n", "ID", "Nom", "Email", "Rôle", "Créé le");
        echo str_repeat("-", 80) . "\n";
        
        foreach ($users as $user) {
            printf("%-3s %-25s %-30s %-10s %-20s\n", 
                $user['id'], 
                substr($user['name'], 0, 25), 
                substr($user['email'], 0, 30), 
                $user['role'], 
                date('d/m/Y H:i', strtotime($user['created_at']))
            );
        }
        
    } else {
        echo "❌ Table 'users' n'existe pas. Exécutez d'abord les migrations Laravel.\n";
        echo "Commande: php artisan migrate\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion à MySQL: " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré et que les paramètres sont corrects.\n";
}

echo "\n🎯 Configuration terminée!\n";

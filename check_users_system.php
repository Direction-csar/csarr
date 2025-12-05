<?php

// Script de vérification du système de gestion des utilisateurs

require_once 'vendor/autoload.php';

echo "🔍 Vérification du système de gestion des utilisateurs CSAR\n";
echo str_repeat("=", 60) . "\n\n";

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
    
    echo "✅ Connexion à MySQL réussie!\n";
    echo "✅ Base de données 'csar' accessible!\n\n";
    
    // Vérifier les tables
    $tables = ['users', 'personnel', 'warehouses', 'stocks', 'demandes'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Table '$table' existe\n";
        } else {
            echo "❌ Table '$table' manquante\n";
        }
    }
    
    echo "\n";
    
    // Vérifier les utilisateurs par rôle
    $roles = ['admin', 'drh', 'dg', 'agent'];
    foreach ($roles as $role) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = ?");
        $stmt->execute([$role]);
        $count = $stmt->fetchColumn();
        echo "👥 Utilisateurs $role: $count\n";
    }
    
    echo "\n";
    
    // Afficher tous les utilisateurs
    $stmt = $pdo->query("SELECT id, name, email, role, is_active, created_at FROM users ORDER BY role, created_at DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📋 Liste complète des utilisateurs:\n";
    echo str_repeat("-", 100) . "\n";
    printf("%-3s %-25s %-30s %-10s %-8s %-20s\n", "ID", "Nom", "Email", "Rôle", "Actif", "Créé le");
    echo str_repeat("-", 100) . "\n";
    
    foreach ($users as $user) {
        $status = $user['is_active'] ? 'Oui' : 'Non';
        printf("%-3s %-25s %-30s %-10s %-8s %-20s\n", 
            $user['id'], 
            substr($user['name'], 0, 25), 
            substr($user['email'], 0, 30), 
            $user['role'], 
            $status,
            date('d/m/Y H:i', strtotime($user['created_at']))
        );
    }
    
    echo "\n";
    
    // Vérifier les URLs d'accès
    echo "🌐 URLs d'accès au système:\n";
    echo "• Admin: http://localhost:8000/admin/login\n";
    echo "• DRH: http://localhost:8000/drh/login\n";
    echo "• DG: http://localhost:8000/dg/login\n";
    echo "• Agent: http://localhost:8000/agent/login\n";
    echo "• Personnel: http://localhost:8000/admin/personnel\n";
    
    echo "\n";
    
    // Informations de connexion par défaut
    echo "🔑 Utilisateurs par défaut créés:\n";
    $stmt = $pdo->query("SELECT name, email, role FROM users WHERE role = 'admin' ORDER BY created_at ASC LIMIT 3");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($admins as $admin) {
        echo "• {$admin['name']} ({$admin['email']}) - {$admin['role']}\n";
    }
    
    echo "\n";
    echo "📝 Scripts disponibles:\n";
    echo "• php manage_users.php - Menu interactif complet\n";
    echo "• php add_admin.php - Ajout rapide d'admin\n";
    echo "• php setup_mysql_admin.php - Configuration initiale\n";
    
    echo "\n✅ Système de gestion des utilisateurs opérationnel!\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion à MySQL: " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré et que les paramètres sont corrects.\n";
}

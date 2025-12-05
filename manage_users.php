<?php

// Script pour ajouter des utilisateurs admin facilement

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

echo "🔧 Gestion des Utilisateurs Admin CSAR\n";
echo str_repeat("=", 50) . "\n\n";

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
    
    echo "✅ Connexion à MySQL réussie!\n\n";
    
    // Menu interactif
    while (true) {
        echo "📋 Menu de gestion des utilisateurs:\n";
        echo "1. Lister tous les utilisateurs\n";
        echo "2. Ajouter un utilisateur admin\n";
        echo "3. Ajouter un utilisateur DRH\n";
        echo "4. Ajouter un utilisateur DG\n";
        echo "5. Ajouter un utilisateur Agent\n";
        echo "6. Supprimer un utilisateur\n";
        echo "7. Modifier le mot de passe d'un utilisateur\n";
        echo "0. Quitter\n\n";
        
        echo "Votre choix: ";
        $choice = trim(fgets(STDIN));
        
        switch ($choice) {
            case '1':
                // Lister tous les utilisateurs
                $stmt = $pdo->query("SELECT id, name, email, role, is_active, created_at FROM users ORDER BY created_at DESC");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo "\n📋 Liste des utilisateurs:\n";
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
                break;
                
            case '2':
            case '3':
            case '4':
            case '5':
                // Ajouter un utilisateur
                $roles = [
                    '2' => 'admin',
                    '3' => 'drh', 
                    '4' => 'dg',
                    '5' => 'agent'
                ];
                
                $roleNames = [
                    '2' => 'Administrateur',
                    '3' => 'DRH',
                    '4' => 'Directeur Général', 
                    '5' => 'Agent'
                ];
                
                $role = $roles[$choice];
                $roleName = $roleNames[$choice];
                
                echo "\n➕ Ajout d'un utilisateur $roleName:\n";
                echo "Nom complet: ";
                $name = trim(fgets(STDIN));
                
                echo "Email: ";
                $email = trim(fgets(STDIN));
                
                echo "Mot de passe: ";
                $password = trim(fgets(STDIN));
                
                if (empty($name) || empty($email) || empty($password)) {
                    echo "❌ Tous les champs sont obligatoires!\n\n";
                    break;
                }
                
                // Vérifier si l'email existe déjà
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetchColumn() > 0) {
                    echo "❌ Cet email existe déjà!\n\n";
                    break;
                }
                
                // Créer l'utilisateur
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    INSERT INTO users (name, email, email_verified_at, password, role, is_active, created_at, updated_at) 
                    VALUES (?, ?, NOW(), ?, ?, 1, NOW(), NOW())
                ");
                
                $stmt->execute([$name, $email, $hashedPassword, $role]);
                
                echo "✅ Utilisateur $roleName créé avec succès!\n";
                echo "📧 Email: $email\n";
                echo "🔑 Mot de passe: $password\n\n";
                break;
                
            case '6':
                // Supprimer un utilisateur
                echo "\n🗑️ Suppression d'un utilisateur:\n";
                echo "ID de l'utilisateur à supprimer: ";
                $userId = trim(fgets(STDIN));
                
                if (!is_numeric($userId)) {
                    echo "❌ ID invalide!\n\n";
                    break;
                }
                
                // Vérifier si l'utilisateur existe
                $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$user) {
                    echo "❌ Utilisateur non trouvé!\n\n";
                    break;
                }
                
                echo "⚠️ Êtes-vous sûr de vouloir supprimer {$user['name']} ({$user['email']})? (oui/non): ";
                $confirm = trim(fgets(STDIN));
                
                if (strtolower($confirm) === 'oui') {
                    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                    $stmt->execute([$userId]);
                    echo "✅ Utilisateur supprimé avec succès!\n\n";
                } else {
                    echo "❌ Suppression annulée.\n\n";
                }
                break;
                
            case '7':
                // Modifier le mot de passe
                echo "\n🔑 Modification du mot de passe:\n";
                echo "ID de l'utilisateur: ";
                $userId = trim(fgets(STDIN));
                
                if (!is_numeric($userId)) {
                    echo "❌ ID invalide!\n\n";
                    break;
                }
                
                // Vérifier si l'utilisateur existe
                $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$user) {
                    echo "❌ Utilisateur non trouvé!\n\n";
                    break;
                }
                
                echo "Nouveau mot de passe pour {$user['name']}: ";
                $newPassword = trim(fgets(STDIN));
                
                if (empty($newPassword)) {
                    echo "❌ Le mot de passe ne peut pas être vide!\n\n";
                    break;
                }
                
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$hashedPassword, $userId]);
                
                echo "✅ Mot de passe modifié avec succès!\n\n";
                break;
                
            case '0':
                echo "👋 Au revoir!\n";
                exit(0);
                
            default:
                echo "❌ Choix invalide!\n\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion à MySQL: " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré et que les paramètres sont corrects.\n";
}

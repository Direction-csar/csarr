<?php

/**
 * Vérification et réactivation du compte administrateur
 */

echo "🔍 Vérification du statut du compte administrateur\n";
echo "================================================\n\n";

// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'csar_platform_2025';
$db_user = 'laravel_user';
$db_pass = 'csar@2025Host1';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données réussie\n\n";

    // 1. Vérifier le statut du compte admin
    echo "1️⃣ Vérification du compte administrateur...\n";
    
    $stmt = $pdo->prepare("SELECT id, name, email, role, status, is_active, created_at, updated_at FROM users WHERE email = ?");
    $stmt->execute(['admin@csar.sn']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "   📊 Compte admin trouvé:\n";
        echo "      - ID: {$admin['id']}\n";
        echo "      - Nom: {$admin['name']}\n";
        echo "      - Email: {$admin['email']}\n";
        echo "      - Rôle: {$admin['role']}\n";
        echo "      - Statut: {$admin['status']}\n";
        echo "      - Actif: " . ($admin['is_active'] ? 'Oui' : 'Non') . "\n";
        echo "      - Créé: {$admin['created_at']}\n";
        echo "      - Modifié: {$admin['updated_at']}\n\n";
        
        // 2. Réactiver le compte si nécessaire
        if (!$admin['is_active']) {
            echo "2️⃣ Réactivation du compte administrateur...\n";
            
            $stmt = $pdo->prepare("UPDATE users SET is_active = 1, status = 'active', updated_at = NOW() WHERE email = ?");
            $result = $stmt->execute(['admin@csar.sn']);
            
            if ($result) {
                echo "   ✅ Compte administrateur réactivé avec succès\n";
            } else {
                echo "   ❌ Erreur lors de la réactivation\n";
            }
        } else {
            echo "2️⃣ Le compte administrateur est déjà actif\n";
        }
        
        // 3. Vérifier le mot de passe
        echo "\n3️⃣ Vérification du mot de passe...\n";
        
        $stmt = $pdo->prepare("SELECT password FROM users WHERE email = ?");
        $stmt->execute(['admin@csar.sn']);
        $passwordHash = $stmt->fetchColumn();
        
        if ($passwordHash) {
            echo "   ✅ Mot de passe configuré\n";
            
            // Tester avec le mot de passe par défaut
            if (password_verify('password', $passwordHash)) {
                echo "   ✅ Mot de passe par défaut 'password' valide\n";
            } else {
                echo "   ⚠️ Mot de passe par défaut 'password' invalide\n";
                echo "   🔧 Réinitialisation du mot de passe...\n";
                
                $newPassword = password_hash('password', PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE email = ?");
                $result = $stmt->execute([$newPassword, 'admin@csar.sn']);
                
                if ($result) {
                    echo "   ✅ Mot de passe réinitialisé à 'password'\n";
                } else {
                    echo "   ❌ Erreur lors de la réinitialisation du mot de passe\n";
                }
            }
        } else {
            echo "   ❌ Aucun mot de passe trouvé\n";
        }
        
    } else {
        echo "   ❌ Compte administrateur non trouvé\n";
        echo "   🔧 Création du compte administrateur...\n";
        
        $password = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, role, status, is_active, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        
        $result = $stmt->execute([
            'Administrateur CSAR',
            'admin@csar.sn',
            $password,
            'admin',
            'active',
            1
        ]);
        
        if ($result) {
            $adminId = $pdo->lastInsertId();
            echo "   ✅ Compte administrateur créé avec l'ID: $adminId\n";
        } else {
            echo "   ❌ Erreur lors de la création du compte\n";
        }
    }
    
    // 4. Vérification finale
    echo "\n4️⃣ Vérification finale...\n";
    
    $stmt = $pdo->prepare("SELECT id, name, email, role, status, is_active FROM users WHERE email = ?");
    $stmt->execute(['admin@csar.sn']);
    $finalAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($finalAdmin && $finalAdmin['is_active']) {
        echo "   ✅ Compte administrateur opérationnel:\n";
        echo "      - Email: {$finalAdmin['email']}\n";
        echo "      - Mot de passe: password\n";
        echo "      - Statut: {$finalAdmin['status']}\n";
        echo "      - Actif: " . ($finalAdmin['is_active'] ? 'Oui' : 'Non') . "\n\n";
        
        echo "🎉 CONNEXION ADMIN DISPONIBLE !\n";
        echo "==============================\n";
        echo "Vous pouvez maintenant vous connecter avec :\n";
        echo "📧 Email: admin@csar.sn\n";
        echo "🔑 Mot de passe: password\n";
        echo "🔗 URL: http://localhost:8000/admin\n\n";
        
    } else {
        echo "   ❌ Problème persistant avec le compte administrateur\n";
    }
    
    // 5. Vérifier les autres comptes
    echo "5️⃣ Vérification des autres comptes...\n";
    
    $stmt = $pdo->query("SELECT email, role, status, is_active FROM users ORDER BY role");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   📊 Tous les comptes:\n";
    foreach ($users as $user) {
        $status = $user['is_active'] ? '✅ Actif' : '❌ Inactif';
        echo "      - {$user['email']} ({$user['role']}) - {$user['status']} - $status\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

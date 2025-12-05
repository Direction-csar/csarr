<?php

/**
 * Script pour corriger tous les comptes utilisateurs
 */

echo "🔧 Correction de tous les comptes utilisateurs\n";
echo "============================================\n\n";

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

    // Comptes à créer/corriger
    $accounts = [
        [
            'name' => 'Administrateur CSAR',
            'email' => 'admin@csar.sn',
            'role' => 'admin',
            'password' => 'password'
        ],
        [
            'name' => 'Directeur Général',
            'email' => 'dg@csar.sn',
            'role' => 'dg',
            'password' => 'password'
        ],
        [
            'name' => 'Directeur RH',
            'email' => 'drh@csar.sn',
            'role' => 'drh',
            'password' => 'password'
        ],
        [
            'name' => 'Responsable Entrepôt',
            'email' => 'responsable@csar.sn',
            'role' => 'responsable',
            'password' => 'password'
        ],
        [
            'name' => 'Agent CSAR',
            'email' => 'agent@csar.sn',
            'role' => 'agent',
            'password' => 'password'
        ]
    ];

    echo "1️⃣ Vérification et correction des comptes...\n";
    
    foreach ($accounts as $account) {
        echo "\n   🔍 Traitement du compte: {$account['email']} ({$account['role']})\n";
        
        // Vérifier si le compte existe
        $stmt = $pdo->prepare("SELECT id, is_active, status FROM users WHERE email = ?");
        $stmt->execute([$account['email']]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            echo "      📊 Compte existant trouvé (ID: {$existing['id']})\n";
            echo "      📊 Statut actuel: " . ($existing['is_active'] ? 'Actif' : 'Inactif') . " / {$existing['status']}\n";
            
            // Réactiver et corriger le compte
            $passwordHash = password_hash($account['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                UPDATE users SET 
                    name = ?, 
                    password = ?, 
                    role = ?, 
                    status = 'active', 
                    is_active = 1, 
                    updated_at = NOW() 
                WHERE email = ?
            ");
            
            $result = $stmt->execute([
                $account['name'],
                $passwordHash,
                $account['role'],
                $account['email']
            ]);
            
            if ($result) {
                echo "      ✅ Compte mis à jour et réactivé\n";
            } else {
                echo "      ❌ Erreur lors de la mise à jour\n";
            }
            
        } else {
            echo "      📊 Compte non trouvé, création...\n";
            
            // Créer le nouveau compte
            $passwordHash = password_hash($account['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, password, role, status, is_active, created_at, updated_at) 
                VALUES (?, ?, ?, ?, 'active', 1, NOW(), NOW())
            ");
            
            $result = $stmt->execute([
                $account['name'],
                $account['email'],
                $passwordHash,
                $account['role']
            ]);
            
            if ($result) {
                $userId = $pdo->lastInsertId();
                echo "      ✅ Compte créé avec l'ID: $userId\n";
            } else {
                echo "      ❌ Erreur lors de la création\n";
            }
        }
    }
    
    // 2. Vérification finale
    echo "\n2️⃣ Vérification finale de tous les comptes...\n";
    
    $stmt = $pdo->query("SELECT email, name, role, status, is_active FROM users ORDER BY role");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   📊 Tous les comptes dans la base de données:\n";
    foreach ($users as $user) {
        $status = $user['is_active'] ? '✅ Actif' : '❌ Inactif';
        echo "      - {$user['email']} ({$user['role']}) - {$user['status']} - $status\n";
    }
    
    // 3. Test de connexion
    echo "\n3️⃣ Test de connexion des comptes...\n";
    
    foreach ($accounts as $account) {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE email = ? AND is_active = 1");
        $stmt->execute([$account['email']]);
        $passwordHash = $stmt->fetchColumn();
        
        if ($passwordHash && password_verify($account['password'], $passwordHash)) {
            echo "      ✅ {$account['email']}: Connexion possible\n";
        } else {
            echo "      ❌ {$account['email']}: Problème de connexion\n";
        }
    }
    
    echo "\n🎉 CORRECTION TERMINÉE !\n";
    echo "=======================\n\n";
    
    echo "🔗 INTERFACES DISPONIBLES:\n";
    echo "========================\n";
    echo "📱 Interface Publique: http://localhost:8000/\n";
    echo "👨‍💼 Interface Admin: http://localhost:8000/admin (admin@csar.sn / password)\n";
    echo "👔 Interface DG: http://localhost:8000/dg (dg@csar.sn / password)\n";
    echo "👥 Interface DRH: http://localhost:8000/drh (drh@csar.sn / password)\n";
    echo "📦 Interface Responsable: http://localhost:8000/entrepot (responsable@csar.sn / password)\n";
    echo "👤 Interface Agent: http://localhost:8000/agent (agent@csar.sn / password)\n\n";
    
    echo "🔑 MOTS DE PASSE: Tous les comptes utilisent le mot de passe 'password'\n";
    echo "✅ Tous les comptes sont actifs et prêts à être utilisés !\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

<?php

/**
 * Script pour corriger la structure de la table users
 */

echo "🔧 Correction de la structure de la table users\n";
echo "=============================================\n\n";

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

    // 1. Ajouter la colonne status
    echo "1️⃣ Ajout de la colonne status...\n";
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN status VARCHAR(50) DEFAULT 'active' AFTER role");
        echo "   ✅ Colonne status ajoutée\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "   ⚠️ Colonne status déjà présente\n";
        } else {
            echo "   ❌ Erreur: " . $e->getMessage() . "\n";
        }
    }

    // 2. Ajouter la colonne is_active
    echo "2️⃣ Ajout de la colonne is_active...\n";
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN is_active TINYINT(1) DEFAULT 1 AFTER status");
        echo "   ✅ Colonne is_active ajoutée\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "   ⚠️ Colonne is_active déjà présente\n";
        } else {
            echo "   ❌ Erreur: " . $e->getMessage() . "\n";
        }
    }

    // 3. Mettre à jour tous les utilisateurs existants
    echo "3️⃣ Mise à jour des utilisateurs existants...\n";
    
    $stmt = $pdo->query("UPDATE users SET status = 'active', is_active = 1 WHERE status IS NULL OR is_active IS NULL");
    $affected = $stmt->rowCount();
    echo "   ✅ $affected utilisateurs mis à jour\n";

    // 4. Vérifier la structure finale
    echo "4️⃣ Vérification de la structure finale...\n";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   📊 Colonnes de la table users:\n";
    foreach ($columns as $column) {
        echo "      - {$column['Field']} ({$column['Type']})\n";
    }
    echo "\n";

    // 5. Vérifier les utilisateurs
    echo "5️⃣ Vérification des utilisateurs...\n";
    
    $stmt = $pdo->query("SELECT id, email, role, status, is_active FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   📊 Utilisateurs dans la base:\n";
    foreach ($users as $user) {
        $status = $user['is_active'] ? '✅ Actif' : '❌ Inactif';
        echo "      - {$user['email']} ({$user['role']}) - {$user['status']} - $status\n";
    }
    echo "\n";

    // 6. Réinitialiser les mots de passe
    echo "6️⃣ Réinitialisation des mots de passe...\n";
    
    $accounts = [
        'admin@csar.sn' => 'admin',
        'dg@csar.sn' => 'dg',
        'drh@csar.sn' => 'drh',
        'responsable@csar.sn' => 'responsable',
        'agent@csar.sn' => 'agent'
    ];
    
    foreach ($accounts as $email => $role) {
        $password = 'password';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE email = ?");
        $result = $stmt->execute([$passwordHash, $email]);
        
        if ($result) {
            echo "   ✅ Mot de passe réinitialisé pour $email\n";
        } else {
            echo "   ❌ Erreur pour $email\n";
        }
    }
    
    echo "\n🎉 STRUCTURE DE LA TABLE CORRIGÉE !\n";
    echo "==================================\n\n";
    
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

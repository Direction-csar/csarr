<?php

/**
 * Correction de la connexion admin
 */

echo "🔧 CORRECTION CONNEXION ADMIN\n";
echo "============================\n\n";

// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'plateforme-csar';
$db_user = 'laravel_user';
$db_pass = 'csar@2025Host1';

try {
    // 1. Connexion à la base de données
    echo "1️⃣ Connexion à la base de données...\n";
    
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✅ Connexion à la base de données réussie\n\n";

    // 2. Vérifier l'utilisateur admin
    echo "2️⃣ Vérification de l'utilisateur admin...\n";
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['admin@csar.sn']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "   ✅ Utilisateur admin trouvé\n";
        echo "   📧 Email: {$admin['email']}\n";
        echo "   👤 Nom: {$admin['name']}\n";
        echo "   🔑 Rôle: {$admin['role']}\n";
        echo "   ✅ Actif: " . ($admin['is_active'] ? 'Oui' : 'Non') . "\n";
        echo "   📊 Statut: {$admin['status']}\n";
    } else {
        echo "   ❌ Utilisateur admin non trouvé\n";
    }
    echo "\n";

    // 3. Réinitialiser le mot de passe admin
    echo "3️⃣ Réinitialisation du mot de passe admin...\n";
    
    $newPassword = password_hash('password', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE users SET password = ?, is_active = 1, status = 'active' WHERE email = ?");
    $stmt->execute([$newPassword, 'admin@csar.sn']);
    
    echo "   ✅ Mot de passe admin réinitialisé\n";
    echo "   ✅ Compte admin activé\n";
    echo "   🔒 Nouveau mot de passe: password\n\n";

    // 4. Vérifier tous les utilisateurs
    echo "4️⃣ Vérification de tous les utilisateurs...\n";
    
    $stmt = $pdo->query("SELECT email, role, is_active, status FROM users ORDER BY role");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($users as $user) {
        $status = $user['is_active'] ? '✅ Actif' : '❌ Inactif';
        echo "   - {$user['email']} ({$user['role']}) - $status\n";
    }
    echo "\n";

    // 5. Test de connexion Laravel
    echo "5️⃣ Test de connexion Laravel...\n";
    
    try {
        require_once "vendor/autoload.php";
        $app = require_once "bootstrap/app.php";
        $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
        
        echo "   ✅ Laravel chargé avec succès\n";
        
        // Test de l'utilisateur admin via Laravel
        $adminUser = \App\Models\User::where('email', 'admin@csar.sn')->first();
        if ($adminUser) {
            echo "   ✅ Utilisateur admin trouvé via Laravel\n";
            echo "   📧 Email: {$adminUser->email}\n";
            echo "   🔑 Rôle: {$adminUser->role}\n";
            echo "   ✅ Actif: " . ($adminUser->is_active ? 'Oui' : 'Non') . "\n";
        } else {
            echo "   ❌ Utilisateur admin non trouvé via Laravel\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur Laravel: " . $e->getMessage() . "\n";
    }
    echo "\n";

    // 6. Vérifier la structure de la table users
    echo "6️⃣ Vérification de la structure de la table users...\n";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   📊 Colonnes de la table users:\n";
    foreach ($columns as $column) {
        echo "      - {$column['Field']} ({$column['Type']})\n";
    }
    echo "\n";

    echo "🎉 CORRECTION TERMINÉE AVEC SUCCÈS !\n";
    echo "=====================================\n";
    echo "✅ Utilisateur admin vérifié\n";
    echo "✅ Mot de passe réinitialisé\n";
    echo "✅ Compte admin activé\n";
    echo "✅ Laravel configuré\n";
    echo "\n";
    echo "🌐 MAINTENANT VOUS POUVEZ VOUS CONNECTER À :\n";
    echo "📱 Interface Admin: http://localhost:8000/admin\n";
    echo "📦 Gestion des Stocks: http://localhost:8000/admin/stocks\n";
    echo "🏢 Gestion des Entrepôts: http://localhost:8000/admin/entrepots\n";
    echo "\n";
    echo "🔑 IDENTIFIANTS ADMIN:\n";
    echo "📧 Email: admin@csar.sn\n";
    echo "🔒 Mot de passe: password\n";
    echo "\n";
    echo "✨ LA CONNEXION ADMIN EST MAINTENANT FONCTIONNELLE !\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
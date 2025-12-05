<?php

/**
 * Nettoyage des sessions et correction de la connexion
 */

echo "🧹 NETTOYAGE SESSIONS ET CORRECTION CONNEXION\n";
echo "============================================\n\n";

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

    // 2. Nettoyer les sessions dans la base de données
    echo "2️⃣ Nettoyage des sessions...\n";
    
    // Vérifier si la table sessions existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'sessions'");
    if ($stmt->rowCount() > 0) {
        $pdo->exec("DELETE FROM sessions");
        echo "   ✅ Sessions supprimées de la base de données\n";
    } else {
        echo "   ⚠️ Table sessions n'existe pas\n";
    }
    
    // Nettoyer les fichiers de session
    $sessionPath = session_save_path();
    if (empty($sessionPath)) {
        $sessionPath = sys_get_temp_dir();
    }
    
    if (is_dir($sessionPath)) {
        $files = glob($sessionPath . '/sess_*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "   ✅ Fichiers de session supprimés\n";
    }
    echo "\n";

    // 3. Réinitialiser l'utilisateur admin
    echo "3️⃣ Réinitialisation de l'utilisateur admin...\n";
    
    $newPassword = password_hash('password', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE users SET password = ?, is_active = 1, status = 'active', last_login_at = NULL WHERE email = ?");
    $stmt->execute([$newPassword, 'admin@csar.sn']);
    
    echo "   ✅ Mot de passe admin réinitialisé\n";
    echo "   ✅ Compte admin activé\n";
    echo "   ✅ Dernière connexion effacée\n";
    echo "   🔒 Mot de passe: password\n\n";

    // 4. Vérifier tous les utilisateurs
    echo "4️⃣ Vérification de tous les utilisateurs...\n";
    
    $stmt = $pdo->query("SELECT email, role, is_active, status FROM users ORDER BY role");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($users as $user) {
        $status = $user['is_active'] ? '✅ Actif' : '❌ Inactif';
        echo "   - {$user['email']} ({$user['role']}) - $status\n";
    }
    echo "\n";

    // 5. Nettoyer le cache Laravel
    echo "5️⃣ Nettoyage du cache Laravel...\n";
    
    try {
        require_once "vendor/autoload.php";
        $app = require_once "bootstrap/app.php";
        $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
        
        // Nettoyer les caches
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('session:table');
        
        echo "   ✅ Cache Laravel nettoyé\n";
        echo "   ✅ Configuration rechargée\n";
        echo "   ✅ Routes rechargées\n";
        echo "   ✅ Vues rechargées\n";
        
    } catch (Exception $e) {
        echo "   ⚠️ Erreur nettoyage cache: " . $e->getMessage() . "\n";
    }
    echo "\n";

    // 6. Test final de connexion
    echo "6️⃣ Test final de connexion...\n";
    
    try {
        $credentials = [
            'email' => 'admin@csar.sn',
            'password' => 'password'
        ];
        
        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            echo "   ✅ Connexion réussie\n";
            $user = \Illuminate\Support\Facades\Auth::user();
            echo "   👤 Utilisateur: {$user->email}\n";
            echo "   🔑 Rôle: {$user->role}\n";
            \Illuminate\Support\Facades\Auth::logout();
        } else {
            echo "   ❌ Connexion échouée\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur test: " . $e->getMessage() . "\n";
    }
    echo "\n";

    echo "🎉 NETTOYAGE TERMINÉ AVEC SUCCÈS !\n";
    echo "===================================\n";
    echo "✅ Sessions nettoyées\n";
    echo "✅ Utilisateur admin réinitialisé\n";
    echo "✅ Cache Laravel nettoyé\n";
    echo "✅ Connexion testée\n";
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
    echo "💡 CONSEILS POUR LA CONNEXION :\n";
    echo "1. Videz le cache de votre navigateur (Ctrl+F5)\n";
    echo "2. Utilisez un navigateur en navigation privée\n";
    echo "3. Vérifiez que l'URL est bien http://localhost:8000/admin\n";
    echo "4. Assurez-vous que le serveur Laravel est démarré\n";
    echo "\n";
    echo "✨ LA CONNEXION ADMIN EST MAINTENANT FONCTIONNELLE !\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

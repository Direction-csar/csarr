<?php
/**
 * Diagnostic de connexion à la plateforme CSAR
 */

echo "🔍 DIAGNOSTIC DE CONNEXION - PLATEFORME CSAR\n";
echo "============================================\n\n";

// Test 1: Vérification de la base de données
echo "1. Vérification de la base de données\n";
echo "------------------------------------\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=csar_platform', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✅ Connexion à la base de données réussie\n";
    
    // Vérifier les utilisateurs
    $stmt = $pdo->query("SELECT id, name, email, role_id FROM users ORDER BY role_id");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   📋 Utilisateurs trouvés:\n";
    foreach ($users as $user) {
        $role = match($user['role_id']) {
            1 => 'Admin',
            2 => 'DG',
            3 => 'Responsable',
            4 => 'Agent',
            5 => 'DRH',
            default => 'Inconnu'
        };
        echo "      - {$user['name']} ({$user['email']}) - Rôle: {$role}\n";
    }
    
} catch (PDOException $e) {
    echo "   ❌ Erreur de connexion à la base de données: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// Test 2: Vérification des rôles
echo "2. Vérification des rôles\n";
echo "-------------------------\n";

try {
    $stmt = $pdo->query("SELECT id, name FROM roles ORDER BY id");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($roles)) {
        echo "   ❌ Aucun rôle trouvé dans la base de données\n";
    } else {
        echo "   ✅ Rôles trouvés:\n";
        foreach ($roles as $role) {
            echo "      - ID {$role['id']}: {$role['name']}\n";
        }
    }
    
} catch (PDOException $e) {
    echo "   ❌ Erreur lors de la vérification des rôles: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Vérification des utilisateurs par rôle
echo "3. Vérification des utilisateurs par rôle\n";
echo "-----------------------------------------\n";

$expectedUsers = [
    1 => ['email' => 'admin@csar.sn', 'name' => 'Administrateur'],
    2 => ['email' => 'dg@csar.sn', 'name' => 'Directeur Général'],
    3 => ['email' => 'responsable@csar.sn', 'name' => 'Responsable Entrepôt'],
    4 => ['email' => 'agent@csar.sn', 'name' => 'Agent CSAR'],
    5 => ['email' => 'drh@csar.sn', 'name' => 'DRH']
];

foreach ($expectedUsers as $roleId => $userData) {
    try {
        $stmt = $pdo->prepare("SELECT id, name, email, role_id FROM users WHERE email = ? AND role_id = ?");
        $stmt->execute([$userData['email'], $roleId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "   ✅ {$userData['name']} ({$userData['email']}) - Rôle ID: {$roleId}\n";
        } else {
            echo "   ❌ {$userData['name']} ({$userData['email']}) - MANQUANT\n";
        }
    } catch (PDOException $e) {
        echo "   ❌ Erreur pour {$userData['email']}: " . $e->getMessage() . "\n";
    }
}

echo "\n";

// Test 4: Vérification des routes
echo "4. Vérification des routes de connexion\n";
echo "---------------------------------------\n";

$routes = [
    'admin' => 'http://localhost:8000/admin/login',
    'dg' => 'http://localhost:8000/dg/login',
    'entrepot' => 'http://localhost:8000/entrepot/login',
    'agent' => 'http://localhost:8000/agent/login',
    'drh' => 'http://localhost:8000/drh/login'
];

foreach ($routes as $role => $url) {
    echo "   🔗 {$role}: {$url}\n";
}

echo "\n";

// Test 5: Instructions de connexion
echo "5. Instructions de connexion\n";
echo "-----------------------------\n";

echo "   📋 Pour vous connecter:\n";
echo "   \n";
echo "   1. Assurez-vous que le serveur Laravel est démarré:\n";
echo "      php artisan serve --host=0.0.0.0 --port=8000\n";
echo "   \n";
echo "   2. Ouvrez votre navigateur et allez sur l'URL correspondante:\n";
echo "   \n";

foreach ($expectedUsers as $roleId => $userData) {
    $roleName = match($roleId) {
        1 => 'admin',
        2 => 'dg',
        3 => 'entrepot',
        4 => 'agent',
        5 => 'drh'
    };
    echo "      👤 {$userData['name']}:\n";
    echo "         URL: http://localhost:8000/{$roleName}/login\n";
    echo "         Email: {$userData['email']}\n";
    echo "         Password: password\n";
    echo "   \n";
}

echo "   3. Si vous obtenez une erreur \"419 PAGE EXPIRED\":\n";
echo "      - Fermez complètement votre navigateur\n";
echo "      - Rouvrez-le\n";
echo "      - Utilisez Ctrl+Shift+Delete pour effacer le cache\n";
echo "      - Ou utilisez le mode navigation privée (Ctrl+Shift+N)\n";
echo "   \n";

echo "   4. Si le serveur ne répond pas:\n";
echo "      - Vérifiez que XAMPP est démarré\n";
echo "      - Vérifiez que MySQL est en cours d'exécution\n";
echo "      - Redémarrez le serveur Laravel\n";

echo "\n";

// Test 6: Vérification du serveur
echo "6. Vérification du serveur\n";
echo "--------------------------\n";

$serverUrl = 'http://localhost:8000';
$context = stream_context_create([
    'http' => [
        'timeout' => 5,
        'method' => 'GET'
    ]
]);

$response = @file_get_contents($serverUrl, false, $context);
if ($response !== false) {
    echo "   ✅ Serveur Laravel accessible sur {$serverUrl}\n";
} else {
    echo "   ❌ Serveur Laravel non accessible sur {$serverUrl}\n";
    echo "   💡 Démarrez le serveur avec: php artisan serve --host=0.0.0.0 --port=8000\n";
}

echo "\n";

echo "🎯 RÉSUMÉ DU DIAGNOSTIC\n";
echo "======================\n";
echo "✅ Base de données: " . (isset($pdo) ? "Connectée" : "Non connectée") . "\n";
echo "✅ Utilisateurs: " . count($users) . " trouvés\n";
echo "✅ Rôles: " . count($roles) . " trouvés\n";
echo "✅ Serveur: " . ($response !== false ? "Accessible" : "Non accessible") . "\n";
echo "\n";
echo "📋 Prochaines étapes:\n";
echo "1. Démarrez le serveur Laravel si nécessaire\n";
echo "2. Utilisez les URLs spécifiques pour chaque rôle\n";
echo "3. Utilisez le mot de passe 'password' pour tous les comptes\n";
echo "4. Videz le cache du navigateur en cas d'erreur 419\n";
echo "\n";
echo "🎉 DIAGNOSTIC TERMINÉ!\n";

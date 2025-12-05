<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Hash;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "═══════════════════════════════════════════════════════════════\n";
echo "    🧪 TEST DE CONNEXION - PLATEFORME CSAR\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Configuration base de données
$envContent = file_get_contents('.env');
preg_match('/DB_HOST=(.*)/', $envContent, $dbHost);
preg_match('/DB_PORT=(.*)/', $envContent, $dbPort);
preg_match('/DB_DATABASE=(.*)/', $envContent, $dbDatabase);
preg_match('/DB_USERNAME=(.*)/', $envContent, $dbUsername);
preg_match('/DB_PASSWORD=(.*)/', $envContent, $dbPassword);

$DB_HOST = $dbHost[1] ?? 'localhost';
$DB_PORT = $dbPort[1] ?? '3306';
$DB_DATABASE = $dbDatabase[1] ?? 'plateforme_csar';
$DB_USERNAME = $dbUsername[1] ?? 'root';
$DB_PASSWORD = $dbPassword[1] ?? '';

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_DATABASE;charset=utf8mb4",
        $DB_USERNAME,
        $DB_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données réussie\n\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
    exit(1);
}

// Tester chaque utilisateur
$testUsers = [
    ['email' => 'admin@csar.sn', 'password' => 'password', 'role' => 'Administrateur'],
    ['email' => 'dg@csar.sn', 'password' => 'password', 'role' => 'DG'],
    ['email' => 'entrepot@csar.sn', 'password' => 'password', 'role' => 'Entrepôt'],
    ['email' => 'drh@csar.sn', 'password' => 'password', 'role' => 'DRH']
];

echo "🧪 TEST DES IDENTIFIANTS\n";
echo "───────────────────────────────────────────────────────────────\n\n";

$allPassed = true;

foreach ($testUsers as $testUser) {
    echo "🔍 Test: " . $testUser['role'] . " (" . $testUser['email'] . ")\n";
    
    // Récupérer l'utilisateur
    $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    $stmt->execute([$testUser['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "   ❌ Utilisateur non trouvé dans la base\n\n";
        $allPassed = false;
        continue;
    }
    
    echo "   ✅ Utilisateur trouvé: " . $user['name'] . "\n";
    
    // Vérifier le mot de passe
    if (Hash::check($testUser['password'], $user['password'])) {
        echo "   ✅ Mot de passe correct (hash vérifié)\n";
        echo "   ✅ CONNEXION DEVRAIT FONCTIONNER\n\n";
    } else {
        echo "   ❌ Mot de passe incorrect (hash ne correspond pas)\n";
        echo "   ⚠️  Exécutez: php reset_passwords.php\n\n";
        $allPassed = false;
    }
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "    📊 RÉSULTAT FINAL\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

if ($allPassed) {
    echo "✅ TOUS LES TESTS SONT PASSÉS !\n\n";
    echo "🎉 Vous pouvez maintenant vous connecter avec:\n\n";
    
    echo "👤 ADMINISTRATEUR\n";
    echo "   Email: admin@csar.sn\n";
    echo "   Mot de passe: password\n";
    echo "   URL: http://localhost:8000/admin/login\n\n";
    
    echo "👔 DIRECTEUR GÉNÉRAL (DG)\n";
    echo "   Email: dg@csar.sn\n";
    echo "   Mot de passe: password\n";
    echo "   URL: http://localhost:8000/dg/login\n\n";
    
    echo "📦 GESTIONNAIRE D'ENTREPÔT\n";
    echo "   Email: entrepot@csar.sn\n";
    echo "   Mot de passe: password\n";
    echo "   URL: http://localhost:8000/entrepot/login\n\n";
    
    echo "💡 N'oubliez pas de démarrer le serveur:\n";
    echo "   php artisan serve\n\n";
    
} else {
    echo "❌ CERTAINS TESTS ONT ÉCHOUÉ\n\n";
    echo "🔧 Actions recommandées:\n";
    echo "   1. Réinitialiser les mots de passe:\n";
    echo "      php reset_passwords.php\n\n";
    echo "   2. Vider le cache:\n";
    echo "      php artisan cache:clear\n";
    echo "      php artisan config:clear\n\n";
    echo "   3. Relancer ce test:\n";
    echo "      php test_connexion_finale.php\n\n";
}

echo "═══════════════════════════════════════════════════════════════\n";



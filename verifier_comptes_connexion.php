<?php
/**
 * Vérification et Correction des Comptes de Connexion
 */

// Configuration base de données
$db_config = [
    'host' => 'localhost',
    'database' => 'csar_platform_2025',
    'username' => 'root',
    'password' => ''
];

try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['database']};charset=utf8mb4",
        $db_config['username'],
        $db_config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ Connexion base de données OK\n\n";
} catch (PDOException $e) {
    die("❌ Erreur connexion: " . $e->getMessage() . "\n");
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "       VÉRIFICATION DES COMPTES DE CONNEXION\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Vérifier les comptes
$comptes_requis = [
    ['email' => 'admin@csar.sn', 'name' => 'Administrateur CSAR', 'role' => 'admin'],
    ['email' => 'dg@csar.sn', 'name' => 'Directeur Général', 'role' => 'dg'],
    ['email' => 'drh@csar.sn', 'name' => 'Directeur RH', 'role' => 'drh'],
    ['email' => 'responsable@csar.sn', 'name' => 'Responsable Entrepôt', 'role' => 'responsable'],
    ['email' => 'agent@csar.sn', 'name' => 'Agent CSAR', 'role' => 'agent']
];

echo "🔍 VÉRIFICATION DES COMPTES:\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$problemes = [];

foreach ($comptes_requis as $compte) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$compte['email']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo "❌ MANQUANT: {$compte['email']} ({$compte['role']})\n";
        $problemes[] = $compte;
    } else {
        $status_icon = ($user['status'] ?? 'active') === 'active' ? '✅' : '⚠️';
        $is_active_icon = ($user['is_active'] ?? 1) == 1 ? '✅' : '⚠️';
        
        echo "{$status_icon} {$is_active_icon} {$compte['email']} - {$user['name']}\n";
        echo "   Role: {$user['role']}\n";
        echo "   Status: " . ($user['status'] ?? 'N/A') . "\n";
        echo "   Is Active: " . ($user['is_active'] ?? 'N/A') . "\n\n";
        
        // Vérifier si le compte est actif
        if (($user['status'] ?? 'active') !== 'active' || ($user['is_active'] ?? 1) != 1) {
            $problemes[] = array_merge($compte, ['action' => 'activer']);
        }
    }
}

// Corriger les problèmes
if (!empty($problemes)) {
    echo "\n🔧 CORRECTION DES PROBLÈMES:\n";
    echo "─────────────────────────────────────────────────────────────\n\n";
    
    foreach ($problemes as $compte) {
        if (isset($compte['action']) && $compte['action'] === 'activer') {
            // Activer le compte
            $stmt = $pdo->prepare("
                UPDATE users 
                SET status = 'active', is_active = 1 
                WHERE email = ?
            ");
            $stmt->execute([$compte['email']]);
            echo "✅ Activé: {$compte['email']}\n";
        } else {
            // Créer le compte
            $password = password_hash('password', PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, password, role, status, is_active, created_at, updated_at)
                VALUES (?, ?, ?, ?, 'active', 1, NOW(), NOW())
            ");
            $stmt->execute([
                $compte['name'],
                $compte['email'],
                $password,
                $compte['role']
            ]);
            echo "✅ Créé: {$compte['email']}\n";
        }
    }
    
    echo "\n";
}

// Afficher les identifiants
echo "\n═══════════════════════════════════════════════════════════════\n";
echo "       IDENTIFIANTS DE CONNEXION\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

foreach ($comptes_requis as $compte) {
    $interface = match($compte['role']) {
        'admin' => '/admin',
        'dg' => '/dg',
        'drh' => '/drh',
        'responsable' => '/entrepot',
        'agent' => '/agent'
    };
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🔐 {$compte['name']}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Email     : {$compte['email']}\n";
    echo "Password  : password\n";
    echo "URL       : http://localhost:8000{$interface}\n";
    echo "Role      : {$compte['role']}\n";
    echo "\n";
}

// Vérifier la configuration
echo "═══════════════════════════════════════════════════════════════\n";
echo "       VÉRIFICATION DE LA CONFIGURATION\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Vérifier APP_KEY
$env_file = __DIR__ . '/.env';
if (file_exists($env_file)) {
    $env_content = file_get_contents($env_file);
    if (strpos($env_content, 'APP_KEY=base64:') !== false) {
        echo "✅ APP_KEY configurée\n";
    } else {
        echo "⚠️ APP_KEY manquante - Exécuter: php artisan key:generate\n";
    }
} else {
    echo "❌ Fichier .env manquant!\n";
}

// Vérifier si le serveur Laravel est démarré
echo "\n📋 INSTRUCTIONS DE CONNEXION:\n";
echo "─────────────────────────────────────────────────────────────\n\n";
echo "1. Démarrer le serveur Laravel:\n";
echo "   php artisan serve\n\n";
echo "2. Ouvrir le navigateur:\n";
echo "   http://localhost:8000/admin\n\n";
echo "3. Se connecter avec:\n";
echo "   Email: admin@csar.sn\n";
echo "   Mot de passe: password\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "✅ Vérification terminée\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Tester la connexion
echo "🧪 TEST DE CONNEXION:\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$test_email = 'admin@csar.sn';
$test_password = 'password';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$test_email]);
$user = $stmt->fetch();

if ($user) {
    if (password_verify($test_password, $user['password'])) {
        echo "✅ TEST RÉUSSI: Le compte admin fonctionne!\n";
        echo "   Vous pouvez vous connecter avec:\n";
        echo "   - Email: admin@csar.sn\n";
        echo "   - Mot de passe: password\n";
    } else {
        echo "⚠️ ATTENTION: Le mot de passe ne correspond pas!\n";
        echo "   Mise à jour du mot de passe...\n";
        
        $new_password = password_hash('password', PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$new_password, $test_email]);
        
        echo "   ✅ Mot de passe réinitialisé à: password\n";
    }
} else {
    echo "❌ Le compte admin n'existe pas!\n";
}

echo "\n";



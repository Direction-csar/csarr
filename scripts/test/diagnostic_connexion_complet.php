<?php

echo "═══════════════════════════════════════════════════════════════\n";
echo "    🔍 DIAGNOSTIC CONNEXION PLATEFORME CSAR\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// 1. Vérifier la configuration
echo "📋 ÉTAPE 1: Vérification fichier .env\n";
echo "───────────────────────────────────────────────────────────────\n";

if (file_exists('.env')) {
    echo "✅ Fichier .env trouvé\n";
    $envContent = file_get_contents('.env');
    
    // Extraire les infos DB
    preg_match('/DB_CONNECTION=(.*)/', $envContent, $dbConnection);
    preg_match('/DB_HOST=(.*)/', $envContent, $dbHost);
    preg_match('/DB_PORT=(.*)/', $envContent, $dbPort);
    preg_match('/DB_DATABASE=(.*)/', $envContent, $dbDatabase);
    preg_match('/DB_USERNAME=(.*)/', $envContent, $dbUsername);
    
    echo "   - Connexion: " . ($dbConnection[1] ?? 'non défini') . "\n";
    echo "   - Hôte: " . ($dbHost[1] ?? 'non défini') . "\n";
    echo "   - Port: " . ($dbPort[1] ?? 'non défini') . "\n";
    echo "   - Base: " . ($dbDatabase[1] ?? 'non défini') . "\n";
    echo "   - Utilisateur: " . ($dbUsername[1] ?? 'non défini') . "\n\n";
    
    $DB_HOST = $dbHost[1] ?? 'localhost';
    $DB_PORT = $dbPort[1] ?? '3306';
    $DB_DATABASE = $dbDatabase[1] ?? 'plateforme_csar';
    $DB_USERNAME = $dbUsername[1] ?? 'root';
    preg_match('/DB_PASSWORD=(.*)/', $envContent, $dbPassword);
    $DB_PASSWORD = $dbPassword[1] ?? '';
    
} else {
    echo "❌ Fichier .env NON TROUVÉ!\n";
    echo "   Utilisation des valeurs par défaut...\n\n";
    $DB_HOST = 'localhost';
    $DB_PORT = '3306';
    $DB_DATABASE = 'plateforme_csar';
    $DB_USERNAME = 'root';
    $DB_PASSWORD = '';
}

// 2. Connexion à la base de données
echo "📋 ÉTAPE 2: Connexion à la base de données\n";
echo "───────────────────────────────────────────────────────────────\n";

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_DATABASE;charset=utf8mb4",
        $DB_USERNAME,
        $DB_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion réussie à la base de données: $DB_DATABASE\n\n";
} catch (PDOException $e) {
    echo "❌ ERREUR DE CONNEXION: " . $e->getMessage() . "\n";
    echo "\n💡 Suggestions:\n";
    echo "   1. Vérifiez que MySQL/XAMPP est démarré\n";
    echo "   2. Vérifiez que la base '$DB_DATABASE' existe\n";
    echo "   3. Vérifiez les identifiants dans le fichier .env\n";
    exit(1);
}

// 3. Vérifier les tables
echo "📋 ÉTAPE 3: Vérification des tables\n";
echo "───────────────────────────────────────────────────────────────\n";

$requiredTables = ['users', 'roles', 'warehouses'];
$missingTables = [];

foreach ($requiredTables as $table) {
    $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table '$table' existe\n";
    } else {
        echo "❌ Table '$table' MANQUANTE\n";
        $missingTables[] = $table;
    }
}

if (!empty($missingTables)) {
    echo "\n⚠️ Tables manquantes: " . implode(', ', $missingTables) . "\n";
    echo "💡 Exécutez: php artisan migrate\n\n";
    exit(1);
}

echo "\n";

// 4. Vérifier les utilisateurs
echo "📋 ÉTAPE 4: Vérification des utilisateurs\n";
echo "───────────────────────────────────────────────────────────────\n";

// Vérifier la structure de la table users
$columns = $pdo->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_ASSOC);
$columnNames = array_column($columns, 'Field');

// Construire la requête en fonction des colonnes disponibles
$selectFields = ['id', 'name', 'email'];
if (in_array('role_id', $columnNames)) $selectFields[] = 'role_id';
if (in_array('is_active', $columnNames)) $selectFields[] = 'is_active';

$users = $pdo->query("SELECT " . implode(', ', $selectFields) . " FROM users")->fetchAll(PDO::FETCH_ASSOC);

if (empty($users)) {
    echo "❌ AUCUN UTILISATEUR TROUVÉ DANS LA BASE!\n\n";
    echo "💡 Ceci explique pourquoi vous ne pouvez pas vous connecter.\n";
    echo "   Les utilisateurs doivent être créés.\n\n";
} else {
    echo "✅ Utilisateurs trouvés: " . count($users) . "\n\n";
    
    foreach ($users as $user) {
        echo "   👤 " . $user['name'] . "\n";
        echo "      Email: " . $user['email'] . "\n";
        if (isset($user['role_id'])) echo "      Role ID: " . $user['role_id'] . "\n";
        if (isset($user['is_active'])) echo "      Actif: " . ($user['is_active'] ? 'Oui' : 'Non') . "\n";
        echo "\n";
    }
}

// 5. Vérifier les rôles
echo "📋 ÉTAPE 5: Vérification des rôles\n";
echo "───────────────────────────────────────────────────────────────\n";

$roles = $pdo->query("SELECT id, name, display_name FROM roles")->fetchAll(PDO::FETCH_ASSOC);

if (empty($roles)) {
    echo "❌ AUCUN RÔLE TROUVÉ!\n\n";
} else {
    echo "✅ Rôles trouvés: " . count($roles) . "\n\n";
    
    foreach ($roles as $role) {
        echo "   🎭 " . $role['display_name'] . " (" . $role['name'] . ")\n";
        echo "      ID: " . $role['id'] . "\n\n";
    }
}

// 6. Vérifier les entrepôts
echo "📋 ÉTAPE 6: Vérification des entrepôts\n";
echo "───────────────────────────────────────────────────────────────\n";

$warehouseColumns = $pdo->query("SHOW COLUMNS FROM warehouses")->fetchAll(PDO::FETCH_ASSOC);
$warehouseColumnNames = array_column($warehouseColumns, 'Field');

$selectWFields = ['id', 'name'];
if (in_array('is_active', $warehouseColumnNames)) $selectWFields[] = 'is_active';

$warehouses = $pdo->query("SELECT " . implode(', ', $selectWFields) . " FROM warehouses")->fetchAll(PDO::FETCH_ASSOC);

if (empty($warehouses)) {
    echo "❌ AUCUN ENTREPÔT TROUVÉ!\n\n";
} else {
    echo "✅ Entrepôts trouvés: " . count($warehouses) . "\n\n";
    
    foreach ($warehouses as $warehouse) {
        echo "   🏢 " . $warehouse['name'] . "\n";
        echo "      ID: " . $warehouse['id'] . "\n";
        if (isset($warehouse['is_active'])) echo "      Actif: " . ($warehouse['is_active'] ? 'Oui' : 'Non') . "\n";
        echo "\n";
    }
}

// 7. Résumé et recommandations
echo "═══════════════════════════════════════════════════════════════\n";
echo "    📊 RÉSUMÉ ET RECOMMANDATIONS\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

if (empty($users) || empty($roles) || empty($warehouses)) {
    echo "❌ PROBLÈME IDENTIFIÉ: Données manquantes dans la base\n\n";
    echo "🔧 SOLUTION:\n";
    echo "   Exécutez cette commande pour créer tous les utilisateurs:\n";
    echo "   > php create_all_users.php\n\n";
} else {
    echo "✅ La base de données semble correcte\n\n";
    echo "💡 Si vous ne pouvez toujours pas vous connecter:\n";
    echo "   1. Videz le cache: php artisan cache:clear\n";
    echo "   2. Videz la config: php artisan config:clear\n";
    echo "   3. Réinitialisez les mots de passe: php reset_passwords.php\n";
    echo "   4. Testez la connexion: php test_connexion_finale.php\n\n";
}

echo "🔐 IDENTIFIANTS STANDARDS:\n";
echo "───────────────────────────────────────────────────────────────\n";
echo "   Admin:       admin@csar.sn / password\n";
echo "   DG:          dg@csar.sn / password\n";
echo "   Entrepôt:    entrepot@csar.sn / password\n";
echo "   DRH:         drh@csar.sn / password\n\n";

echo "🌐 URLS DE CONNEXION:\n";
echo "───────────────────────────────────────────────────────────────\n";
echo "   Admin:       http://localhost:8000/admin/login\n";
echo "   DG:          http://localhost:8000/dg/login\n";
echo "   Entrepôt:    http://localhost:8000/entrepot/login\n";
echo "   DRH:         http://localhost:8000/\n\n";

echo "═══════════════════════════════════════════════════════════════\n";



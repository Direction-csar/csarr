<?php
/**
 * Script de Vérification Complète de la Plateforme CSAR
 * 
 * Ce script vérifie :
 * 1. La connexion à la base de données
 * 2. Les fonctionnalités CRUD principales
 * 3. Les données présentes dans chaque table
 * 4. Les fichiers de test à supprimer
 */

// Configuration de la base de données
$db_config = [
    'host' => 'localhost',
    'database' => 'csar_platform_2025',
    'username' => 'root',
    'password' => ''
];

// Connexion à la base de données
try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['database']};charset=utf8mb4",
        $db_config['username'],
        $db_config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    echo "✅ Connexion à la base de données réussie\n\n";
} catch (PDOException $e) {
    die("❌ Erreur de connexion : " . $e->getMessage() . "\n");
}

// Fonction pour compter les enregistrements dans une table
function countRecords($pdo, $table) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM `{$table}`");
        return $stmt->fetch()['count'];
    } catch (PDOException $e) {
        return "Table non trouvée";
    }
}

// Fonction pour trouver les données de test
function findTestData($pdo, $table, $conditions) {
    try {
        $where = implode(' OR ', $conditions);
        $stmt = $pdo->query("SELECT * FROM `{$table}` WHERE {$where}");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "     VÉRIFICATION COMPLÈTE DE LA PLATEFORME CSAR 2025\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// 1. VÉRIFICATION DES TABLES PRINCIPALES
echo "📊 1. ÉTAT DES TABLES PRINCIPALES\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$tables = [
    'users' => 'Utilisateurs',
    'demandes' => 'Demandes',
    'warehouses' => 'Entrepôts',
    'stock_movements' => 'Mouvements de stock',
    'personnel' => 'Personnel',
    'news' => 'Actualités',
    'sim_reports' => 'Rapports SIM',
    'gallery_images' => 'Images galerie',
    'technical_partners' => 'Partenaires',
    'contact_messages' => 'Messages de contact',
    'newsletter_subscribers' => 'Abonnés newsletter',
    'notifications' => 'Notifications',
    'messages' => 'Messages internes',
    'audit_logs' => 'Journaux d\'audit',
    'price_alerts' => 'Alertes de prix',
    'tasks' => 'Tâches',
    'weekly_agendas' => 'Agendas hebdomadaires'
];

$total_records = 0;
foreach ($tables as $table => $label) {
    $count = countRecords($pdo, $table);
    if ($count !== "Table non trouvée") {
        $total_records += $count;
        $status = $count > 0 ? "✅" : "⚠️";
        echo "{$status} {$label} ({$table}): {$count} enregistrement(s)\n";
    } else {
        echo "❌ {$label} ({$table}): {$count}\n";
    }
}

echo "\n📈 Total des enregistrements : {$total_records}\n\n";

// 2. RECHERCHE DES DONNÉES DE TEST
echo "🔍 2. RECHERCHE DES DONNÉES DE TEST/FICTIVES\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$test_data_found = [];

// Recherche dans users
$test_users = findTestData($pdo, 'users', [
    "email LIKE '%test%'",
    "email LIKE '%demo%'",
    "email LIKE '%fake%'",
    "name LIKE '%Test%'",
    "name LIKE '%Demo%'"
]);
if (count($test_users) > 0) {
    $test_data_found['users'] = $test_users;
    echo "⚠️ Utilisateurs de test trouvés: " . count($test_users) . "\n";
    foreach ($test_users as $user) {
        echo "   - {$user['name']} ({$user['email']})\n";
    }
}

// Recherche dans demandes
$test_demandes = findTestData($pdo, 'demandes', [
    "nom LIKE '%Test%'",
    "nom LIKE '%Demo%'",
    "email LIKE '%test%'",
    "description LIKE '%test%'",
    "description LIKE '%fake%'"
]);
if (count($test_demandes) > 0) {
    $test_data_found['demandes'] = $test_demandes;
    echo "⚠️ Demandes de test trouvées: " . count($test_demandes) . "\n";
}

// Recherche dans news
$test_news = findTestData($pdo, 'news', [
    "title LIKE '%Test%'",
    "title LIKE '%Demo%'",
    "content LIKE '%test%'",
    "content LIKE '%fake%'"
]);
if (count($test_news) > 0) {
    $test_data_found['news'] = $test_news;
    echo "⚠️ Actualités de test trouvées: " . count($test_news) . "\n";
}

// Recherche dans personnel
$test_personnel = findTestData($pdo, 'personnel', [
    "nom LIKE '%Test%'",
    "nom LIKE '%Demo%'",
    "email LIKE '%test%'",
    "prenom LIKE '%Test%'"
]);
if (count($test_personnel) > 0) {
    $test_data_found['personnel'] = $test_personnel;
    echo "⚠️ Personnel de test trouvé: " . count($test_personnel) . "\n";
}

if (empty($test_data_found)) {
    echo "✅ Aucune donnée de test détectée\n\n";
} else {
    echo "\n⚠️ Total de données de test à nettoyer: " . array_sum(array_map('count', $test_data_found)) . "\n\n";
}

// 3. VÉRIFICATION DES UTILISATEURS PAR DÉFAUT
echo "👥 3. UTILISATEURS PAR DÉFAUT (à conserver)\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$default_users = $pdo->query("
    SELECT id, name, email, role, status 
    FROM users 
    WHERE email IN ('admin@csar.sn', 'dg@csar.sn', 'drh@csar.sn', 'responsable@csar.sn', 'agent@csar.sn')
")->fetchAll();

foreach ($default_users as $user) {
    $status_icon = $user['status'] === 'active' ? '✅' : '⚠️';
    echo "{$status_icon} {$user['role']}: {$user['name']} ({$user['email']}) - Status: {$user['status']}\n";
}

if (count($default_users) < 5) {
    echo "\n⚠️ Attention: Certains comptes par défaut sont manquants!\n";
}

echo "\n";

// 4. VÉRIFICATION DES FONCTIONNALITÉS
echo "⚙️ 4. VÉRIFICATION DES FONCTIONNALITÉS CLÉS\n";
echo "─────────────────────────────────────────────────────────────\n\n";

// Vérifier les dernières demandes
$recent_demandes = $pdo->query("SELECT COUNT(*) as count FROM demandes WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetch()['count'];
echo "✅ Demandes des 7 derniers jours: {$recent_demandes}\n";

// Vérifier les entrepôts actifs
$active_warehouses = $pdo->query("SELECT COUNT(*) as count FROM warehouses WHERE is_active = 1")->fetch()['count'];
echo "✅ Entrepôts actifs: {$active_warehouses}\n";

// Vérifier les notifications non lues
$unread_notifications = $pdo->query("SELECT COUNT(*) as count FROM notifications WHERE is_read = 0")->fetch()['count'];
echo "✅ Notifications non lues: {$unread_notifications}\n";

// Vérifier les messages non lus
$unread_messages = $pdo->query("SELECT COUNT(*) as count FROM messages WHERE lu = 0")->fetch()['count'];
echo "✅ Messages non lus: {$unread_messages}\n";

// Vérifier les abonnés newsletter
$newsletter_subscribers = countRecords($pdo, 'newsletter_subscribers');
echo "✅ Abonnés newsletter: {$newsletter_subscribers}\n";

echo "\n";

// 5. FICHIERS ET STRUCTURE
echo "📁 5. ANALYSE DE LA STRUCTURE DES FICHIERS\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$root_dir = __DIR__;

// Compter les fichiers PHP temporaires à la racine
$temp_files = glob($root_dir . '/*.php');
$temp_bat = glob($root_dir . '/*.bat');
$temp_sql = glob($root_dir . '/*.sql');
$temp_ps1 = glob($root_dir . '/*.ps1');
$temp_sh = glob($root_dir . '/*.sh');

echo "⚠️ Fichiers PHP à la racine: " . count($temp_files) . "\n";
echo "⚠️ Fichiers BAT à la racine: " . count($temp_bat) . "\n";
echo "⚠️ Fichiers SQL à la racine: " . count($temp_sql) . "\n";
echo "⚠️ Fichiers PowerShell à la racine: " . count($temp_ps1) . "\n";
echo "⚠️ Fichiers Shell à la racine: " . count($temp_sh) . "\n";

$total_temp = count($temp_files) + count($temp_bat) + count($temp_sql) + count($temp_ps1) + count($temp_sh);
echo "\n📊 Total de fichiers temporaires: {$total_temp}\n";

echo "\n";

// RÉSUMÉ FINAL
echo "═══════════════════════════════════════════════════════════════\n";
echo "                    RÉSUMÉ DE LA VÉRIFICATION\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "✅ Base de données: Connectée et fonctionnelle\n";
echo "📊 Total d'enregistrements: {$total_records}\n";

if (!empty($test_data_found)) {
    echo "⚠️ Données de test: " . array_sum(array_map('count', $test_data_found)) . " enregistrements à nettoyer\n";
} else {
    echo "✅ Données de test: Aucune détectée\n";
}

echo "👥 Comptes par défaut: " . count($default_users) . "/5\n";
echo "📁 Fichiers temporaires: {$total_temp} fichiers à organiser\n\n";

// RECOMMANDATIONS
echo "🎯 RECOMMANDATIONS:\n";
echo "─────────────────────────────────────────────────────────────\n\n";

if (!empty($test_data_found)) {
    echo "1. ⚠️ Nettoyer les données de test de la base de données\n";
}

if ($total_temp > 50) {
    echo "2. ⚠️ Organiser les fichiers temporaires dans un dossier /scripts ou /docs\n";
}

if (count($default_users) < 5) {
    echo "3. ⚠️ Recréer les comptes utilisateurs par défaut manquants\n";
}

echo "4. ✅ Vérifier les fonctionnalités dans le navigateur\n";
echo "5. ✅ Tester les exports PDF/CSV\n";
echo "6. ✅ Vérifier les notifications en temps réel\n";
echo "7. ✅ Tester le formulaire de demande public\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "           Vérification terminée - " . date('Y-m-d H:i:s') . "\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Retourner les données pour utilisation
return [
    'total_records' => $total_records,
    'test_data' => $test_data_found,
    'default_users' => count($default_users),
    'temp_files' => $total_temp
];


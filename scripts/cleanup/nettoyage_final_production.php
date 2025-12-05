<?php
/**
 * Script de Nettoyage Final pour Production - Plateforme CSAR
 * 
 * Ce script nettoie:
 * 1. Toutes les données de test de la base de données
 * 2. Les notifications de test
 * 3. Les logs d'audit inutiles
 * 
 * ATTENTION: Exécuter ce script uniquement avant la mise en production!
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

echo "═══════════════════════════════════════════════════════════════\n";
echo "        NETTOYAGE FINAL POUR PRODUCTION - CSAR 2025\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$total_deleted = 0;
$summary = [];

// Fonction pour supprimer les données de test
function deleteTestData($pdo, $table, $conditions, $label) {
    $where = implode(' OR ', $conditions);
    try {
        $stmt = $pdo->prepare("DELETE FROM `{$table}` WHERE {$where}");
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count > 0) {
            echo "✅ {$label}: {$count} enregistrement(s) supprimé(s)\n";
        }
        return $count;
    } catch (PDOException $e) {
        echo "⚠️ Erreur lors de la suppression dans {$table}: " . $e->getMessage() . "\n";
        return 0;
    }
}

// 1. NETTOYAGE DES DEMANDES DE TEST
echo "🧹 1. NETTOYAGE DES DEMANDES DE TEST\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$deleted_demandes = deleteTestData($pdo, 'demandes', [
    "nom LIKE '%Test%'",
    "nom LIKE '%Demo%'",
    "nom LIKE '%Fake%'",
    "email LIKE '%test%'",
    "email LIKE '%demo%'",
    "email LIKE '%fake%'",
    "description LIKE '%test%'",
    "description LIKE '%fake%'",
    "description LIKE '%demo%'"
], "Demandes de test");

$summary['demandes'] = $deleted_demandes;
$total_deleted += $deleted_demandes;

// 2. NETTOYAGE DES UTILISATEURS DE TEST (SAUF COMPTES PAR DÉFAUT)
echo "\n🧹 2. NETTOYAGE DES UTILISATEURS DE TEST\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$deleted_users = deleteTestData($pdo, 'users', [
    "email LIKE '%test%' AND email NOT IN ('admin@csar.sn', 'dg@csar.sn', 'drh@csar.sn', 'responsable@csar.sn', 'agent@csar.sn')",
    "email LIKE '%demo%' AND email NOT IN ('admin@csar.sn', 'dg@csar.sn', 'drh@csar.sn', 'responsable@csar.sn', 'agent@csar.sn')",
    "name LIKE '%Test%' AND email NOT IN ('admin@csar.sn', 'dg@csar.sn', 'drh@csar.sn', 'responsable@csar.sn', 'agent@csar.sn')",
    "name LIKE '%Demo%' AND email NOT IN ('admin@csar.sn', 'dg@csar.sn', 'drh@csar.sn', 'responsable@csar.sn', 'agent@csar.sn')"
], "Utilisateurs de test");

$summary['users'] = $deleted_users;
$total_deleted += $deleted_users;

// 3. NETTOYAGE DU PERSONNEL DE TEST
echo "\n🧹 3. NETTOYAGE DU PERSONNEL DE TEST\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$deleted_personnel = deleteTestData($pdo, 'personnel', [
    "nom LIKE '%Test%'",
    "nom LIKE '%Demo%'",
    "prenom LIKE '%Test%'",
    "prenom LIKE '%Demo%'",
    "email LIKE '%test%'",
    "email LIKE '%demo%'"
], "Personnel de test");

$summary['personnel'] = $deleted_personnel;
$total_deleted += $deleted_personnel;

// 4. NETTOYAGE DES ACTUALITÉS DE TEST
echo "\n🧹 4. NETTOYAGE DES ACTUALITÉS DE TEST\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$deleted_news = deleteTestData($pdo, 'news', [
    "title LIKE '%Test%'",
    "title LIKE '%Demo%'",
    "content LIKE '%test%'",
    "content LIKE '%demo%'"
], "Actualités de test");

$summary['news'] = $deleted_news;
$total_deleted += $deleted_news;

// 5. NETTOYAGE DES RAPPORTS SIM DE TEST
echo "\n🧹 5. NETTOYAGE DES RAPPORTS SIM DE TEST\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$deleted_sim_reports = deleteTestData($pdo, 'sim_reports', [
    "title LIKE '%Test%'",
    "title LIKE '%Demo%'",
    "title LIKE '%Fake%'"
], "Rapports SIM de test");

$summary['sim_reports'] = $deleted_sim_reports;
$total_deleted += $deleted_sim_reports;

// 6. NETTOYAGE DES MESSAGES DE CONTACT DE TEST
echo "\n🧹 6. NETTOYAGE DES MESSAGES DE CONTACT DE TEST\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$deleted_contacts = deleteTestData($pdo, 'contact_messages', [
    "nom LIKE '%Test%'",
    "nom LIKE '%Demo%'",
    "email LIKE '%test%'",
    "email LIKE '%demo%'",
    "message LIKE '%test%'"
], "Messages de contact de test");

$summary['contact_messages'] = $deleted_contacts;
$total_deleted += $deleted_contacts;

// 7. NETTOYAGE DES ABONNÉS NEWSLETTER DE TEST
echo "\n🧹 7. NETTOYAGE DES ABONNÉS NEWSLETTER DE TEST\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$deleted_newsletter = deleteTestData($pdo, 'newsletter_subscribers', [
    "email LIKE '%test%'",
    "email LIKE '%demo%'",
    "email LIKE '%fake%'",
    "nom LIKE '%Test%'",
    "nom LIKE '%Demo%'"
], "Abonnés newsletter de test");

$summary['newsletter_subscribers'] = $deleted_newsletter;
$total_deleted += $deleted_newsletter;

// 8. NETTOYAGE DES NOTIFICATIONS DE TEST
echo "\n🧹 8. NETTOYAGE DES NOTIFICATIONS DE TEST\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$deleted_notifications = deleteTestData($pdo, 'notifications', [
    "title LIKE '%Test%'",
    "title LIKE '%Demo%'",
    "message LIKE '%test%'",
    "message LIKE '%demo%'"
], "Notifications de test");

$summary['notifications'] = $deleted_notifications;
$total_deleted += $deleted_notifications;

// 9. NETTOYAGE DES ENTREPÔTS DE TEST
echo "\n🧹 9. NETTOYAGE DES ENTREPÔTS DE TEST\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$deleted_warehouses = deleteTestData($pdo, 'warehouses', [
    "name LIKE '%Test%'",
    "name LIKE '%Demo%'",
    "name LIKE '%Fake%'"
], "Entrepôts de test");

$summary['warehouses'] = $deleted_warehouses;
$total_deleted += $deleted_warehouses;

// 10. NETTOYAGE DES MOUVEMENTS DE STOCK DE TEST
echo "\n🧹 10. NETTOYAGE DES MOUVEMENTS DE STOCK DE TEST\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$deleted_movements = deleteTestData($pdo, 'stock_movements', [
    "reference LIKE '%TEST%'",
    "reference LIKE '%DEMO%'",
    "motif LIKE '%test%'",
    "motif LIKE '%demo%'"
], "Mouvements de stock de test");

$summary['stock_movements'] = $deleted_movements;
$total_deleted += $deleted_movements;

// 11. NETTOYAGE DES ANCIENS LOGS D'AUDIT (OPTIONNEL)
echo "\n🧹 11. NETTOYAGE DES ANCIENS LOGS D'AUDIT\n";
echo "─────────────────────────────────────────────────────────────\n\n";

try {
    // Garder seulement les 30 derniers jours
    $stmt = $pdo->prepare("DELETE FROM audit_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
    $stmt->execute();
    $deleted_logs = $stmt->rowCount();
    if ($deleted_logs > 0) {
        echo "✅ Logs d'audit de plus de 30 jours: {$deleted_logs} supprimé(s)\n";
    } else {
        echo "✅ Aucun log d'audit ancien à supprimer\n";
    }
    $summary['audit_logs'] = $deleted_logs;
    $total_deleted += $deleted_logs;
} catch (PDOException $e) {
    echo "⚠️ Erreur lors du nettoyage des logs: " . $e->getMessage() . "\n";
}

// RÉSUMÉ FINAL
echo "\n═══════════════════════════════════════════════════════════════\n";
echo "                    RÉSUMÉ DU NETTOYAGE\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "📊 STATISTIQUES DE SUPPRESSION:\n";
echo "─────────────────────────────────────────────────────────────\n\n";

foreach ($summary as $table => $count) {
    if ($count > 0) {
        echo "✅ {$table}: {$count} enregistrement(s)\n";
    }
}

echo "\n🗑️ Total d'enregistrements supprimés: {$total_deleted}\n\n";

// Vérifier les comptes par défaut
echo "👥 VÉRIFICATION DES COMPTES PAR DÉFAUT:\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$default_users = $pdo->query("
    SELECT id, name, email, role, status 
    FROM users 
    WHERE email IN ('admin@csar.sn', 'dg@csar.sn', 'drh@csar.sn', 'responsable@csar.sn', 'agent@csar.sn')
    ORDER BY FIELD(email, 'admin@csar.sn', 'dg@csar.sn', 'drh@csar.sn', 'responsable@csar.sn', 'agent@csar.sn')
")->fetchAll();

foreach ($default_users as $user) {
    echo "✅ {$user['role']}: {$user['name']} ({$user['email']})\n";
}

echo "\n";

// Statistiques finales
echo "📈 ÉTAT FINAL DE LA BASE DE DONNÉES:\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$final_stats = [
    'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'demandes' => $pdo->query("SELECT COUNT(*) FROM demandes")->fetchColumn(),
    'warehouses' => $pdo->query("SELECT COUNT(*) FROM warehouses")->fetchColumn(),
    'stock_movements' => $pdo->query("SELECT COUNT(*) FROM stock_movements")->fetchColumn(),
    'personnel' => $pdo->query("SELECT COUNT(*) FROM personnel")->fetchColumn(),
    'news' => $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn(),
    'sim_reports' => $pdo->query("SELECT COUNT(*) FROM sim_reports")->fetchColumn(),
    'notifications' => $pdo->query("SELECT COUNT(*) FROM notifications")->fetchColumn(),
    'audit_logs' => $pdo->query("SELECT COUNT(*) FROM audit_logs")->fetchColumn()
];

foreach ($final_stats as $table => $count) {
    echo "✅ {$table}: {$count} enregistrement(s)\n";
}

$final_total = array_sum($final_stats);
echo "\n📊 Total final: {$final_total} enregistrements\n";

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "✅ Nettoyage terminé avec succès - " . date('Y-m-d H:i:s') . "\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "🎉 La base de données est maintenant prête pour la production!\n\n";

return [
    'total_deleted' => $total_deleted,
    'summary' => $summary,
    'final_total' => $final_total
];


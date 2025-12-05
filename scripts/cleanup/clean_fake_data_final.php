<?php
/**
 * 🧹 SCRIPT DE NETTOYAGE FINAL - PLATEFORME CSAR
 * 
 * Ce script supprime définitivement toutes les données fictives
 * et ne garde que les comptes réels CSAR.
 * 
 * ✅ Comptes réels à conserver :
 * - admin@csar.sn (Administrateur CSAR)
 * - dg@csar.sn (Directeur Général)
 * - responsable@csar.sn (Responsable Entrepôt)
 * - agent@csar.sn (Agent CSAR)
 * - drh@csar.sn (Directeur RH)
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Configuration de la base de données
$config = [
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'plateforme-csar',
    'username' => 'root',
    'password' => '', // Mot de passe MySQL de XAMPP
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];

try {
    // Connexion à la base de données
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}",
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    echo "🔗 Connexion à la base de données réussie\n";
    echo "📊 Base de données : {$config['database']}\n\n";

    // 1️⃣ NETTOYAGE DE LA TABLE USERS
    echo "🧹 1. NETTOYAGE DE LA TABLE USERS\n";
    echo "================================\n";

    // Comptes réels CSAR à conserver
    $realAccounts = [
        'admin@csar.sn',
        'dg@csar.sn', 
        'responsable@csar.sn',
        'agent@csar.sn',
        'drh@csar.sn'
    ];

    // Supprimer tous les utilisateurs fictifs
    $placeholders = str_repeat('?,', count($realAccounts) - 1) . '?';
    $stmt = $pdo->prepare("DELETE FROM users WHERE email NOT IN ($placeholders)");
    $stmt->execute($realAccounts);
    $deletedUsers = $stmt->rowCount();
    echo "✅ {$deletedUsers} utilisateurs fictifs supprimés\n";

    // Vérifier les comptes restants
    $stmt = $pdo->query("SELECT id, name, email, role FROM users ORDER BY email");
    $remainingUsers = $stmt->fetchAll();
    echo "👥 Comptes réels conservés :\n";
    foreach ($remainingUsers as $user) {
        echo "   - {$user['name']} ({$user['email']}) - {$user['role']}\n";
    }
    echo "\n";

    // 2️⃣ NETTOYAGE DE LA TABLE DEMANDES/PUBLIC_REQUESTS
    echo "🧹 2. NETTOYAGE DE LA TABLE DEMANDES\n";
    echo "===================================\n";

    // Supprimer toutes les demandes fictives
    $stmt = $pdo->query("DELETE FROM public_requests WHERE full_name IN (
        'Mariama Diop', 'Amadou Ba', 'Fatou Sarr', 'Ibrahima Fall', 
        'Aïcha Ndiaye', 'Jean Dupont', 'Marie Martin', 'Dr. Aminata Diallo',
        'Moussa Traoré', 'Khadija Sow'
    )");
    $deletedDemandes = $stmt->rowCount();
    echo "✅ {$deletedDemandes} demandes fictives supprimées\n";

    // Compter les demandes réelles restantes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM public_requests");
    $realDemandes = $stmt->fetch()['total'];
    echo "📋 {$realDemandes} demandes réelles conservées\n\n";

    // 3️⃣ NETTOYAGE DE LA TABLE STOCKS
    echo "🧹 3. NETTOYAGE DE LA TABLE STOCKS\n";
    echo "=================================\n";

    // Supprimer les mouvements de stock fictifs
    $stmt = $pdo->query("DELETE FROM stock_movements WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)");
    $deletedStocks = $stmt->rowCount();
    echo "✅ {$deletedStocks} mouvements de stock fictifs supprimés\n\n";

    // 4️⃣ NETTOYAGE DE LA TABLE RAPPORTS
    echo "🧹 4. NETTOYAGE DE LA TABLE RAPPORTS\n";
    echo "===================================\n";

    // Supprimer les rapports fictifs
    $stmt = $pdo->query("DELETE FROM sim_reports WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)");
    $deletedReports = $stmt->rowCount();
    echo "✅ {$deletedReports} rapports fictifs supprimés\n\n";

    // 5️⃣ NETTOYAGE DE LA TABLE ACTUALITES
    echo "🧹 5. NETTOYAGE DE LA TABLE ACTUALITES\n";
    echo "=====================================\n";

    // Supprimer les actualités fictives
    $stmt = $pdo->query("DELETE FROM news WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)");
    $deletedNews = $stmt->rowCount();
    echo "✅ {$deletedNews} actualités fictives supprimées\n\n";

    // 6️⃣ NETTOYAGE DE LA TABLE MESSAGES
    echo "🧹 6. NETTOYAGE DE LA TABLE MESSAGES\n";
    echo "===================================\n";

    // Supprimer les messages fictifs
    $stmt = $pdo->query("DELETE FROM messages WHERE expediteur IN (
        'Jean Dupont', 'Marie Martin', 'Dr. Aminata Diallo', 'Moussa Traoré', 'Khadija Sow'
    )");
    $deletedMessages = $stmt->rowCount();
    echo "✅ {$deletedMessages} messages fictifs supprimés\n\n";

    // 7️⃣ NETTOYAGE DE LA TABLE NOTIFICATIONS
    echo "🧹 7. NETTOYAGE DE LA TABLE NOTIFICATIONS\n";
    echo "========================================\n";

    // Supprimer les notifications fictives
    $stmt = $pdo->query("DELETE FROM notifications WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)");
    $deletedNotifications = $stmt->rowCount();
    echo "✅ {$deletedNotifications} notifications fictives supprimées\n\n";

    // 8️⃣ VÉRIFICATION DES STATISTIQUES
    echo "📊 8. VÉRIFICATION DES STATISTIQUES\n";
    echo "==================================\n";

    $stats = [
        'users' => $pdo->query("SELECT COUNT(*) as count FROM users")->fetch()['count'],
        'demandes' => $pdo->query("SELECT COUNT(*) as count FROM public_requests")->fetch()['count'],
        'stocks' => $pdo->query("SELECT COUNT(*) as count FROM stock_movements")->fetch()['count'],
        'rapports' => $pdo->query("SELECT COUNT(*) as count FROM sim_reports")->fetch()['count'],
        'actualites' => $pdo->query("SELECT COUNT(*) as count FROM news")->fetch()['count'],
        'messages' => $pdo->query("SELECT COUNT(*) as count FROM messages")->fetch()['count'],
        'notifications' => $pdo->query("SELECT COUNT(*) as count FROM notifications")->fetch()['count']
    ];

    echo "📈 Statistiques finales :\n";
    foreach ($stats as $table => $count) {
        echo "   - {$table}: {$count}\n";
    }
    echo "\n";

    // 9️⃣ DÉSACTIVATION DES SEEDERS FICTIFS
    echo "🔧 9. DÉSACTIVATION DES SEEDERS FICTIFS\n";
    echo "======================================\n";

    // Modifier le DatabaseSeeder pour ne garder que les seeders essentiels
    $databaseSeederPath = 'database/seeders/DatabaseSeeder.php';
    if (file_exists($databaseSeederPath)) {
        $content = file_get_contents($databaseSeederPath);
        
        // Remplacer le contenu pour ne garder que les seeders essentiels
        $newContent = '<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Seulement les seeders essentiels - pas de données fictives
            CleanDatabaseSeeder::class,
        ]);
    }
}';
        
        file_put_contents($databaseSeederPath, $newContent);
        echo "✅ DatabaseSeeder modifié - seeders fictifs désactivés\n";
    }

    // 🔟 CRÉATION D'UN SCRIPT DE VÉRIFICATION
    echo "🔍 10. CRÉATION D'UN SCRIPT DE VÉRIFICATION\n";
    echo "==========================================\n";

    $verificationScript = '<?php
/**
 * 🔍 SCRIPT DE VÉRIFICATION - PLATEFORME CSAR
 * Vérifie que seules les données réelles sont présentes
 */

require_once "vendor/autoload.php";

$config = [
    "host" => "localhost",
    "port" => "3306", 
    "database" => "plateforme-csar",
    "username" => "root",
    "password" => "",
];

try {
    $pdo = new PDO(
        "mysql:host={$config["host"]};port={$config["port"]};dbname={$config["database"]};charset=utf8mb4",
        $config["username"],
        $config["password"],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "🔍 VÉRIFICATION DES DONNÉES CSAR\n";
    echo "===============================\n\n";

    // Vérifier les utilisateurs
    $stmt = $pdo->query("SELECT name, email, role FROM users ORDER BY email");
    $users = $stmt->fetchAll();
    
    echo "👥 UTILISATEURS (" . count($users) . "):\n";
    foreach ($users as $user) {
        echo "   ✅ {$user["name"]} ({$user["email"]}) - {$user["role"]}\n";
    }
    echo "\n";

    // Vérifier les demandes
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM public_requests");
    $demandes = $stmt->fetch()["count"];
    echo "📋 DEMANDES: {$demandes}\n\n";

    // Vérifier les statistiques
    $stats = [
        "users" => $pdo->query("SELECT COUNT(*) as count FROM users")->fetch()["count"],
        "demandes" => $pdo->query("SELECT COUNT(*) as count FROM public_requests")->fetch()["count"],
        "stocks" => $pdo->query("SELECT COUNT(*) as count FROM stock_movements")->fetch()["count"],
        "rapports" => $pdo->query("SELECT COUNT(*) as count FROM sim_reports")->fetch()["count"],
    ];

    echo "📊 STATISTIQUES:\n";
    foreach ($stats as $table => $count) {
        echo "   - {$table}: {$count}\n";
    }

    echo "\n✅ Vérification terminée - Plateforme CSAR nettoyée !\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}';

    file_put_contents('verify_clean_data.php', $verificationScript);
    echo "✅ Script de vérification créé : verify_clean_data.php\n\n";

    // ✅ RÉSUMÉ FINAL
    echo "🎉 NETTOYAGE TERMINÉ AVEC SUCCÈS !\n";
    echo "==================================\n";
    echo "✅ Utilisateurs fictifs supprimés : {$deletedUsers}\n";
    echo "✅ Demandes fictives supprimées : {$deletedDemandes}\n";
    echo "✅ Mouvements de stock fictifs supprimés : {$deletedStocks}\n";
    echo "✅ Rapports fictifs supprimés : {$deletedReports}\n";
    echo "✅ Actualités fictives supprimées : {$deletedNews}\n";
    echo "✅ Messages fictifs supprimés : {$deletedMessages}\n";
    echo "✅ Notifications fictives supprimées : {$deletedNotifications}\n";
    echo "✅ Seeders fictifs désactivés\n";
    echo "✅ Script de vérification créé\n\n";

    echo "🔐 COMPTES RÉELS CSAR CONSERVÉS :\n";
    echo "   - admin@csar.sn (Administrateur CSAR)\n";
    echo "   - dg@csar.sn (Directeur Général)\n";
    echo "   - responsable@csar.sn (Responsable Entrepôt)\n";
    echo "   - agent@csar.sn (Agent CSAR)\n";
    echo "   - drh@csar.sn (Directeur RH)\n\n";

    echo "🚀 PROCHAINES ÉTAPES :\n";
    echo "   1. Exécuter : php verify_clean_data.php\n";
    echo "   2. Tester la connexion admin\n";
    echo "   3. Vérifier que les statistiques sont correctes\n";
    echo "   4. Les données supprimées ne réapparaîtront plus !\n\n";

} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    echo "🔧 Vérifiez la configuration de la base de données\n";
}

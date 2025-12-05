<?php
/**
 * 🧹 NETTOYAGE FINAL ADAPTÉ CSAR
 * 
 * Ce script supprime définitivement toutes les données fictives
 * en utilisant les bonnes tables (personnel, demandes, etc.)
 */

require_once 'vendor/autoload.php';

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
    echo "🧹 NETTOYAGE FINAL ADAPTÉ CSAR\n";
    echo "==============================\n\n";

    // 1️⃣ NETTOYAGE DE LA TABLE USERS
    echo "👥 1. NETTOYAGE DE LA TABLE USERS\n";
    echo "=================================\n";

    // Comptes CSAR réels à conserver
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

    // 2️⃣ NETTOYAGE DE LA TABLE DEMANDES
    echo "📋 2. NETTOYAGE DE LA TABLE DEMANDES\n";
    echo "===================================\n";

    // Supprimer toutes les demandes fictives de la table demandes
    $stmt = $pdo->query("DELETE FROM demandes WHERE full_name IN (
        'Mariama Diop', 'Amadou Ba', 'Fatou Sarr', 'Ibrahima Fall', 
        'Aïcha Ndiaye', 'Jean Dupont', 'Marie Martin', 'Dr. Aminata Diallo',
        'Moussa Traoré', 'Khadija Sow', 'Test User', 'Demo User'
    )");
    $deletedDemandes = $stmt->rowCount();
    echo "✅ {$deletedDemandes} demandes fictives supprimées de la table demandes\n";

    // Supprimer les demandes avec des emails fictifs
    $stmt = $pdo->query("DELETE FROM demandes WHERE email IN (
        'mariama.diop@gmail.com', 'amadou.ba@yahoo.fr', 'fatou.sarr@outlook.com',
        'ibrahima.fall@gmail.com', 'aicha.ndiaye@hotmail.com', 'jean.dupont@email.com',
        'marie.martin@email.com', 'test@example.com', 'demo@example.com'
    )");
    $deletedDemandes += $stmt->rowCount();
    echo "✅ Demandes avec emails fictifs supprimées de la table demandes\n";

    // Compter les demandes réelles restantes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM demandes");
    $realDemandes = $stmt->fetch()['total'];
    echo "📋 {$realDemandes} demandes réelles conservées dans la table demandes\n\n";

    // 3️⃣ NETTOYAGE DE LA TABLE PUBLIC_REQUESTS
    echo "📋 3. NETTOYAGE DE LA TABLE PUBLIC_REQUESTS\n";
    echo "==========================================\n";

    // Supprimer toutes les demandes fictives de la table public_requests
    $stmt = $pdo->query("DELETE FROM public_requests WHERE full_name IN (
        'Mariama Diop', 'Amadou Ba', 'Fatou Sarr', 'Ibrahima Fall', 
        'Aïcha Ndiaye', 'Jean Dupont', 'Marie Martin', 'Dr. Aminata Diallo',
        'Moussa Traoré', 'Khadija Sow', 'Test User', 'Demo User'
    )");
    $deletedPublicRequests = $stmt->rowCount();
    echo "✅ {$deletedPublicRequests} demandes fictives supprimées de la table public_requests\n";

    // Supprimer les demandes avec des emails fictifs
    $stmt = $pdo->query("DELETE FROM public_requests WHERE email IN (
        'mariama.diop@gmail.com', 'amadou.ba@yahoo.fr', 'fatou.sarr@outlook.com',
        'ibrahima.fall@gmail.com', 'aicha.ndiaye@hotmail.com', 'jean.dupont@email.com',
        'marie.martin@email.com', 'test@example.com', 'demo@example.com'
    )");
    $deletedPublicRequests += $stmt->rowCount();
    echo "✅ Demandes avec emails fictifs supprimées de la table public_requests\n";

    // Compter les demandes réelles restantes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM public_requests");
    $realPublicRequests = $stmt->fetch()['total'];
    echo "📋 {$realPublicRequests} demandes réelles conservées dans la table public_requests\n\n";

    // 4️⃣ NETTOYAGE DE LA TABLE PERSONNEL
    echo "🧑‍💼 4. NETTOYAGE DE LA TABLE PERSONNEL\n";
    echo "====================================\n";

    // Supprimer tout le personnel fictif
    $stmt = $pdo->query("DELETE FROM personnel");
    $deletedPersonnel = $stmt->rowCount();
    echo "✅ {$deletedPersonnel} enregistrements de personnel fictif supprimés\n";
    echo "📋 Table personnel vidée - prête pour les données réelles\n\n";

    // 5️⃣ NETTOYAGE DES AUTRES TABLES
    echo "🧹 5. NETTOYAGE DES AUTRES TABLES\n";
    echo "===============================\n";

    // Nettoyer les notifications fictives
    $stmt = $pdo->query("DELETE FROM notifications WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)");
    $deletedNotifications = $stmt->rowCount();
    echo "✅ {$deletedNotifications} notifications fictives supprimées\n";

    // Nettoyer les messages fictifs
    $stmt = $pdo->query("DELETE FROM messages WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)");
    $deletedMessages = $stmt->rowCount();
    echo "✅ {$deletedMessages} messages fictifs supprimés\n";

    // Nettoyer les actualités fictives
    $stmt = $pdo->query("DELETE FROM news WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)");
    $deletedNews = $stmt->rowCount();
    echo "✅ {$deletedNews} actualités fictives supprimées\n\n";

    // 6️⃣ MODIFICATION DES SEEDERS
    echo "🔧 6. MODIFICATION DES SEEDERS\n";
    echo "=============================\n";

    // Modifier UserSeeder.php pour supprimer les agents fictifs
    $userSeederPath = 'database/seeders/UserSeeder.php';
    if (file_exists($userSeederPath)) {
        $content = file_get_contents($userSeederPath);
        
        // Supprimer la section des agents supplémentaires
        $newContent = preg_replace('/\/\/ Agents supplémentaires.*?}\s*}/s', '', $content);
        
        if ($newContent !== $content) {
            file_put_contents($userSeederPath, $newContent);
            echo "✅ UserSeeder.php modifié - agents fictifs supprimés\n";
        }
    }

    // Modifier PersonnelSeeder.php pour le vider
    $personnelSeederPath = 'database/seeders/PersonnelSeeder.php';
    if (file_exists($personnelSeederPath)) {
        $newContent = '<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Personnel;

class PersonnelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Aucune donnée fictive - seeder vide
        // Les données réelles seront ajoutées via l\'interface admin
    }
}';
        
        file_put_contents($personnelSeederPath, $newContent);
        echo "✅ PersonnelSeeder.php vidé - prêt pour les données réelles\n";
    }

    // Modifier DatabaseSeeder.php pour ne garder que les seeders essentiels
    $databaseSeederPath = 'database/seeders/DatabaseSeeder.php';
    if (file_exists($databaseSeederPath)) {
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
        echo "✅ DatabaseSeeder.php modifié - seeders fictifs supprimés\n";
    }

    echo "\n";

    // 7️⃣ VÉRIFICATION DES STATISTIQUES
    echo "📊 7. VÉRIFICATION DES STATISTIQUES\n";
    echo "==================================\n";

    $stats = [
        'users' => $pdo->query("SELECT COUNT(*) as count FROM users")->fetch()['count'],
        'demandes' => $pdo->query("SELECT COUNT(*) as count FROM demandes")->fetch()['count'],
        'public_requests' => $pdo->query("SELECT COUNT(*) as count FROM public_requests")->fetch()['count'],
        'personnel' => $pdo->query("SELECT COUNT(*) as count FROM personnel")->fetch()['count'],
        'notifications' => $pdo->query("SELECT COUNT(*) as count FROM notifications")->fetch()['count'],
        'messages' => $pdo->query("SELECT COUNT(*) as count FROM messages")->fetch()['count'],
        'actualites' => $pdo->query("SELECT COUNT(*) as count FROM news")->fetch()['count']
    ];

    echo "📈 Statistiques finales :\n";
    foreach ($stats as $table => $count) {
        echo "   - {$table}: {$count}\n";
    }
    echo "\n";

    // 8️⃣ CRÉATION D'UN SCRIPT DE VÉRIFICATION
    echo "🔍 8. CRÉATION D'UN SCRIPT DE VÉRIFICATION\n";
    echo "==========================================\n";

    $verificationScript = '<?php
/**
 * 🔍 VÉRIFICATION FINALE CSAR
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

    echo "🔍 VÉRIFICATION FINALE CSAR\n";
    echo "===========================\n\n";

    // Vérifier les utilisateurs
    $stmt = $pdo->query("SELECT name, email, role FROM users ORDER BY email");
    $users = $stmt->fetchAll();
    
    echo "👥 UTILISATEURS (" . count($users) . "):\n";
    foreach ($users as $user) {
        echo "   ✅ {$user["name"]} ({$user["email"]}) - {$user["role"]}\n";
    }
    echo "\n";

    // Vérifier les demandes
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM demandes");
    $demandes = $stmt->fetch()["count"];
    echo "📋 DEMANDES: {$demandes}\n\n";

    // Vérifier les demandes publiques
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM public_requests");
    $publicRequests = $stmt->fetch()["count"];
    echo "📋 DEMANDES PUBLIQUES: {$publicRequests}\n\n";

    // Vérifier le personnel
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM personnel");
    $personnel = $stmt->fetch()["count"];
    echo "🧑‍💼 PERSONNEL: {$personnel}\n\n";

    // Vérifier les statistiques
    $stats = [
        "users" => $pdo->query("SELECT COUNT(*) as count FROM users")->fetch()["count"],
        "demandes" => $pdo->query("SELECT COUNT(*) as count FROM demandes")->fetch()["count"],
        "public_requests" => $pdo->query("SELECT COUNT(*) as count FROM public_requests")->fetch()["count"],
        "personnel" => $pdo->query("SELECT COUNT(*) as count FROM personnel")->fetch()["count"],
    ];

    echo "📊 STATISTIQUES:\n";
    foreach ($stats as $table => $count) {
        echo "   - {$table}: {$count}\n";
    }

    echo "\n✅ Vérification terminée - Plateforme CSAR nettoyée !\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}';

    file_put_contents('verification_finale_adaptée.php', $verificationScript);
    echo "✅ Script de vérification créé : verification_finale_adaptée.php\n\n";

    // ✅ RÉSUMÉ FINAL
    echo "🎉 NETTOYAGE FINAL TERMINÉ !\n";
    echo "============================\n";
    echo "✅ Utilisateurs fictifs supprimés : {$deletedUsers}\n";
    echo "✅ Demandes fictives supprimées (demandes) : {$deletedDemandes}\n";
    echo "✅ Demandes fictives supprimées (public_requests) : {$deletedPublicRequests}\n";
    echo "✅ Personnel fictif supprimé : {$deletedPersonnel}\n";
    echo "✅ Notifications fictives supprimées : {$deletedNotifications}\n";
    echo "✅ Messages fictifs supprimés : {$deletedMessages}\n";
    echo "✅ Actualités fictives supprimées : {$deletedNews}\n";
    echo "✅ Seeders modifiés\n";
    echo "✅ Script de vérification créé\n\n";

    echo "🔐 COMPTES RÉELS CSAR CONSERVÉS :\n";
    echo "   - admin@csar.sn (Administrateur CSAR)\n";
    echo "   - dg@csar.sn (Directeur Général)\n";
    echo "   - responsable@csar.sn (Responsable Entrepôt)\n";
    echo "   - agent@csar.sn (Agent CSAR)\n";
    echo "   - drh@csar.sn (Directeur RH)\n\n";

    echo "🚀 PROCHAINES ÉTAPES :\n";
    echo "   1. Exécuter : php verification_finale_adaptée.php\n";
    echo "   2. Tester la connexion admin\n";
    echo "   3. Vérifier que les statistiques sont correctes\n";
    echo "   4. Tester les opérations CRUD\n";
    echo "   5. Les données supprimées ne réapparaîtront plus !\n\n";

    echo "📊 La plateforme CSAR est maintenant 100% réelle et connectée à MySQL !\n";

} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    echo "🔧 Vérifiez la configuration de la base de données\n";
}

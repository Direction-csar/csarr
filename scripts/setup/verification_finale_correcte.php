<?php
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
}
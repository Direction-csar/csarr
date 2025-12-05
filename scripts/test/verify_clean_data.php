<?php
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
}
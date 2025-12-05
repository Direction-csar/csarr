<?php

/**
 * Script de vérification des connexions à la base de données
 */

echo "🔍 Vérification des connexions à la base de données...\n";
echo "====================================================\n\n";

// Configuration MySQL
$config = [
    "host" => "localhost",
    "database" => "csar_platform_2025",
    "username" => "laravel_user",
    "password" => "csar@2025Host1"
];

// Test de connexion directe
echo "1️⃣ Test de connexion directe MySQL...\n";
try {
    $pdo = new PDO(
        "mysql:host={$config["host"]};dbname={$config["database"]};charset=utf8mb4",
        $config["username"],
        $config["password"]
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion MySQL directe réussie\n";
    
    // Vérifier les tables principales
    $tables = ["users", "messages", "notifications", "newsletter_subscribers", "contact_messages"];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE \"$table\"");
        if ($stmt->rowCount() > 0) {
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "   📊 Table $table: $count enregistrements\n";
        } else {
            echo "   ❌ Table $table: non trouvée\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion MySQL: " . $e->getMessage() . "\n";
}

echo "\n";

// Test de connexion Laravel
echo "2️⃣ Test de connexion Laravel...\n";
try {
    require_once "vendor/autoload.php";
    
    $app = require_once "bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    
    $userCount = \App\Models\User::count();
    $messageCount = \App\Models\Message::count();
    $notificationCount = \App\Models\Notification::count();
    
    echo "✅ Connexion Laravel réussie\n";
    echo "   👥 Utilisateurs: $userCount\n";
    echo "   📧 Messages: $messageCount\n";
    echo "   🔔 Notifications: $notificationCount\n";
    
} catch (Exception $e) {
    echo "❌ Erreur de connexion Laravel: " . $e->getMessage() . "\n";
}

echo "\n";

// Test des interfaces
echo "3️⃣ Test des interfaces...\n";
$interfaces = [
    "Admin" => "admin",
    "DG" => "dg", 
    "DRH" => "drh",
    "Agent" => "agent",
    "Responsable" => "entrepot"
];

foreach ($interfaces as $name => $route) {
    echo "   🔗 Interface $name: /$route\n";
}

echo "\n✅ Vérification terminée\n";

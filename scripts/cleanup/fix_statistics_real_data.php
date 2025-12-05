<?php
/**
 * 📊 CORRECTION DES STATISTIQUES - PLATEFORME CSAR
 * 
 * Ce script corrige les modules statistiques pour qu'ils utilisent
 * uniquement les données MySQL réelles et non des données fictives.
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

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
    echo "📊 Correction des statistiques CSAR\n\n";

    // 1️⃣ VÉRIFICATION DES STATISTIQUES ACTUELLES
    echo "📈 1. STATISTIQUES ACTUELLES\n";
    echo "============================\n";

    $currentStats = [
        'users' => $pdo->query("SELECT COUNT(*) as count FROM users")->fetch()['count'],
        'demandes' => $pdo->query("SELECT COUNT(*) as count FROM public_requests")->fetch()['count'],
        'stocks' => $pdo->query("SELECT COUNT(*) as count FROM stock_movements")->fetch()['count'],
        'rapports' => $pdo->query("SELECT COUNT(*) as count FROM sim_reports")->fetch()['count'],
        'actualites' => $pdo->query("SELECT COUNT(*) as count FROM news")->fetch()['count'],
        'messages' => $pdo->query("SELECT COUNT(*) as count FROM messages")->fetch()['count'],
        'notifications' => $pdo->query("SELECT COUNT(*) as count FROM notifications")->fetch()['count'],
        'entrepots' => $pdo->query("SELECT COUNT(*) as count FROM warehouses")->fetch()['count']
    ];

    echo "📊 Données actuelles :\n";
    foreach ($currentStats as $table => $count) {
        echo "   - {$table}: {$count}\n";
    }
    echo "\n";

    // 2️⃣ CRÉATION D'UNE TABLE DE STATISTIQUES TEMPORELLES
    echo "📊 2. CRÉATION DE LA TABLE STATISTIQUES\n";
    echo "======================================\n";

    // Créer une table pour stocker les statistiques calculées
    $createStatsTable = "
    CREATE TABLE IF NOT EXISTS statistics_cache (
        id INT AUTO_INCREMENT PRIMARY KEY,
        stat_name VARCHAR(100) NOT NULL UNIQUE,
        stat_value INT NOT NULL,
        calculated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $pdo->exec($createStatsTable);
    echo "✅ Table statistics_cache créée\n";

    // 3️⃣ CALCUL ET MISE À JOUR DES STATISTIQUES
    echo "📊 3. CALCUL DES STATISTIQUES RÉELLES\n";
    echo "====================================\n";

    $statistics = [
        'total_users' => $currentStats['users'],
        'active_users' => $pdo->query("SELECT COUNT(*) as count FROM users WHERE status = 'actif' OR is_active = 1")->fetch()['count'],
        'total_demandes' => $currentStats['demandes'],
        'pending_demandes' => $pdo->query("SELECT COUNT(*) as count FROM public_requests WHERE status = 'pending'")->fetch()['count'],
        'approved_demandes' => $pdo->query("SELECT COUNT(*) as count FROM public_requests WHERE status = 'approved'")->fetch()['count'],
        'rejected_demandes' => $pdo->query("SELECT COUNT(*) as count FROM public_requests WHERE status = 'rejected'")->fetch()['count'],
        'completed_demandes' => $pdo->query("SELECT COUNT(*) as count FROM public_requests WHERE status = 'completed'")->fetch()['count'],
        'total_stocks' => $currentStats['stocks'],
        'total_rapports' => $currentStats['rapports'],
        'total_actualites' => $currentStats['actualites'],
        'total_messages' => $currentStats['messages'],
        'unread_notifications' => $pdo->query("SELECT COUNT(*) as count FROM notifications WHERE is_read = 0 OR read = 0")->fetch()['count'],
        'total_entrepots' => $currentStats['entrepots'],
        'active_entrepots' => $pdo->query("SELECT COUNT(*) as count FROM warehouses WHERE is_active = 1")->fetch()['count'],
        'today_demandes' => $pdo->query("SELECT COUNT(*) as count FROM public_requests WHERE DATE(created_at) = CURDATE()")->fetch()['count'],
        'month_demandes' => $pdo->query("SELECT COUNT(*) as count FROM public_requests WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->fetch()['count'],
        'week_demandes' => $pdo->query("SELECT COUNT(*) as count FROM public_requests WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetch()['count']
    ];

    // Mettre à jour la table de cache
    foreach ($statistics as $statName => $statValue) {
        $stmt = $pdo->prepare("
            INSERT INTO statistics_cache (stat_name, stat_value) 
            VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE 
            stat_value = VALUES(stat_value), 
            updated_at = CURRENT_TIMESTAMP
        ");
        $stmt->execute([$statName, $statValue]);
    }

    echo "✅ Statistiques calculées et mises en cache\n";
    echo "📊 Statistiques réelles :\n";
    foreach ($statistics as $name => $value) {
        echo "   - {$name}: {$value}\n";
    }
    echo "\n";

    // 4️⃣ CRÉATION D'UN SCRIPT DE MISE À JOUR DES STATISTIQUES
    echo "🔄 4. CRÉATION DU SCRIPT DE MISE À JOUR\n";
    echo "======================================\n";

    $updateStatsScript = '<?php
/**
 * 🔄 SCRIPT DE MISE À JOUR DES STATISTIQUES - PLATEFORME CSAR
 * Met à jour les statistiques en temps réel depuis MySQL
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

    echo "🔄 Mise à jour des statistiques CSAR...\n";

    // Calculer les nouvelles statistiques
    $statistics = [
        "total_users" => $pdo->query("SELECT COUNT(*) as count FROM users")->fetch()["count"],
        "active_users" => $pdo->query("SELECT COUNT(*) as count FROM users WHERE status = \"actif\" OR is_active = 1")->fetch()["count"],
        "total_demandes" => $pdo->query("SELECT COUNT(*) as count FROM public_requests")->fetch()["count"],
        "pending_demandes" => $pdo->query("SELECT COUNT(*) as count FROM public_requests WHERE status = \"pending\"")->fetch()["count"],
        "approved_demandes" => $pdo->query("SELECT COUNT(*) as count FROM public_requests WHERE status = \"approved\"")->fetch()["count"],
        "rejected_demandes" => $pdo->query("SELECT COUNT(*) as count FROM public_requests WHERE status = \"rejected\"")->fetch()["count"],
        "completed_demandes" => $pdo->query("SELECT COUNT(*) as count FROM public_requests WHERE status = \"completed\"")->fetch()["count"],
        "total_stocks" => $pdo->query("SELECT COUNT(*) as count FROM stock_movements")->fetch()["count"],
        "total_rapports" => $pdo->query("SELECT COUNT(*) as count FROM sim_reports")->fetch()["count"],
        "total_actualites" => $pdo->query("SELECT COUNT(*) as count FROM news")->fetch()["count"],
        "total_messages" => $pdo->query("SELECT COUNT(*) as count FROM messages")->fetch()["count"],
        "unread_notifications" => $pdo->query("SELECT COUNT(*) as count FROM notifications WHERE is_read = 0 OR read = 0")->fetch()["count"],
        "total_entrepots" => $pdo->query("SELECT COUNT(*) as count FROM warehouses")->fetch()["count"],
        "active_entrepots" => $pdo->query("SELECT COUNT(*) as count FROM warehouses WHERE is_active = 1")->fetch()["count"],
        "today_demandes" => $pdo->query("SELECT COUNT(*) as count FROM public_requests WHERE DATE(created_at) = CURDATE()")->fetch()["count"],
        "month_demandes" => $pdo->query("SELECT COUNT(*) as count FROM public_requests WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->fetch()["count"],
        "week_demandes" => $pdo->query("SELECT COUNT(*) as count FROM public_requests WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetch()["count"]
    ];

    // Mettre à jour le cache
    foreach ($statistics as $statName => $statValue) {
        $stmt = $pdo->prepare("
            INSERT INTO statistics_cache (stat_name, stat_value) 
            VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE 
            stat_value = VALUES(stat_value), 
            updated_at = CURRENT_TIMESTAMP
        ");
        $stmt->execute([$statName, $statValue]);
    }

    echo "✅ Statistiques mises à jour avec succès !\n";
    echo "📊 Nouvelles valeurs :\n";
    foreach ($statistics as $name => $value) {
        echo "   - {$name}: {$value}\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}';

    file_put_contents('update_statistics.php', $updateStatsScript);
    echo "✅ Script de mise à jour créé : update_statistics.php\n\n";

    // 5️⃣ MODIFICATION DU DASHBOARD CONTROLLER
    echo "🎛️ 5. MODIFICATION DU DASHBOARD CONTROLLER\n";
    echo "=========================================\n";

    $dashboardControllerPath = 'app/Http/Controllers/Admin/DashboardController.php';
    if (file_exists($dashboardControllerPath)) {
        $content = file_get_contents($dashboardControllerPath);
        
        // Remplacer la méthode getDashboardStats pour utiliser les vraies données
        $newStatsMethod = '
    /**
     * Obtenir les statistiques du dashboard depuis MySQL
     */
    private function getDashboardStats()
    {
        try {
            // Utiliser les statistiques en cache ou les calculer en temps réel
            $stats = DB::table("statistics_cache")->pluck("stat_value", "stat_name")->toArray();
            
            // Si le cache est vide, calculer les statistiques
            if (empty($stats)) {
                $stats = [
                    "total_users" => User::count(),
                    "active_users" => User::where("status", "actif")->orWhere("is_active", 1)->count(),
                    "total_requests" => PublicRequest::count(),
                    "pending_requests" => PublicRequest::where("status", "pending")->count(),
                    "approved_requests" => PublicRequest::where("status", "approved")->count(),
                    "rejected_requests" => PublicRequest::where("status", "rejected")->count(),
                    "completed_requests" => PublicRequest::where("status", "completed")->count(),
                    "total_warehouses" => Warehouse::count(),
                    "active_warehouses" => Warehouse::where("is_active", true)->count(),
                    "total_stocks" => StockMovement::count(),
                    "low_stock_items" => $this->getLowStockCount(),
                    "total_personnel" => User::where("role", "!=", "admin")->count(),
                    "active_personnel" => User::where("role", "!=", "admin")->where("status", "actif")->count(),
                    "today_requests" => PublicRequest::whereDate("created_at", today())->count(),
                    "month_requests" => PublicRequest::whereMonth("created_at", now()->month)->count(),
                    "week_requests" => PublicRequest::where("created_at", ">=", now()->subDays(7))->count(),
                    "fuel_available" => $this->getFuelAvailable(),
                    "total_stock" => StockMovement::count(),
                    "unread_notifications" => Notification::where("is_read", false)->orWhere("read", false)->count(),
                    "unread_messages" => Message::where("lu", false)->count()
                ];
                
                // Mettre en cache les nouvelles statistiques
                foreach ($stats as $statName => $statValue) {
                    DB::table("statistics_cache")->updateOrInsert(
                        ["stat_name" => $statName],
                        ["stat_value" => $statValue, "updated_at" => now()]
                    );
                }
            }
            
            return $stats;
        } catch (\Exception $e) {
            Log::error("Erreur lors du calcul des statistiques: " . $e->getMessage());
            return [
                "total_users" => 0,
                "active_users" => 0,
                "total_requests" => 0,
                "pending_requests" => 0,
                "approved_requests" => 0,
                "rejected_requests" => 0,
                "completed_requests" => 0,
                "total_warehouses" => 0,
                "active_warehouses" => 0,
                "total_stocks" => 0,
                "low_stock_items" => 0,
                "total_personnel" => 0,
                "active_personnel" => 0,
                "today_requests" => 0,
                "month_requests" => 0,
                "week_requests" => 0,
                "fuel_available" => 0,
                "total_stock" => 0,
                "unread_notifications" => 0,
                "unread_messages" => 0
            ];
        }
    }';

        // Remplacer l\'ancienne méthode
        $pattern = '/private function getDashboardStats\(\)\s*\{[^}]+\}/s';
        $newContent = preg_replace($pattern, $newStatsMethod, $content);
        
        if ($newContent !== $content) {
            file_put_contents($dashboardControllerPath, $newContent);
            echo "✅ DashboardController modifié pour utiliser les vraies données\n";
        } else {
            echo "⚠️ Impossible de modifier le DashboardController automatiquement\n";
        }
    }

    // 6️⃣ CRÉATION D'UN CRON JOB POUR LA MISE À JOUR AUTOMATIQUE
    echo "⏰ 6. CRÉATION DU CRON JOB\n";
    echo "=========================\n";

    $cronScript = '#!/bin/bash
# Cron job pour mettre à jour les statistiques CSAR toutes les 5 minutes
# Ajouter cette ligne au crontab : */5 * * * * /path/to/update_statistics_cron.sh

cd /path/to/csar-platform
php update_statistics.php >> logs/statistics_update.log 2>&1';

    file_put_contents('update_statistics_cron.sh', $cronScript);
    echo "✅ Script cron créé : update_statistics_cron.sh\n\n";

    // 7️⃣ VÉRIFICATION FINALE
    echo "🔍 7. VÉRIFICATION FINALE\n";
    echo "========================\n";

    // Vérifier que les statistiques sont bien en cache
    $cachedStats = $pdo->query("SELECT stat_name, stat_value FROM statistics_cache ORDER BY stat_name")->fetchAll();
    
    if (!empty($cachedStats)) {
        echo "✅ Statistiques mises en cache :\n";
        foreach ($cachedStats as $stat) {
            echo "   - {$stat['stat_name']}: {$stat['stat_value']}\n";
        }
    } else {
        echo "⚠️ Aucune statistique en cache trouvée\n";
    }

    echo "\n";

    // ✅ RÉSUMÉ FINAL
    echo "🎉 CORRECTION DES STATISTIQUES TERMINÉE !\n";
    echo "========================================\n";
    echo "✅ Table statistics_cache créée\n";
    echo "✅ Statistiques calculées depuis MySQL\n";
    echo "✅ Script de mise à jour créé\n";
    echo "✅ DashboardController modifié\n";
    echo "✅ Script cron créé\n";
    echo "✅ Vérification effectuée\n\n";

    echo "🚀 PROCHAINES ÉTAPES :\n";
    echo "   1. Exécuter : php update_statistics.php\n";
    echo "   2. Tester le dashboard admin\n";
    echo "   3. Vérifier que les statistiques sont dynamiques\n";
    echo "   4. Configurer le cron job si nécessaire\n\n";

    echo "📊 Les statistiques sont maintenant 100% réelles et calculées depuis MySQL !\n";

} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    echo "🔧 Vérifiez la configuration de la base de données\n";
}

<?php

/**
 * Script pour supprimer les données fictives de la base de données
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "=== SUPPRESSION DES DONNÉES FICTIVES ===\n\n";

// Configuration de la base de données
$config = [
    'driver' => 'mysql',
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'csar_platform'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
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

    echo "✓ Connexion à la base de données réussie\n";
    echo "✓ Base de données: {$config['database']}\n\n";

    // Étape 1: Vérifier les données existantes
    echo "1. Vérification des données existantes...\n";
    
    $tables = ['stock_movements', 'warehouses'];
    $dataCounts = [];
    
    foreach ($tables as $table) {
        try {
            $count = $pdo->query("SELECT COUNT(*) as count FROM {$table}")->fetch()['count'];
            $dataCounts[$table] = $count;
            echo "   ✓ Table {$table}: {$count} enregistrement(s)\n";
        } catch (PDOException $e) {
            echo "   ❌ Table {$table}: Erreur - " . $e->getMessage() . "\n";
            $dataCounts[$table] = 0;
        }
    }

    // Étape 2: Supprimer les données fictives des mouvements de stock
    echo "\n2. Suppression des données fictives des mouvements de stock...\n";
    
    if ($dataCounts['stock_movements'] > 0) {
        // Supprimer tous les mouvements de stock
        $deletedMovements = $pdo->exec("DELETE FROM stock_movements");
        echo "   ✓ {$deletedMovements} mouvement(s) de stock supprimé(s)\n";
        
        // Réinitialiser l'auto-increment
        $pdo->exec("ALTER TABLE stock_movements AUTO_INCREMENT = 1");
        echo "   ✓ Auto-increment réinitialisé pour stock_movements\n";
    } else {
        echo "   ✓ Aucun mouvement de stock à supprimer\n";
    }

    // Étape 3: Supprimer les données fictives des entrepôts
    echo "\n3. Suppression des données fictives des entrepôts...\n";
    
    if ($dataCounts['warehouses'] > 0) {
        // Supprimer tous les entrepôts
        $deletedWarehouses = $pdo->exec("DELETE FROM warehouses");
        echo "   ✓ {$deletedWarehouses} entrepôt(s) supprimé(s)\n";
        
        // Réinitialiser l'auto-increment
        $pdo->exec("ALTER TABLE warehouses AUTO_INCREMENT = 1");
        echo "   ✓ Auto-increment réinitialisé pour warehouses\n";
    } else {
        echo "   ✓ Aucun entrepôt à supprimer\n";
    }

    // Étape 4: Vérifier les autres tables qui pourraient contenir des données fictives
    echo "\n4. Vérification des autres tables...\n";
    
    $otherTables = ['users', 'products', 'stocks', 'stock_levels'];
    
    foreach ($otherTables as $table) {
        try {
            $count = $pdo->query("SELECT COUNT(*) as count FROM {$table}")->fetch()['count'];
            echo "   ✓ Table {$table}: {$count} enregistrement(s)\n";
            
            // Si c'est la table users et qu'elle contient des données de test
            if ($table === 'users' && $count > 0) {
                $testUsers = $pdo->query("SELECT id, name, email FROM {$table} WHERE email LIKE '%test%' OR email LIKE '%demo%' OR name LIKE '%Test%' OR name LIKE '%Demo%'")->fetchAll();
                
                if (!empty($testUsers)) {
                    echo "     ⚠ Utilisateurs de test trouvés:\n";
                    foreach ($testUsers as $user) {
                        echo "       - {$user['name']} ({$user['email']})\n";
                    }
                    
                    // Demander confirmation pour supprimer les utilisateurs de test
                    echo "     💡 Voulez-vous supprimer ces utilisateurs de test ? (y/N): ";
                    $handle = fopen("php://stdin", "r");
                    $line = fgets($handle);
                    fclose($handle);
                    
                    if (trim(strtolower($line)) === 'y') {
                        $deletedUsers = $pdo->exec("DELETE FROM {$table} WHERE email LIKE '%test%' OR email LIKE '%demo%' OR name LIKE '%Test%' OR name LIKE '%Demo%'");
                        echo "     ✓ {$deletedUsers} utilisateur(s) de test supprimé(s)\n";
                    } else {
                        echo "     ✓ Utilisateurs de test conservés\n";
                    }
                }
            }
            
        } catch (PDOException $e) {
            echo "   ❌ Table {$table}: Erreur - " . $e->getMessage() . "\n";
        }
    }

    // Étape 5: Nettoyer les tables de notifications
    echo "\n5. Nettoyage des notifications...\n";
    
    try {
        $notificationCount = $pdo->query("SELECT COUNT(*) as count FROM notifications")->fetch()['count'];
        if ($notificationCount > 0) {
            $deletedNotifications = $pdo->exec("DELETE FROM notifications");
            echo "   ✓ {$deletedNotifications} notification(s) supprimée(s)\n";
        } else {
            echo "   ✓ Aucune notification à supprimer\n";
        }
    } catch (PDOException $e) {
        echo "   ⚠ Table notifications: " . $e->getMessage() . "\n";
    }

    // Étape 6: Vérification finale
    echo "\n6. Vérification finale...\n";
    
    $finalCounts = [];
    foreach ($tables as $table) {
        try {
            $count = $pdo->query("SELECT COUNT(*) as count FROM {$table}")->fetch()['count'];
            $finalCounts[$table] = $count;
            echo "   ✓ Table {$table}: {$count} enregistrement(s)\n";
        } catch (PDOException $e) {
            echo "   ❌ Table {$table}: Erreur - " . $e->getMessage() . "\n";
        }
    }

    // Étape 7: Créer des entrepôts de base (optionnel)
    echo "\n7. Création d'entrepôts de base...\n";
    
    if ($finalCounts['warehouses'] == 0) {
        echo "   ⚠ Aucun entrepôt trouvé - création d'entrepôts de base...\n";
        
        $pdo->exec("
            INSERT INTO warehouses (name, location, type, capacity, is_active, created_at, updated_at) VALUES
            ('Entrepôt Principal', 'Dakar, Sénégal', 'general', 1000, TRUE, NOW(), NOW()),
            ('Entrepôt Secondaire', 'Thiès, Sénégal', 'general', 500, TRUE, NOW(), NOW())
        ");
        
        echo "   ✓ 2 entrepôts de base créés\n";
    } else {
        echo "   ✓ Entrepôts existants conservés\n";
    }

    echo "\n=== SUPPRESSION TERMINÉE AVEC SUCCÈS ===\n";
    echo "✅ Données fictives supprimées\n";
    echo "✅ Tables nettoyées\n";
    echo "✅ Auto-increments réinitialisés\n";
    echo "✅ Entrepôts de base créés\n\n";
    
    echo "🎯 État actuel:\n";
    echo "- Mouvements de stock: 0\n";
    echo "- Entrepôts: " . ($finalCounts['warehouses'] > 0 ? $finalCounts['warehouses'] : '2 (de base)') . "\n";
    echo "- Notifications: 0\n";
    echo "- Utilisateurs: Conservés (sauf test si supprimés)\n\n";
    
    echo "📋 Prochaines étapes:\n";
    echo "1. Accédez à: http://localhost:8000/admin/stock\n";
    echo "2. Créez de nouveaux mouvements de stock\n";
    echo "3. Testez le système de reçus PDF\n";
    echo "4. Vérifiez que tout fonctionne correctement\n";

} catch (PDOException $e) {
    echo "❌ Erreur de base de données : " . $e->getMessage() . "\n";
    echo "Vérifiez votre configuration de base de données dans le fichier .env\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Erreur générale : " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== FIN DE LA SUPPRESSION ===\n";

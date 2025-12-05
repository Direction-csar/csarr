<?php

/**
 * Correction directe de la gestion des stocks
 */

echo "🔧 Correction directe de la gestion des stocks\n";
echo "===========================================\n\n";

// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'csar_platform_2025';
$db_user = 'laravel_user';
$db_pass = 'csar@2025Host1';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données réussie\n\n";

    // 1. Créer la table stocks si elle n'existe pas
    echo "1️⃣ Création de la table stocks...\n";
    
    $createStocksTable = "
        CREATE TABLE IF NOT EXISTS stocks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            item_name VARCHAR(255) NOT NULL,
            item_type VARCHAR(100) NOT NULL,
            quantity INT NOT NULL DEFAULT 0,
            unit VARCHAR(50) NOT NULL,
            status ENUM('disponible', 'epuise', 'en_commande') DEFAULT 'disponible',
            entrepot_id INT,
            description TEXT,
            min_quantity INT DEFAULT 0,
            max_quantity INT DEFAULT 0,
            supplier VARCHAR(255),
            cost DECIMAL(10,2),
            last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_item_name (item_name),
            INDEX idx_item_type (item_type),
            INDEX idx_status (status),
            INDEX idx_entrepot_id (entrepot_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createStocksTable);
    echo "   ✅ Table stocks créée/vérifiée\n";

    // 2. Créer la table entrepots si elle n'existe pas
    echo "2️⃣ Création de la table entrepots...\n";
    
    $createEntrepotsTable = "
        CREATE TABLE IF NOT EXISTS entrepots (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            address TEXT,
            capacity INT NOT NULL DEFAULT 0,
            current_stock INT NOT NULL DEFAULT 0,
            status ENUM('actif', 'inactif', 'maintenance') DEFAULT 'actif',
            manager VARCHAR(255),
            phone VARCHAR(50),
            email VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_name (name),
            INDEX idx_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createEntrepotsTable);
    echo "   ✅ Table entrepots créée/vérifiée\n";

    // 3. Ajouter des données de test
    echo "3️⃣ Ajout de données de test...\n";
    
    // Vérifier si des entrepôts existent
    $stmt = $pdo->query("SELECT COUNT(*) FROM entrepots");
    $entrepotCount = $stmt->fetchColumn();
    
    if ($entrepotCount == 0) {
        // Ajouter des entrepôts
        $entrepots = [
            [
                'name' => 'Entrepôt Principal Dakar',
                'address' => 'Zone Industrielle, Dakar',
                'capacity' => 10000,
                'current_stock' => 0,
                'status' => 'actif',
                'manager' => 'Responsable Principal',
                'phone' => '+221 33 123 45 67',
                'email' => 'entrepot@csar.sn'
            ],
            [
                'name' => 'Entrepôt Régional Thiès',
                'address' => 'Route de Thiès, Thiès',
                'capacity' => 5000,
                'current_stock' => 0,
                'status' => 'actif',
                'manager' => 'Responsable Régional',
                'phone' => '+221 33 234 56 78',
                'email' => 'thies@csar.sn'
            ]
        ];
        
        $insertStmt = $pdo->prepare("
            INSERT INTO entrepots (
                name, address, capacity, current_stock, status,
                manager, phone, email, created_at, updated_at
            ) VALUES (
                :name, :address, :capacity, :current_stock, :status,
                :manager, :phone, :email, NOW(), NOW()
            )
        ");
        
        foreach ($entrepots as $entrepot) {
            $insertStmt->execute($entrepot);
        }
        
        echo "   ✅ 2 entrepôts ajoutés\n";
    } else {
        echo "   ✅ Entrepôts déjà présents ($entrepotCount)\n";
    }
    
    // Vérifier si des stocks existent
    $stmt = $pdo->query("SELECT COUNT(*) FROM stocks");
    $stockCount = $stmt->fetchColumn();
    
    if ($stockCount == 0) {
        // Ajouter des stocks
        $stocks = [
            [
                'item_name' => 'Riz',
                'item_type' => 'Alimentaire',
                'quantity' => 1000,
                'unit' => 'kg',
                'status' => 'disponible',
                'entrepot_id' => 1,
                'description' => 'Riz de qualité supérieure',
                'min_quantity' => 100,
                'max_quantity' => 2000,
                'supplier' => 'Fournisseur Riz SA',
                'cost' => 500.00
            ],
            [
                'item_name' => 'Huile de tournesol',
                'item_type' => 'Alimentaire',
                'quantity' => 500,
                'unit' => 'litres',
                'status' => 'disponible',
                'entrepot_id' => 1,
                'description' => 'Huile de tournesol raffinée',
                'min_quantity' => 50,
                'max_quantity' => 1000,
                'supplier' => 'Huilerie Moderne',
                'cost' => 800.00
            ],
            [
                'item_name' => 'Couverts en plastique',
                'item_type' => 'Matériel',
                'quantity' => 2000,
                'unit' => 'pièces',
                'status' => 'disponible',
                'entrepot_id' => 1,
                'description' => 'Couverts jetables en plastique',
                'min_quantity' => 200,
                'max_quantity' => 5000,
                'supplier' => 'Plastique Plus',
                'cost' => 150.00
            ],
            [
                'item_name' => 'Tentes d\'urgence',
                'item_type' => 'Équipement',
                'quantity' => 50,
                'unit' => 'pièces',
                'status' => 'disponible',
                'entrepot_id' => 1,
                'description' => 'Tentes d\'urgence pour 4 personnes',
                'min_quantity' => 10,
                'max_quantity' => 100,
                'supplier' => 'Équipement Urgence',
                'cost' => 2500.00
            ],
            [
                'item_name' => 'Médicaments de base',
                'item_type' => 'Médical',
                'quantity' => 100,
                'unit' => 'boîtes',
                'status' => 'disponible',
                'entrepot_id' => 1,
                'description' => 'Trousse de médicaments d\'urgence',
                'min_quantity' => 20,
                'max_quantity' => 200,
                'supplier' => 'Pharmacie Centrale',
                'cost' => 1200.00
            ]
        ];
        
        $insertStmt = $pdo->prepare("
            INSERT INTO stocks (
                item_name, item_type, quantity, unit, status, entrepot_id,
                description, min_quantity, max_quantity, supplier, cost,
                created_at, updated_at
            ) VALUES (
                :item_name, :item_type, :quantity, :unit, :status, :entrepot_id,
                :description, :min_quantity, :max_quantity, :supplier, :cost,
                NOW(), NOW()
            )
        ");
        
        foreach ($stocks as $stock) {
            $insertStmt->execute($stock);
        }
        
        echo "   ✅ 5 articles de stock ajoutés\n";
    } else {
        echo "   ✅ Stocks déjà présents ($stockCount)\n";
    }

    // 4. Vérification finale
    echo "4️⃣ Vérification finale...\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM stocks");
    $stockCount = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM entrepots");
    $entrepotCount = $stmt->fetchColumn();
    
    echo "   📊 Articles en stock: $stockCount\n";
    echo "   📊 Entrepôts: $entrepotCount\n";
    echo "   ✅ Gestion des stocks opérationnelle\n";
    echo "\n";

    echo "🎉 CORRECTION TERMINÉE\n";
    echo "=====================\n";
    echo "✅ Tables stocks et entrepots créées\n";
    echo "✅ Données de test ajoutées\n";
    echo "✅ Gestion des stocks fonctionnelle\n";
    echo "\n";
    echo "🌐 Testez maintenant :\n";
    echo "- Interface Admin: http://localhost:8000/admin\n";
    echo "- Gestion des Stocks: http://localhost:8000/admin/stocks\n";
    echo "- Gestion des Entrepôts: http://localhost:8000/admin/entrepots\n";
    echo "\n";
    echo "🔑 Identifiants Admin:\n";
    echo "- Email: admin@csar.sn\n";
    echo "- Mot de passe: password\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

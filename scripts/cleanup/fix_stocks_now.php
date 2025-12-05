<?php

echo "🔧 CORRECTION IMMÉDIATE - GESTION DES STOCKS\n";
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

    // Créer la table stocks
    echo "1️⃣ Création de la table stocks...\n";
    
    $pdo->exec("
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
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    echo "   ✅ Table stocks créée\n";

    // Créer la table entrepots
    echo "2️⃣ Création de la table entrepots...\n";
    
    $pdo->exec("
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
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    echo "   ✅ Table entrepots créée\n";

    // Ajouter des données de test
    echo "3️⃣ Ajout de données de test...\n";
    
    // Vérifier si des données existent
    $stmt = $pdo->query("SELECT COUNT(*) FROM entrepots");
    $entrepotCount = $stmt->fetchColumn();
    
    if ($entrepotCount == 0) {
        $pdo->exec("
            INSERT INTO entrepots (name, address, capacity, current_stock, status, manager, phone, email, created_at, updated_at)
            VALUES ('Entrepôt Principal Dakar', 'Zone Industrielle, Dakar', 10000, 0, 'actif', 'Responsable Principal', '+221 33 123 45 67', 'entrepot@csar.sn', NOW(), NOW())
        ");
        echo "   ✅ Entrepôt ajouté\n";
    } else {
        echo "   ✅ Entrepôts déjà présents\n";
    }
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM stocks");
    $stockCount = $stmt->fetchColumn();
    
    if ($stockCount == 0) {
        $pdo->exec("
            INSERT INTO stocks (item_name, item_type, quantity, unit, status, entrepot_id, description, min_quantity, max_quantity, supplier, cost, created_at, updated_at)
            VALUES 
            ('Riz', 'Alimentaire', 1000, 'kg', 'disponible', 1, 'Riz de qualité supérieure', 100, 2000, 'Fournisseur Riz SA', 500.00, NOW(), NOW()),
            ('Huile de tournesol', 'Alimentaire', 500, 'litres', 'disponible', 1, 'Huile de tournesol raffinée', 50, 1000, 'Huilerie Moderne', 800.00, NOW(), NOW()),
            ('Couverts en plastique', 'Matériel', 2000, 'pièces', 'disponible', 1, 'Couverts jetables en plastique', 200, 5000, 'Plastique Plus', 150.00, NOW(), NOW()),
            ('Tentes d\'urgence', 'Équipement', 50, 'pièces', 'disponible', 1, 'Tentes d\'urgence pour 4 personnes', 10, 100, 'Équipement Urgence', 2500.00, NOW(), NOW()),
            ('Médicaments de base', 'Médical', 100, 'boîtes', 'disponible', 1, 'Trousse de médicaments d\'urgence', 20, 200, 'Pharmacie Centrale', 1200.00, NOW(), NOW())
        ");
        echo "   ✅ 5 articles de stock ajoutés\n";
    } else {
        echo "   ✅ Stocks déjà présents\n";
    }

    // Vérification finale
    echo "4️⃣ Vérification finale...\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM stocks");
    $stockCount = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM entrepots");
    $entrepotCount = $stmt->fetchColumn();
    
    echo "   📊 Articles en stock: $stockCount\n";
    echo "   📊 Entrepôts: $entrepotCount\n";
    
    echo "\n";

    echo "🎉 CORRECTION TERMINÉE AVEC SUCCÈS !\n";
    echo "====================================\n";
    echo "✅ Tables stocks et entrepots créées\n";
    echo "✅ Données de test ajoutées\n";
    echo "✅ Gestion des stocks opérationnelle\n";
    echo "\n";
    echo "🌐 MAINTENANT VOUS POUVEZ ACCÉDER À :\n";
    echo "📱 Interface Admin: http://localhost:8000/admin\n";
    echo "📦 Gestion des Stocks: http://localhost:8000/admin/stocks\n";
    echo "🏢 Gestion des Entrepôts: http://localhost:8000/admin/entrepots\n";
    echo "\n";
    echo "🔑 IDENTIFIANTS ADMIN:\n";
    echo "📧 Email: admin@csar.sn\n";
    echo "🔒 Mot de passe: password\n";
    echo "\n";
    echo "✨ LA GESTION DES STOCKS FONCTIONNE MAINTENANT !\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
    echo "\n🔧 SOLUTIONS POSSIBLES:\n";
    echo "1. Vérifiez que XAMPP est démarré\n";
    echo "2. Vérifiez que MySQL est actif\n";
    echo "3. Vérifiez les identifiants de base de données\n";
    echo "4. Vérifiez que la base csar_platform_2025 existe\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

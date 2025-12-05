<?php

/**
 * Script pour vérifier et corriger les modèles manquants
 */

echo "🔍 Vérification des modèles manquants\n";
echo "===================================\n\n";

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

    // 1. Vérifier les tables nécessaires pour HomeController
    echo "1️⃣ Vérification des tables nécessaires...\n";
    
    $requiredTables = [
        'home_backgrounds' => 'Images de fond pour l\'accueil',
        'public_contents' => 'Contenu public dynamique',
        'news' => 'Actualités',
        'speeches' => 'Discours',
        'warehouses' => 'Entrepôts',
        'technical_partners' => 'Partenaires techniques',
        'gallery_images' => 'Images de galerie',
        'sim_reports' => 'Rapports SIM'
    ];
    
    $missingTables = [];
    
    foreach ($requiredTables as $table => $description) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "   ✅ Table $table: Présente ($description)\n";
        } else {
            echo "   ❌ Table $table: Manquante ($description)\n";
            $missingTables[] = $table;
        }
    }
    echo "\n";
    
    // 2. Créer les tables manquantes
    if (!empty($missingTables)) {
        echo "2️⃣ Création des tables manquantes...\n";
        
        foreach ($missingTables as $table) {
            echo "   🔧 Création de la table $table...\n";
            
            switch ($table) {
                case 'home_backgrounds':
                    $pdo->exec("
                        CREATE TABLE home_backgrounds (
                            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            title VARCHAR(255) NOT NULL,
                            description TEXT,
                            image_url VARCHAR(500) NOT NULL,
                            is_active TINYINT(1) DEFAULT 1,
                            display_order INT DEFAULT 0,
                            created_at TIMESTAMP NULL,
                            updated_at TIMESTAMP NULL
                        )
                    ");
                    break;
                    
                case 'public_contents':
                    $pdo->exec("
                        CREATE TABLE public_contents (
                            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            section VARCHAR(100) NOT NULL,
                            key_name VARCHAR(100) NOT NULL,
                            value TEXT,
                            created_at TIMESTAMP NULL,
                            updated_at TIMESTAMP NULL,
                            UNIQUE KEY unique_section_key (section, key_name)
                        )
                    ");
                    break;
                    
                case 'news':
                    $pdo->exec("
                        CREATE TABLE news (
                            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            title VARCHAR(255) NOT NULL,
                            excerpt TEXT,
                            content LONGTEXT NOT NULL,
                            featured_image_url VARCHAR(500),
                            category VARCHAR(100) DEFAULT 'general',
                            status ENUM('draft', 'published') DEFAULT 'draft',
                            is_published TINYINT(1) DEFAULT 0,
                            published_at TIMESTAMP NULL,
                            created_at TIMESTAMP NULL,
                            updated_at TIMESTAMP NULL
                        )
                    ");
                    break;
                    
                case 'speeches':
                    $pdo->exec("
                        CREATE TABLE speeches (
                            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            title VARCHAR(255) NOT NULL,
                            speaker VARCHAR(255) NOT NULL,
                            content LONGTEXT NOT NULL,
                            date DATE NOT NULL,
                            location VARCHAR(255),
                            created_at TIMESTAMP NULL,
                            updated_at TIMESTAMP NULL
                        )
                    ");
                    break;
                    
                case 'warehouses':
                    $pdo->exec("
                        CREATE TABLE warehouses (
                            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(255) NOT NULL,
                            address TEXT,
                            capacity INT,
                            latitude DECIMAL(10, 8),
                            longitude DECIMAL(11, 8),
                            is_active TINYINT(1) DEFAULT 1,
                            created_at TIMESTAMP NULL,
                            updated_at TIMESTAMP NULL
                        )
                    ");
                    break;
                    
                case 'technical_partners':
                    $pdo->exec("
                        CREATE TABLE technical_partners (
                            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(255) NOT NULL,
                            logo_url VARCHAR(500),
                            website VARCHAR(255),
                            status ENUM('active', 'inactive') DEFAULT 'active',
                            position INT,
                            created_at TIMESTAMP NULL,
                            updated_at TIMESTAMP NULL
                        )
                    ");
                    break;
                    
                case 'gallery_images':
                    $pdo->exec("
                        CREATE TABLE gallery_images (
                            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            title VARCHAR(255),
                            description TEXT,
                            image_url VARCHAR(500) NOT NULL,
                            status ENUM('active', 'inactive') DEFAULT 'active',
                            is_featured TINYINT(1) DEFAULT 0,
                            created_at TIMESTAMP NULL,
                            updated_at TIMESTAMP NULL
                        )
                    ");
                    break;
                    
                case 'sim_reports':
                    $pdo->exec("
                        CREATE TABLE sim_reports (
                            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            title VARCHAR(255) NOT NULL,
                            summary TEXT,
                            description LONGTEXT,
                            file_url VARCHAR(500),
                            is_public TINYINT(1) DEFAULT 0,
                            published_at TIMESTAMP NULL,
                            created_at TIMESTAMP NULL,
                            updated_at TIMESTAMP NULL
                        )
                    ");
                    break;
            }
            
            echo "   ✅ Table $table créée\n";
        }
        echo "\n";
    }
    
    // 3. Insérer des données de base
    echo "3️⃣ Insertion de données de base...\n";
    
    // Données pour public_contents
    $publicContents = [
        ['about', 'agents_count', '150'],
        ['about', 'warehouses_count', '25'],
        ['about', 'capacity_count', '50000'],
        ['about', 'experience_count', '15']
    ];
    
    foreach ($publicContents as $content) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO public_contents (section, key_name, value, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute($content);
    }
    echo "   ✅ Données public_contents insérées\n";
    
    // Données pour home_backgrounds
    $stmt = $pdo->prepare("INSERT IGNORE INTO home_backgrounds (title, description, image_url, is_active, display_order, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute(['Image par défaut', 'Image de fond par défaut pour l\'accueil', 'img/1.jpg', 1, 1]);
    echo "   ✅ Image de fond par défaut insérée\n";
    
    // 4. Vérification finale
    echo "\n4️⃣ Vérification finale...\n";
    
    foreach ($requiredTables as $table => $description) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "   📊 Table $table: $count enregistrements\n";
    }
    
    echo "\n🎉 MODÈLES CORRIGÉS !\n";
    echo "====================\n";
    echo "Toutes les tables nécessaires sont maintenant présentes.\n";
    echo "L'interface publique devrait maintenant fonctionner correctement.\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

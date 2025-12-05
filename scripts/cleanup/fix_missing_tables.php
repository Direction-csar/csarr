<?php

// Script pour créer les tables manquantes dans la base de données CSAR Platform

$host = '127.0.0.1';
$dbname = 'csar_platform';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données réussie\n";
    
    // Créer la table public_contents
    $sql = "CREATE TABLE IF NOT EXISTS public_contents (
        id INT AUTO_INCREMENT PRIMARY KEY,
        section VARCHAR(255) NOT NULL,
        title VARCHAR(255),
        content TEXT,
        is_active BOOLEAN DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "✅ Table 'public_contents' créée avec succès\n";
    
    // Insérer des données par défaut pour la section "about"
    $checkSql = "SELECT COUNT(*) FROM public_contents WHERE section = 'about'";
    $count = $pdo->query($checkSql)->fetchColumn();
    
    if ($count == 0) {
        $insertSql = "INSERT INTO public_contents (section, title, content, is_active) VALUES 
                     ('about', 'À propos du CSAR', 'Le Centre de Services d\'Appui au Réseau (CSAR) est une institution dédiée à l\'amélioration des services publics et au développement des communautés.', 1)";
        $pdo->exec($insertSql);
        echo "✅ Données par défaut insérées dans public_contents\n";
    }
    
    // Vérifier et créer d'autres tables si nécessaire
    $tables = [
        'home_backgrounds' => "CREATE TABLE IF NOT EXISTS home_backgrounds (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255),
            image_path VARCHAR(500),
            is_active BOOLEAN DEFAULT 1,
            display_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        'news' => "CREATE TABLE IF NOT EXISTS news (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT,
            image_path VARCHAR(500),
            is_published BOOLEAN DEFAULT 0,
            published_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        'newsletters' => "CREATE TABLE IF NOT EXISTS newsletters (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            is_active BOOLEAN DEFAULT 1,
            subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        'contact_messages' => "CREATE TABLE IF NOT EXISTS contact_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            subject VARCHAR(255),
            message TEXT NOT NULL,
            is_read BOOLEAN DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    ];
    
    foreach ($tables as $tableName => $createSql) {
        try {
            $pdo->exec($createSql);
            echo "✅ Table '$tableName' vérifiée/créée\n";
        } catch (PDOException $e) {
            echo "⚠️  Table '$tableName' : " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n🎉 Toutes les tables nécessaires sont maintenant disponibles !\n";
    echo "Votre plateforme CSAR devrait maintenant fonctionner correctement.\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion à la base de données : " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré et que la base 'csar_platform' existe.\n";
}

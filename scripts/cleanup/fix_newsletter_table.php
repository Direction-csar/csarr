<?php
/**
 * Script pour corriger la table newsletters
 */

echo "🔧 CORRECTION DE LA TABLE NEWSLETTERS\n";
echo "====================================\n\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=csar_platform', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données réussie\n\n";
    
    // 1. Vérifier si la table newsletters existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'newsletters'");
    if ($stmt->rowCount() == 0) {
        echo "❌ Table newsletters manquante - Création en cours...\n";
        
        // Créer la table newsletters
        $sql = "CREATE TABLE newsletters (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) UNIQUE NOT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL
        )";
        $pdo->exec($sql);
        echo "✅ Table newsletters créée\n";
    } else {
        echo "✅ Table newsletters existe\n";
        
        // Vérifier si la colonne deleted_at existe
        $stmt = $pdo->query("SHOW COLUMNS FROM newsletters LIKE 'deleted_at'");
        if ($stmt->rowCount() == 0) {
            echo "❌ Colonne deleted_at manquante - Ajout en cours...\n";
            $pdo->exec("ALTER TABLE newsletters ADD COLUMN deleted_at TIMESTAMP NULL");
            echo "✅ Colonne deleted_at ajoutée\n";
        } else {
            echo "✅ Colonne deleted_at existe\n";
        }
    }
    
    // 2. Vérifier la table newsletter_subscribers
    $stmt = $pdo->query("SHOW TABLES LIKE 'newsletter_subscribers'");
    if ($stmt->rowCount() == 0) {
        echo "❌ Table newsletter_subscribers manquante - Création en cours...\n";
        
        $sql = "CREATE TABLE newsletter_subscribers (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) UNIQUE NOT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL
        )";
        $pdo->exec($sql);
        echo "✅ Table newsletter_subscribers créée\n";
    } else {
        echo "✅ Table newsletter_subscribers existe\n";
        
        // Vérifier si la colonne deleted_at existe
        $stmt = $pdo->query("SHOW COLUMNS FROM newsletter_subscribers LIKE 'deleted_at'");
        if ($stmt->rowCount() == 0) {
            echo "❌ Colonne deleted_at manquante dans newsletter_subscribers - Ajout en cours...\n";
            $pdo->exec("ALTER TABLE newsletter_subscribers ADD COLUMN deleted_at TIMESTAMP NULL");
            echo "✅ Colonne deleted_at ajoutée à newsletter_subscribers\n";
        } else {
            echo "✅ Colonne deleted_at existe dans newsletter_subscribers\n";
        }
    }
    
    // 3. Vérifier la structure finale
    echo "\n📋 Structure des tables:\n";
    echo "------------------------\n";
    
    $tables = ['newsletters', 'newsletter_subscribers'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Table $table:\n";
            $stmt = $pdo->query("DESCRIBE $table");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($columns as $column) {
                echo "   - {$column['Field']} ({$column['Type']})\n";
            }
        }
    }
    
    echo "\n🎉 CORRECTION TERMINÉE AVEC SUCCÈS!\n";
    echo "Vous pouvez maintenant accéder à la page newsletter sans erreur.\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n";
?>

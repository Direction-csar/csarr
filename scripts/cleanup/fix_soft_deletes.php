<?php
/**
 * Script pour corriger les problèmes de SoftDeletes
 */

echo "🔧 CORRECTION DES PROBLÈMES SOFT DELETES\n";
echo "========================================\n\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=csar_platform', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données réussie\n\n";
    
    // Tables qui utilisent SoftDeletes
    $tables = [
        'newsletters',
        'messages', 
        'notifications',
        'home_backgrounds'
    ];
    
    foreach ($tables as $table) {
        echo "🔍 Vérification de la table: $table\n";
        
        // Vérifier si la table existe
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() == 0) {
            echo "   ❌ Table $table n'existe pas - Création...\n";
            
            // Créer la table selon son type
            switch ($table) {
                case 'newsletters':
                    $sql = "CREATE TABLE newsletters (
                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        title VARCHAR(255),
                        subject VARCHAR(255),
                        content TEXT,
                        template VARCHAR(100) DEFAULT 'default',
                        status VARCHAR(50) DEFAULT 'pending',
                        scheduled_at TIMESTAMP NULL,
                        sent_at TIMESTAMP NULL,
                        sent_by BIGINT UNSIGNED,
                        recipients_count INT DEFAULT 0,
                        delivered_count INT DEFAULT 0,
                        opened_count INT DEFAULT 0,
                        clicked_count INT DEFAULT 0,
                        bounced_count INT DEFAULT 0,
                        unsubscribed_count INT DEFAULT 0,
                        open_rate DECIMAL(5,2) DEFAULT 0,
                        click_rate DECIMAL(5,2) DEFAULT 0,
                        metadata JSON,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        deleted_at TIMESTAMP NULL
                    )";
                    break;
                    
                case 'messages':
                    $sql = "CREATE TABLE messages (
                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        sujet VARCHAR(255),
                        contenu TEXT,
                        expediteur VARCHAR(255),
                        email_expediteur VARCHAR(255),
                        telephone_expediteur VARCHAR(20),
                        lu BOOLEAN DEFAULT FALSE,
                        lu_at TIMESTAMP NULL,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        deleted_at TIMESTAMP NULL
                    )";
                    break;
                    
                case 'notifications':
                    $sql = "CREATE TABLE notifications (
                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        title VARCHAR(255),
                        message TEXT,
                        type VARCHAR(50) DEFAULT 'info',
                        is_read BOOLEAN DEFAULT FALSE,
                        data JSON,
                        user_id BIGINT UNSIGNED,
                        read_at TIMESTAMP NULL,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        deleted_at TIMESTAMP NULL
                    )";
                    break;
                    
                case 'home_backgrounds':
                    $sql = "CREATE TABLE home_backgrounds (
                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        title VARCHAR(255),
                        image_path VARCHAR(500),
                        is_active BOOLEAN DEFAULT TRUE,
                        order_index INT DEFAULT 0,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        deleted_at TIMESTAMP NULL
                    )";
                    break;
            }
            
            $pdo->exec($sql);
            echo "   ✅ Table $table créée avec deleted_at\n";
        } else {
            // Vérifier si la colonne deleted_at existe
            $stmt = $pdo->query("SHOW COLUMNS FROM $table LIKE 'deleted_at'");
            if ($stmt->rowCount() == 0) {
                echo "   ❌ Colonne deleted_at manquante - Ajout...\n";
                $pdo->exec("ALTER TABLE $table ADD COLUMN deleted_at TIMESTAMP NULL");
                echo "   ✅ Colonne deleted_at ajoutée à $table\n";
            } else {
                echo "   ✅ Colonne deleted_at existe dans $table\n";
            }
        }
        
        echo "\n";
    }
    
    echo "🎉 CORRECTION TERMINÉE AVEC SUCCÈS!\n";
    echo "Toutes les tables ont maintenant la colonne deleted_at.\n";
    echo "Vous pouvez maintenant réactiver SoftDeletes dans les modèles.\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n";
?>

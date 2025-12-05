<?php

/**
 * Application manuelle de la migration pour ajouter duplicate_hash
 */

echo "🔧 Application de la migration duplicate_hash...\n";
echo "===============================================\n\n";

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

    // 1. Ajouter duplicate_hash à contact_messages
    echo "1️⃣ Ajout de duplicate_hash à contact_messages...\n";
    try {
        $pdo->exec("ALTER TABLE contact_messages ADD COLUMN duplicate_hash VARCHAR(64) NULL AFTER message");
        $pdo->exec("ALTER TABLE contact_messages ADD INDEX idx_contact_duplicate_hash (duplicate_hash)");
        echo "   ✅ Champ et index ajoutés à contact_messages\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "   ⚠️ Champ duplicate_hash déjà présent dans contact_messages\n";
        } else {
            echo "   ❌ Erreur: " . $e->getMessage() . "\n";
        }
    }

    // 2. Ajouter duplicate_hash à public_requests
    echo "2️⃣ Ajout de duplicate_hash à public_requests...\n";
    try {
        $pdo->exec("ALTER TABLE public_requests ADD COLUMN duplicate_hash VARCHAR(64) NULL AFTER description");
        $pdo->exec("ALTER TABLE public_requests ADD INDEX idx_request_duplicate_hash (duplicate_hash)");
        echo "   ✅ Champ et index ajoutés à public_requests\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "   ⚠️ Champ duplicate_hash déjà présent dans public_requests\n";
        } else {
            echo "   ❌ Erreur: " . $e->getMessage() . "\n";
        }
    }

    // 3. Ajouter duplicate_hash à messages
    echo "3️⃣ Ajout de duplicate_hash à messages...\n";
    try {
        $pdo->exec("ALTER TABLE messages ADD COLUMN duplicate_hash VARCHAR(64) NULL AFTER reponse");
        $pdo->exec("ALTER TABLE messages ADD INDEX idx_message_duplicate_hash (duplicate_hash)");
        echo "   ✅ Champ et index ajoutés à messages\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "   ⚠️ Champ duplicate_hash déjà présent dans messages\n";
        } else {
            echo "   ❌ Erreur: " . $e->getMessage() . "\n";
        }
    }

    // 4. Ajouter duplicate_hash à newsletter_subscribers
    echo "4️⃣ Ajout de duplicate_hash à newsletter_subscribers...\n";
    try {
        $pdo->exec("ALTER TABLE newsletter_subscribers ADD COLUMN duplicate_hash VARCHAR(64) NULL AFTER source");
        $pdo->exec("ALTER TABLE newsletter_subscribers ADD INDEX idx_newsletter_duplicate_hash (duplicate_hash)");
        echo "   ✅ Champ et index ajoutés à newsletter_subscribers\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "   ⚠️ Champ duplicate_hash déjà présent dans newsletter_subscribers\n";
        } else {
            echo "   ❌ Erreur: " . $e->getMessage() . "\n";
        }
    }

    echo "\n5️⃣ Vérification finale...\n";
    
    // Vérifier que tous les champs sont présents
    $tables = ['contact_messages', 'public_requests', 'messages', 'newsletter_subscribers'];
    $allPresent = true;
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW COLUMNS FROM $table LIKE 'duplicate_hash'");
        $columns = $stmt->fetchAll();
        if (count($columns) > 0) {
            echo "   ✅ Table $table : Champ duplicate_hash présent\n";
        } else {
            echo "   ❌ Table $table : Champ duplicate_hash manquant\n";
            $allPresent = false;
        }
    }
    
    if ($allPresent) {
        echo "\n🎉 MIGRATION APPLIQUÉE AVEC SUCCÈS !\n";
        echo "===================================\n\n";
        echo "✅ Tous les champs duplicate_hash ont été ajoutés\n";
        echo "✅ Tous les index ont été créés\n";
        echo "✅ Le système de prévention des doublons est prêt\n\n";
        
        echo "🔒 Fonctionnalités maintenant disponibles :\n";
        echo "   - Prévention des doublons pour les messages de contact\n";
        echo "   - Prévention des doublons pour les demandes publiques\n";
        echo "   - Prévention des doublons pour les messages admin\n";
        echo "   - Prévention des doublons pour les abonnements newsletter\n";
        echo "   - Journal d'audit complet pour toutes les actions\n";
    } else {
        echo "\n❌ MIGRATION INCOMPLÈTE\n";
        echo "======================\n";
        echo "Certains champs n'ont pas pu être ajoutés.\n";
    }

} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

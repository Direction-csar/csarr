<?php
/**
 * Script manuel pour ajouter les champs SMS à la table demandes
 */

echo "🔧 Ajout manuel des champs SMS à la table demandes\n";
echo "=================================================\n\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=csar_platform', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données réussie\n\n";
    
    // Vérifier si la table demandes existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'demandes'");
    if ($stmt->rowCount() === 0) {
        echo "❌ La table 'demandes' n'existe pas!\n";
        exit(1);
    }
    
    echo "✅ La table 'demandes' existe\n\n";
    
    // Ajouter les champs SMS un par un
    $fields = [
        'sms_sent' => 'BOOLEAN DEFAULT FALSE',
        'sms_message_id' => 'VARCHAR(255) NULL',
        'sms_sent_at' => 'TIMESTAMP NULL',
        'sms_error' => 'TEXT NULL',
        'sms_retry_count' => 'INTEGER DEFAULT 0'
    ];
    
    foreach ($fields as $field => $definition) {
        try {
            // Vérifier si le champ existe déjà
            $stmt = $pdo->query("SHOW COLUMNS FROM demandes LIKE '{$field}'");
            if ($stmt->rowCount() > 0) {
                echo "   ⚠️  Le champ {$field} existe déjà\n";
                continue;
            }
            
            // Ajouter le champ
            $sql = "ALTER TABLE demandes ADD COLUMN {$field} {$definition}";
            $pdo->exec($sql);
            echo "   ✅ Champ {$field} ajouté avec succès\n";
            
        } catch (PDOException $e) {
            echo "   ❌ Erreur lors de l'ajout du champ {$field}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n📋 Vérification finale de la structure:\n";
    echo "------------------------------------\n";
    
    $stmt = $pdo->query("DESCRIBE demandes");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $smsFields = ['sms_sent', 'sms_message_id', 'sms_sent_at', 'sms_error', 'sms_retry_count'];
    $foundFields = [];
    
    foreach ($columns as $column) {
        if (in_array($column['Field'], $smsFields)) {
            $foundFields[] = $column['Field'];
            echo "   ✅ {$column['Field']} ({$column['Type']})\n";
        }
    }
    
    echo "\n📊 Résumé:\n";
    echo "----------\n";
    echo "   Champs SMS présents: " . count($foundFields) . "/" . count($smsFields) . "\n";
    
    if (count($foundFields) === count($smsFields)) {
        echo "   ✅ Tous les champs SMS ont été ajoutés avec succès!\n";
    } else {
        echo "   ❌ Certains champs SMS sont encore manquants\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion à la base de données: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n";

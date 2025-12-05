<?php
/**
 * Test simple de la base de données pour les champs SMS
 */

echo "🔍 Test de la base de données - Champs SMS\n";
echo "==========================================\n\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=csar_platform', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données réussie\n\n";
    
    // Vérifier la structure de la table demandes
    $stmt = $pdo->query("DESCRIBE demandes");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📋 Structure de la table 'demandes':\n";
    echo "------------------------------------\n";
    
    $smsFields = ['sms_sent', 'sms_message_id', 'sms_sent_at', 'sms_error', 'sms_retry_count'];
    $foundFields = [];
    
    foreach ($columns as $column) {
        $fieldName = $column['Field'];
        $fieldType = $column['Type'];
        $isNull = $column['Null'] === 'YES' ? 'NULL' : 'NOT NULL';
        $default = $column['Default'] ? "DEFAULT '{$column['Default']}'" : '';
        
        echo "   {$fieldName} ({$fieldType}) {$isNull} {$default}\n";
        
        if (in_array($fieldName, $smsFields)) {
            $foundFields[] = $fieldName;
        }
    }
    
    echo "\n📱 Champs SMS trouvés:\n";
    echo "----------------------\n";
    
    foreach ($smsFields as $field) {
        if (in_array($field, $foundFields)) {
            echo "   ✅ {$field} - PRÉSENT\n";
        } else {
            echo "   ❌ {$field} - MANQUANT\n";
        }
    }
    
    echo "\n📊 Résumé:\n";
    echo "----------\n";
    echo "   Champs SMS présents: " . count($foundFields) . "/" . count($smsFields) . "\n";
    
    if (count($foundFields) === count($smsFields)) {
        echo "   ✅ Tous les champs SMS sont présents!\n";
    } else {
        echo "   ❌ Certains champs SMS sont manquants\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion à la base de données: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n";

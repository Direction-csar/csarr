<?php

/**
 * Script pour corriger la table messages
 */

echo "🔧 Correction de la table messages\n";
echo "=================================\n\n";

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

    // 1. Vérifier la structure actuelle
    echo "1️⃣ Structure actuelle de messages...\n";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM messages");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   📊 Colonnes actuelles:\n";
    foreach ($columns as $column) {
        echo "      - {$column['Field']} ({$column['Type']})\n";
    }
    echo "\n";

    // 2. Ajouter les colonnes manquantes
    echo "2️⃣ Ajout des colonnes manquantes...\n";
    
    $requiredColumns = [
        'subject' => 'VARCHAR(255) NULL',
        'content' => 'TEXT NULL',
        'sender_name' => 'VARCHAR(255) NULL',
        'sender_email' => 'VARCHAR(255) NULL',
        'sender_phone' => 'VARCHAR(20) NULL',
        'is_read' => 'TINYINT(1) DEFAULT 0',
        'response' => 'TEXT NULL',
        'responded_at' => 'TIMESTAMP NULL',
        'responded_by' => 'BIGINT UNSIGNED NULL',
        'priority' => 'ENUM("low", "medium", "high") DEFAULT "medium"',
        'category' => 'VARCHAR(100) NULL',
        'ip_address' => 'VARCHAR(45) NULL',
        'user_agent' => 'TEXT NULL'
    ];
    
    foreach ($requiredColumns as $column => $definition) {
        $exists = false;
        foreach ($columns as $col) {
            if ($col['Field'] === $column) {
                $exists = true;
                break;
            }
        }
        
        if (!$exists) {
            try {
                $pdo->exec("ALTER TABLE messages ADD COLUMN $column $definition");
                echo "   ✅ Colonne $column ajoutée\n";
            } catch (PDOException $e) {
                echo "   ❌ Erreur pour $column: " . $e->getMessage() . "\n";
            }
        } else {
            echo "   ⚠️ Colonne $column déjà présente\n";
        }
    }
    echo "\n";

    // 3. Renommer les colonnes existantes pour correspondre au modèle
    echo "3️⃣ Renommage des colonnes pour correspondre au modèle...\n";
    
    $renames = [
        'sujet' => 'subject',
        'contenu' => 'content',
        'expediteur' => 'sender_name',
        'email_expediteur' => 'sender_email',
        'telephone_expediteur' => 'sender_phone',
        'lu' => 'is_read',
        'reponse' => 'response'
    ];
    
    foreach ($renames as $oldName => $newName) {
        $hasOld = false;
        $hasNew = false;
        foreach ($columns as $col) {
            if ($col['Field'] === $oldName) $hasOld = true;
            if ($col['Field'] === $newName) $hasNew = true;
        }
        
        if ($hasOld && !$hasNew) {
            try {
                $pdo->exec("ALTER TABLE messages CHANGE COLUMN $oldName $newName VARCHAR(255)");
                echo "   ✅ Colonne '$oldName' renommée en '$newName'\n";
            } catch (PDOException $e) {
                echo "   ❌ Erreur lors du renommage de '$oldName': " . $e->getMessage() . "\n";
            }
        } else if ($hasNew) {
            echo "   ⚠️ Colonne '$newName' déjà présente\n";
        } else {
            echo "   ⚠️ Colonne '$oldName' non trouvée\n";
        }
    }
    echo "\n";

    // 4. Insérer des données de test
    echo "4️⃣ Insertion de données de test...\n";
    
    $testMessages = [
        [
            'subject' => 'Demande d\'information sur les services',
            'content' => 'Bonjour, j\'aimerais avoir des informations sur les services proposés par le CSAR.',
            'sender_name' => 'Fatou Sall',
            'sender_email' => 'fatou.sall@example.com',
            'sender_phone' => '+221123456789',
            'is_read' => 0,
            'priority' => 'medium',
            'category' => 'information',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],
        [
            'subject' => 'Urgent - Demande d\'aide',
            'content' => 'J\'ai besoin d\'aide urgente pour ma situation de réfugié.',
            'sender_name' => 'Moussa Diallo',
            'sender_email' => 'moussa.diallo@example.com',
            'sender_phone' => '+221987654321',
            'is_read' => 1,
            'response' => 'Votre demande a été prise en compte. Nous vous contacterons sous 24h.',
            'responded_at' => date('Y-m-d H:i:s'),
            'priority' => 'high',
            'category' => 'aide',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],
        [
            'subject' => 'Remerciements',
            'content' => 'Je tiens à remercier toute l\'équipe du CSAR pour leur aide précieuse.',
            'sender_name' => 'Aminata Ndiaye',
            'sender_email' => 'aminata.ndiaye@example.com',
            'sender_phone' => '+221555666777',
            'is_read' => 1,
            'priority' => 'low',
            'category' => 'remerciement',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]
    ];
    
    foreach ($testMessages as $message) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO messages (
                subject, content, sender_name, sender_email, sender_phone, 
                is_read, response, responded_at, priority, category, 
                created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $message['subject'],
            $message['content'],
            $message['sender_name'],
            $message['sender_email'],
            $message['sender_phone'],
            $message['is_read'],
            $message['response'] ?? null,
            $message['responded_at'] ?? null,
            $message['priority'],
            $message['category'],
            $message['created_at'],
            $message['updated_at']
        ]);
    }
    echo "   ✅ Données de test insérées\n\n";

    // 5. Vérification finale
    echo "5️⃣ Vérification finale...\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM messages");
    $count = $stmt->fetchColumn();
    echo "   📊 Nombre total de messages: $count\n";
    
    $stmt = $pdo->query("SELECT subject, sender_name, is_read, priority FROM messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "   📋 Messages dans la base:\n";
    foreach ($messages as $message) {
        $readStatus = $message['is_read'] ? 'Lu' : 'Non lu';
        echo "      - {$message['subject']} | {$message['sender_name']} | $readStatus | {$message['priority']}\n";
    }
    
    echo "\n🎉 TABLE MESSAGES CORRIGÉE !\n";
    echo "===========================\n";
    echo "La table messages est maintenant compatible avec le modèle Laravel.\n";
    echo "Les messages devraient maintenant se charger correctement.\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

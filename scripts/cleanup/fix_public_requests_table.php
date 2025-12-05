<?php

/**
 * Script pour corriger la table public_requests
 */

echo "🔧 Correction de la table public_requests\n";
echo "========================================\n\n";

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
    echo "1️⃣ Structure actuelle de public_requests...\n";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM public_requests");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   📊 Colonnes actuelles:\n";
    foreach ($columns as $column) {
        echo "      - {$column['Field']} ({$column['Type']})\n";
    }
    echo "\n";

    // 2. Ajouter les colonnes manquantes
    echo "2️⃣ Ajout des colonnes manquantes...\n";
    
    $requiredColumns = [
        'tracking_code' => 'VARCHAR(50) NULL',
        'full_name' => 'VARCHAR(255) NULL',
        'address' => 'TEXT NULL',
        'latitude' => 'DECIMAL(10, 8) NULL',
        'longitude' => 'DECIMAL(11, 8) NULL',
        'region' => 'VARCHAR(255) NULL',
        'admin_comment' => 'TEXT NULL',
        'assigned_to' => 'BIGINT UNSIGNED NULL',
        'request_date' => 'DATE NULL',
        'processed_date' => 'DATE NULL',
        'urgency' => 'ENUM("low", "medium", "high") DEFAULT "medium"',
        'preferred_contact' => 'ENUM("email", "phone", "sms") DEFAULT "email"',
        'sms_sent' => 'TINYINT(1) DEFAULT 0',
        'is_viewed' => 'TINYINT(1) DEFAULT 0',
        'viewed_at' => 'TIMESTAMP NULL',
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
                $pdo->exec("ALTER TABLE public_requests ADD COLUMN $column $definition");
                echo "   ✅ Colonne $column ajoutée\n";
            } catch (PDOException $e) {
                echo "   ❌ Erreur pour $column: " . $e->getMessage() . "\n";
            }
        } else {
            echo "   ⚠️ Colonne $column déjà présente\n";
        }
    }
    echo "\n";

    // 3. Renommer la colonne 'name' en 'full_name' si nécessaire
    echo "3️⃣ Vérification de la colonne name/full_name...\n";
    
    $hasName = false;
    $hasFullName = false;
    foreach ($columns as $col) {
        if ($col['Field'] === 'name') $hasName = true;
        if ($col['Field'] === 'full_name') $hasFullName = true;
    }
    
    if ($hasName && !$hasFullName) {
        try {
            $pdo->exec("ALTER TABLE public_requests CHANGE COLUMN name full_name VARCHAR(255) NOT NULL");
            echo "   ✅ Colonne 'name' renommée en 'full_name'\n";
        } catch (PDOException $e) {
            echo "   ❌ Erreur lors du renommage: " . $e->getMessage() . "\n";
        }
    } else if ($hasFullName) {
        echo "   ✅ Colonne 'full_name' déjà présente\n";
    } else {
        echo "   ⚠️ Aucune colonne name ou full_name trouvée\n";
    }
    echo "\n";

    // 4. Insérer des données de test
    echo "4️⃣ Insertion de données de test...\n";
    
    $testRequests = [
        [
            'tracking_code' => 'CSAR-ABC12345',
            'type' => 'aide',
            'full_name' => 'Jean Dupont',
            'email' => 'jean.dupont@example.com',
            'phone' => '+221123456789',
            'region' => 'Dakar',
            'description' => 'Demande d\'aide pour l\'intégration sociale',
            'urgency' => 'medium',
            'preferred_contact' => 'email',
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],
        [
            'tracking_code' => 'CSAR-DEF67890',
            'type' => 'partenariat',
            'full_name' => 'Marie Diop',
            'email' => 'marie.diop@example.com',
            'phone' => '+221987654321',
            'region' => 'Thiès',
            'description' => 'Proposition de partenariat pour l\'aide aux réfugiés',
            'urgency' => 'low',
            'preferred_contact' => 'phone',
            'status' => 'approved',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],
        [
            'tracking_code' => 'CSAR-GHI11111',
            'type' => 'audience',
            'full_name' => 'Amadou Ba',
            'email' => 'amadou.ba@example.com',
            'phone' => '+221555666777',
            'region' => 'Saint-Louis',
            'description' => 'Demande d\'audience avec le directeur',
            'urgency' => 'high',
            'preferred_contact' => 'sms',
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]
    ];
    
    foreach ($testRequests as $request) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO public_requests (
                tracking_code, type, full_name, email, phone, region, description, 
                urgency, preferred_contact, status, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $request['tracking_code'],
            $request['type'],
            $request['full_name'],
            $request['email'],
            $request['phone'],
            $request['region'],
            $request['description'],
            $request['urgency'],
            $request['preferred_contact'],
            $request['status'],
            $request['created_at'],
            $request['updated_at']
        ]);
    }
    echo "   ✅ Données de test insérées\n\n";

    // 5. Vérification finale
    echo "5️⃣ Vérification finale...\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM public_requests");
    $count = $stmt->fetchColumn();
    echo "   📊 Nombre total de demandes: $count\n";
    
    $stmt = $pdo->query("SELECT tracking_code, type, full_name, status FROM public_requests ORDER BY created_at DESC");
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "   📋 Demandes dans la base:\n";
    foreach ($requests as $request) {
        echo "      - {$request['tracking_code']} | {$request['type']} | {$request['full_name']} | {$request['status']}\n";
    }
    
    echo "\n🎉 TABLE PUBLIC_REQUESTS CORRIGÉE !\n";
    echo "==================================\n";
    echo "La table public_requests est maintenant compatible avec le modèle Laravel.\n";
    echo "Les demandes devraient maintenant se charger correctement.\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

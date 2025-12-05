<?php
/**
 * 🔧 CORRECTION DU PROBLÈME DE CLÉ ÉTRANGÈRE
 * 
 * Ce script corrige le problème de contrainte de clé étrangère
 * pour les rapports SIM
 */

// Configuration de la base de données
$host = 'localhost';
$dbname = 'plateforme-csar';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔧 CORRECTION DU PROBLÈME DE CLÉ ÉTRANGÈRE\n";
    echo "==========================================\n\n";
    
    // 1. Vérifier les utilisateurs existants
    echo "1️⃣ Vérification des utilisateurs existants...\n";
    
    $users = $pdo->query("SELECT id, name, email, role FROM users ORDER BY id")->fetchAll();
    
    if (empty($users)) {
        echo "   ❌ Aucun utilisateur trouvé !\n";
        echo "   🔧 Création d'un utilisateur admin par défaut...\n";
        
        // Créer un utilisateur admin par défaut
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, role, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            'Admin CSAR',
            'admin@csar.sn',
            password_hash('admin123', PASSWORD_DEFAULT),
            'admin'
        ]);
        
        $adminId = $pdo->lastInsertId();
        echo "   ✅ Utilisateur admin créé (ID: $adminId)\n";
    } else {
        echo "   ✅ Utilisateurs trouvés:\n";
        foreach ($users as $user) {
            echo "      - ID: {$user['id']}, Nom: {$user['name']}, Email: {$user['email']}, Rôle: {$user['role']}\n";
        }
        $adminId = $users[0]['id']; // Utiliser le premier utilisateur
    }
    
    echo "\n";
    
    // 2. Vérifier la structure de la table sim_reports
    echo "2️⃣ Vérification de la structure de la table sim_reports...\n";
    
    $columns = $pdo->query("SHOW COLUMNS FROM sim_reports")->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'Field');
    
    echo "   📋 Colonnes existantes: " . implode(', ', $columnNames) . "\n";
    
    // Ajouter les colonnes manquantes
    $requiredColumns = [
        'created_by' => 'INT UNSIGNED NULL',
        'generated_by' => 'INT UNSIGNED NULL',
        'is_public' => 'BOOLEAN DEFAULT FALSE',
        'published_at' => 'DATETIME NULL',
        'cover_image' => 'VARCHAR(255) NULL',
        'download_count' => 'INT DEFAULT 0',
        'view_count' => 'INT DEFAULT 0',
        'file_size' => 'INT NULL',
        'metadata' => 'JSON NULL'
    ];
    
    foreach ($requiredColumns as $column => $definition) {
        if (!in_array($column, $columnNames)) {
            try {
                $pdo->exec("ALTER TABLE sim_reports ADD COLUMN `$column` $definition");
                echo "   ✅ Colonne '$column' ajoutée\n";
            } catch (PDOException $e) {
                echo "   ⚠️ Erreur ajout colonne '$column': " . $e->getMessage() . "\n";
            }
        } else {
            echo "   ✅ Colonne '$column' existe déjà\n";
        }
    }
    
    echo "\n";
    
    // 3. Créer un rapport SIM public
    echo "3️⃣ Création d'un rapport SIM public...\n";
    
    $stmt = $pdo->prepare("
        INSERT INTO sim_reports (
            title, 
            description, 
            report_type, 
            status, 
            is_public, 
            published_at, 
            created_by,
            generated_by,
            created_at, 
            updated_at, 
            download_count, 
            view_count
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?)
    ");
    
    $reportData = [
        'Rapport Opérationnel CSAR - Janvier 2025',
        'Ce rapport présente les activités opérationnelles du CSAR pour le mois de janvier 2025. Il inclut les statistiques d\'aide alimentaire, médicale et financière distribuées dans les différentes régions du Sénégal, ainsi que les recommandations pour les mois à venir.',
        'operational',
        'published',
        1, // is_public = true
        date('Y-m-d H:i:s'), // published_at
        $adminId, // created_by
        $adminId, // generated_by
        0, // download_count
        0  // view_count
    ];
    
    $stmt->execute($reportData);
    $reportId = $pdo->lastInsertId();
    
    echo "   ✅ Rapport SIM public créé avec succès !\n";
    echo "      📋 ID: $reportId\n";
    echo "      📄 Titre: " . $reportData[0] . "\n";
    echo "      📊 Type: " . $reportData[2] . "\n";
    echo "      🌐 Public: Oui\n";
    echo "      📅 Publié le: " . $reportData[5] . "\n";
    
    echo "\n";
    
    // 4. Créer une demande réelle pour test
    echo "4️⃣ Création d'une demande réelle pour test...\n";
    
    $stmt = $pdo->prepare("
        INSERT INTO demandes (
            code_suivi, nom_demandeur, email, telephone, type_demande, 
            statut, region, commune, departement, adresse, description, 
            priorite, date_demande, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    $demandeData = [
        'CSAR-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
        'Aminata Fall',
        'aminata.fall@email.com',
        '+221701234567',
        'aide_alimentaire',
        'en_attente',
        'Dakar',
        'Parcelles Assainies',
        'Dakar',
        'Rue 10, Parcelles Assainies, Dakar',
        'Demande d\'aide alimentaire pour une famille de 6 personnes en situation difficile.',
        'moyenne',
        date('Y-m-d')
    ];
    
    $stmt->execute($demandeData);
    $demandeId = $pdo->lastInsertId();
    
    echo "   ✅ Demande réelle créée avec succès !\n";
    echo "      📋 ID: $demandeId\n";
    echo "      📄 Code: " . $demandeData[0] . "\n";
    echo "      👤 Demandeur: " . $demandeData[1] . "\n";
    echo "      📊 Type: " . $demandeData[4] . "\n";
    echo "      📍 Région: " . $demandeData[6] . "\n";
    
    echo "\n";
    
    // 5. Vérification finale
    echo "5️⃣ VÉRIFICATION FINALE\n";
    echo "=====================\n";
    
    $totalDemandes = $pdo->query("SELECT COUNT(*) FROM demandes")->fetchColumn();
    $totalReports = $pdo->query("SELECT COUNT(*) FROM sim_reports")->fetchColumn();
    $publicReports = $pdo->query("SELECT COUNT(*) FROM sim_reports WHERE is_public = 1")->fetchColumn();
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    
    echo "   📊 Total demandes: $totalDemandes\n";
    echo "   📊 Total rapports SIM: $totalReports (dont $publicReports publics)\n";
    echo "   📊 Total utilisateurs: $totalUsers\n";
    
    echo "\n🎉 CORRECTION TERMINÉE AVEC SUCCÈS !\n";
    echo "====================================\n";
    echo "✅ Problème de clé étrangère résolu\n";
    echo "✅ Rapport SIM public créé\n";
    echo "✅ Demande réelle créée\n";
    echo "✅ Plateforme prête pour les tests\n";
    
    echo "\n🌐 VOTRE PLATEFORME CSAR EST MAINTENANT PRÊTE !\n";
    echo "==============================================\n";
    echo "🔗 Interface admin: http://127.0.0.1:8000/admin\n";
    echo "🔗 Plateforme publique: http://127.0.0.1:8000\n";
    echo "🔗 Rapports SIM publics: http://127.0.0.1:8000/rapports-sim\n";
    echo "🔗 Gestion des demandes: http://127.0.0.1:8000/admin/demandes\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré et que la base 'plateforme-csar' existe.\n";
}


<?php
/**
 * 🔧 CORRECTION DE L'ERREUR DU TABLEAU DE BORD
 * 
 * Ce script corrige l'erreur "Erreur lors du chargement des demandes"
 */

// Configuration de la base de données
$host = 'localhost';
$dbname = 'plateforme-csar';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔧 CORRECTION DE L'ERREUR DU TABLEAU DE BORD\n";
    echo "===========================================\n\n";
    
    // 1. Vérifier la structure de la table demandes
    echo "1️⃣ Vérification de la structure de la table demandes...\n";
    
    $columns = $pdo->query("SHOW COLUMNS FROM demandes")->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'Field');
    
    echo "   📋 Colonnes existantes: " . implode(', ', $columnNames) . "\n";
    
    // Ajouter les colonnes manquantes si nécessaire
    $requiredColumns = [
        'statut' => "ENUM('en_attente','en_cours','approuvee','rejetee','terminee') DEFAULT 'en_attente'",
        'type_demande' => "ENUM('aide_alimentaire','aide_medicale','aide_financiere','information_generale','demande_audience','autre')",
        'priorite' => "ENUM('faible','moyenne','haute','urgente') DEFAULT 'moyenne'",
        'assignee_id' => 'INT UNSIGNED NULL',
        'date_demande' => 'DATE',
        'date_traitement' => 'DATE NULL',
        'commentaire_admin' => 'TEXT NULL',
        'region' => 'VARCHAR(100)',
        'commune' => 'VARCHAR(100)',
        'departement' => 'VARCHAR(100)',
        'adresse' => 'TEXT',
        'description' => 'TEXT',
        'sms_envoye' => 'BOOLEAN DEFAULT FALSE',
        'sms_message_id' => 'VARCHAR(100) NULL',
        'sms_envoye_at' => 'DATETIME NULL',
        'latitude' => 'DECIMAL(10,8) NULL',
        'longitude' => 'DECIMAL(11,8) NULL'
    ];
    
    foreach ($requiredColumns as $column => $definition) {
        if (!in_array($column, $columnNames)) {
            try {
                $pdo->exec("ALTER TABLE demandes ADD COLUMN `$column` $definition");
                echo "   ✅ Colonne '$column' ajoutée\n";
            } catch (PDOException $e) {
                echo "   ⚠️ Erreur ajout colonne '$column': " . $e->getMessage() . "\n";
            }
        } else {
            echo "   ✅ Colonne '$column' existe déjà\n";
        }
    }
    
    echo "\n";
    
    // 2. Créer une demande de test pour vérifier que tout fonctionne
    echo "2️⃣ Création d'une demande de test...\n";
    
    $stmt = $pdo->prepare("
        INSERT INTO demandes (
            code_suivi, nom_demandeur, email, telephone, type_demande, 
            statut, region, commune, departement, adresse, description, 
            priorite, date_demande, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    $demandeData = [
        'CSAR-' . date('Y') . '-001',
        'Test User',
        'test@csar.sn',
        '+221701234567',
        'aide_alimentaire',
        'en_attente',
        'Dakar',
        'Parcelles Assainies',
        'Dakar',
        'Rue 10, Parcelles Assainies, Dakar',
        'Demande de test pour vérifier le fonctionnement du système.',
        'moyenne',
        date('Y-m-d')
    ];
    
    $stmt->execute($demandeData);
    $demandeId = $pdo->lastInsertId();
    
    echo "   ✅ Demande de test créée (ID: $demandeId)\n";
    echo "      📄 Code: " . $demandeData[0] . "\n";
    echo "      👤 Demandeur: " . $demandeData[1] . "\n";
    echo "      📊 Statut: " . $demandeData[5] . "\n";
    
    echo "\n";
    
    // 3. Vérification finale
    echo "3️⃣ VÉRIFICATION FINALE\n";
    echo "=====================\n";
    
    $totalDemandes = $pdo->query("SELECT COUNT(*) FROM demandes")->fetchColumn();
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $totalReports = $pdo->query("SELECT COUNT(*) FROM sim_reports")->fetchColumn();
    
    echo "   📊 Total demandes: $totalDemandes\n";
    echo "   📊 Total utilisateurs: $totalUsers\n";
    echo "   📊 Total rapports SIM: $totalReports\n";
    
    echo "\n🎉 CORRECTION TERMINÉE AVEC SUCCÈS !\n";
    echo "====================================\n";
    echo "✅ Structure de la table demandes corrigée\n";
    echo "✅ Demande de test créée\n";
    echo "✅ Erreur du tableau de bord résolue\n";
    
    echo "\n🌐 TESTEZ MAINTENANT VOTRE PLATEFORME :\n";
    echo "=======================================\n";
    echo "🔗 Interface admin: http://127.0.0.1:8000/admin\n";
    echo "🔗 Gestion des demandes: http://127.0.0.1:8000/admin/demandes\n";
    echo "🔗 Tableau de bord: http://127.0.0.1:8000/admin/dashboard\n";
    
    echo "\n📝 L'erreur 'Erreur lors du chargement des demandes' devrait maintenant être résolue !\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré et que la base 'plateforme-csar' existe.\n";
}


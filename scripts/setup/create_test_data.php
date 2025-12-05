<?php
/**
 * 📊 CRÉATION DE DONNÉES DE TEST RÉELLES
 * 
 * Ce script crée des données de test réelles pour la plateforme CSAR
 */

// Configuration de la base de données
$host = 'localhost';
$dbname = 'plateforme-csar';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "📊 CRÉATION DE DONNÉES DE TEST RÉELLES\n";
    echo "=====================================\n\n";
    
    // 1. Créer des demandes réelles
    echo "1️⃣ Création de demandes réelles...\n";
    
    $demandes = [
        [
            'code_suivi' => 'CSAR-' . date('Y') . '-001',
            'nom_demandeur' => 'Aminata Fall',
            'email' => 'aminata.fall@email.com',
            'telephone' => '+221701234567',
            'type_demande' => 'aide_alimentaire',
            'statut' => 'en_attente',
            'region' => 'Dakar',
            'commune' => 'Parcelles Assainies',
            'departement' => 'Dakar',
            'adresse' => 'Rue 10, Parcelles Assainies, Dakar',
            'description' => 'Demande d\'aide alimentaire pour une famille de 6 personnes en situation difficile.',
            'priorite' => 'moyenne',
            'date_demande' => date('Y-m-d')
        ],
        [
            'code_suivi' => 'CSAR-' . date('Y') . '-002',
            'nom_demandeur' => 'Moussa Diop',
            'email' => 'moussa.diop@email.com',
            'telephone' => '+221701234568',
            'type_demande' => 'aide_medicale',
            'statut' => 'en_cours',
            'region' => 'Thiès',
            'commune' => 'Thiès',
            'departement' => 'Thiès',
            'adresse' => 'Quartier Médina, Thiès',
            'description' => 'Demande d\'aide médicale pour traitement d\'urgence.',
            'priorite' => 'haute',
            'date_demande' => date('Y-m-d', strtotime('-2 days'))
        ],
        [
            'code_suivi' => 'CSAR-' . date('Y') . '-003',
            'nom_demandeur' => 'Fatou Sarr',
            'email' => 'fatou.sarr@email.com',
            'telephone' => '+221701234569',
            'type_demande' => 'aide_financiere',
            'statut' => 'approuvee',
            'region' => 'Saint-Louis',
            'commune' => 'Saint-Louis',
            'departement' => 'Saint-Louis',
            'adresse' => 'Quartier Nord, Saint-Louis',
            'description' => 'Demande d\'aide financière pour relancer une activité commerciale.',
            'priorite' => 'moyenne',
            'date_demande' => date('Y-m-d', strtotime('-5 days'))
        ]
    ];
    
    $stmt = $pdo->prepare("
        INSERT INTO demandes (
            code_suivi, nom_demandeur, email, telephone, type_demande, 
            statut, region, commune, departement, adresse, description, 
            priorite, date_demande, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    $createdCount = 0;
    foreach ($demandes as $demande) {
        try {
            $stmt->execute(array_values($demande));
            $createdCount++;
            echo "   ✅ Demande créée: {$demande['code_suivi']} - {$demande['nom_demandeur']}\n";
        } catch (PDOException $e) {
            echo "   ⚠️ Erreur création demande {$demande['code_suivi']}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "   📊 Total demandes créées: $createdCount\n\n";
    
    // 2. Créer des rapports SIM supplémentaires
    echo "2️⃣ Création de rapports SIM supplémentaires...\n";
    
    $rapports = [
        [
            'title' => 'Rapport Financier CSAR - Décembre 2024',
            'description' => 'Rapport financier détaillé des activités du CSAR pour le mois de décembre 2024, incluant les dépenses, revenus et projections budgétaires.',
            'report_type' => 'financial',
            'status' => 'published',
            'is_public' => 1,
            'published_at' => date('Y-m-d H:i:s', strtotime('-10 days'))
        ],
        [
            'title' => 'Rapport Inventaire CSAR - Janvier 2025',
            'description' => 'État des stocks et inventaire des entrepôts CSAR pour janvier 2025, avec analyse des mouvements et recommandations.',
            'report_type' => 'inventory',
            'status' => 'published',
            'is_public' => 1,
            'published_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
        ],
        [
            'title' => 'Rapport Personnel CSAR - Janvier 2025',
            'description' => 'Rapport sur les ressources humaines du CSAR, formations, évaluations et planification des effectifs.',
            'report_type' => 'personnel',
            'status' => 'completed',
            'is_public' => 0,
            'published_at' => null
        ]
    ];
    
    $stmt = $pdo->prepare("
        INSERT INTO sim_reports (
            title, description, report_type, status, is_public, 
            published_at, created_by, generated_by, created_at, updated_at, 
            download_count, view_count
        ) VALUES (?, ?, ?, ?, ?, ?, 1, 1, NOW(), NOW(), ?, ?)
    ");
    
    $createdCount = 0;
    foreach ($rapports as $rapport) {
        try {
            $stmt->execute([
                $rapport['title'],
                $rapport['description'],
                $rapport['report_type'],
                $rapport['status'],
                $rapport['is_public'],
                $rapport['published_at'],
                rand(0, 5), // download_count
                rand(10, 50) // view_count
            ]);
            $createdCount++;
            echo "   ✅ Rapport créé: {$rapport['title']}\n";
        } catch (PDOException $e) {
            echo "   ⚠️ Erreur création rapport: " . $e->getMessage() . "\n";
        }
    }
    
    echo "   📊 Total rapports créés: $createdCount\n\n";
    
    // 3. Créer des notifications
    echo "3️⃣ Création de notifications...\n";
    
    $notifications = [
        [
            'type' => 'info',
            'title' => 'Nouvelle demande reçue',
            'message' => 'Une nouvelle demande d\'aide alimentaire a été reçue de la région de Dakar.',
            'user_id' => 1
        ],
        [
            'type' => 'success',
            'title' => 'Rapport publié',
            'message' => 'Le rapport opérationnel de janvier 2025 a été publié avec succès.',
            'user_id' => 1
        ],
        [
            'type' => 'warning',
            'title' => 'Demande urgente',
            'message' => 'Une demande d\'aide médicale urgente nécessite votre attention.',
            'user_id' => 1
        ]
    ];
    
    $stmt = $pdo->prepare("
        INSERT INTO notifications (type, title, message, user_id, read, created_at, updated_at) 
        VALUES (?, ?, ?, ?, 0, NOW(), NOW())
    ");
    
    $createdCount = 0;
    foreach ($notifications as $notification) {
        try {
            $stmt->execute(array_values($notification));
            $createdCount++;
            echo "   ✅ Notification créée: {$notification['title']}\n";
        } catch (PDOException $e) {
            echo "   ⚠️ Erreur création notification: " . $e->getMessage() . "\n";
        }
    }
    
    echo "   📊 Total notifications créées: $createdCount\n\n";
    
    // 4. Vérification finale
    echo "4️⃣ VÉRIFICATION FINALE\n";
    echo "=====================\n";
    
    $totalDemandes = $pdo->query("SELECT COUNT(*) FROM demandes")->fetchColumn();
    $totalReports = $pdo->query("SELECT COUNT(*) FROM sim_reports")->fetchColumn();
    $publicReports = $pdo->query("SELECT COUNT(*) FROM sim_reports WHERE is_public = 1")->fetchColumn();
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $totalNotifications = $pdo->query("SELECT COUNT(*) FROM notifications")->fetchColumn();
    
    echo "   📊 Total demandes: $totalDemandes\n";
    echo "   📊 Total rapports SIM: $totalReports (dont $publicReports publics)\n";
    echo "   📊 Total utilisateurs: $totalUsers\n";
    echo "   📊 Total notifications: $totalNotifications\n";
    
    echo "\n🎉 DONNÉES DE TEST CRÉÉES AVEC SUCCÈS !\n";
    echo "=======================================\n";
    echo "✅ Demandes réelles créées\n";
    echo "✅ Rapports SIM publics créés\n";
    echo "✅ Notifications créées\n";
    echo "✅ Plateforme prête pour les tests\n";
    
    echo "\n🌐 TESTEZ VOTRE PLATEFORME :\n";
    echo "============================\n";
    echo "🔗 Interface admin: http://127.0.0.1:8000/admin\n";
    echo "   - Gestion des demandes: http://127.0.0.1:8000/admin/demandes\n";
    echo "   - Rapports SIM: http://127.0.0.1:8000/admin/sim-reports\n";
    echo "   - Statistiques: http://127.0.0.1:8000/admin/statistics\n";
    echo "\n🔗 Plateforme publique: http://127.0.0.1:8000\n";
    echo "   - Rapports SIM publics: http://127.0.0.1:8000/rapports-sim\n";
    echo "   - Actualités: http://127.0.0.1:8000/actualites\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré et que la base 'plateforme-csar' existe.\n";
}


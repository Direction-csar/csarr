<?php
/**
 * 🧹 NETTOYAGE COMPLET DES DONNÉES FICTIVES
 * 
 * Ce script supprime TOUTES les données fictives de la plateforme CSAR
 */

// Configuration de la base de données
$host = 'localhost';
$dbname = 'plateforme-csar';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🧹 NETTOYAGE COMPLET DES DONNÉES FICTIVES\n";
    echo "========================================\n\n";
    
    // 1. Supprimer TOUS les rapports SIM fictifs
    echo "1️⃣ Suppression de TOUS les rapports SIM fictifs...\n";
    
    // Supprimer les rapports avec des titres bizarres
    $stmt = $pdo->query("DELETE FROM sim_reports WHERE title LIKE '%htdujrfdys%' OR title LIKE '%ggdjmkfujdgx%'");
    $deletedCount = $stmt->rowCount();
    echo "   🗑️ Rapports avec titres bizarres supprimés: $deletedCount\n";
    
    // Supprimer les rapports de test
    $stmt = $pdo->query("DELETE FROM sim_reports WHERE title LIKE '%test%' OR title LIKE '%Test%' OR description LIKE '%test%'");
    $deletedCount += $stmt->rowCount();
    echo "   🗑️ Rapports de test supprimés: $deletedCount\n";
    
    // Supprimer les rapports avec des dates futures (14/10/2025)
    $stmt = $pdo->query("DELETE FROM sim_reports WHERE created_at > NOW() OR published_at > NOW()");
    $deletedCount += $stmt->rowCount();
    echo "   🗑️ Rapports avec dates futures supprimés: $deletedCount\n";
    
    // Supprimer TOUS les rapports SIM pour repartir à zéro
    $stmt = $pdo->query("DELETE FROM sim_reports");
    $totalDeleted = $stmt->rowCount();
    echo "   🗑️ TOTAL rapports SIM supprimés: $totalDeleted\n";
    
    echo "\n";
    
    // 2. Supprimer TOUTES les demandes fictives
    echo "2️⃣ Suppression de TOUTES les demandes fictives...\n";
    
    // Supprimer les demandes avec des codes de test
    $fakeCodes = ['CSAR-2025-001', 'CSAR-2025-002', 'CSAR-2025-003', 'CSAR-2025-004', 'CSAR-2025-005', 'CSAR-REAL001', 'CSAR-REAL002', 'CSAR-TEST001', 'CSAR-TEST002', 'CSAR-TEST003'];
    
    $deletedCount = 0;
    foreach ($fakeCodes as $code) {
        $stmt = $pdo->prepare("DELETE FROM demandes WHERE code_suivi = ?");
        $stmt->execute([$code]);
        $deletedCount += $stmt->rowCount();
    }
    
    // Supprimer les demandes avec des noms fictifs
    $fakeNames = ['Mariama Diop', 'Amadou Ba', 'Fatou Sarr', 'Ibrahima Fall', 'Aïcha Ndiaye', 'Mamadou Diallo', 'Aminata Fall', 'Moussa Diop'];
    foreach ($fakeNames as $name) {
        $stmt = $pdo->prepare("DELETE FROM demandes WHERE nom_demandeur = ?");
        $stmt->execute([$name]);
        $deletedCount += $stmt->rowCount();
    }
    
    // Supprimer les demandes avec des emails de test
    $stmt = $pdo->query("DELETE FROM demandes WHERE email LIKE '%test%' OR email LIKE '%example%' OR email LIKE '%@email.com%'");
    $deletedCount += $stmt->rowCount();
    
    // Supprimer TOUTES les demandes pour repartir à zéro
    $stmt = $pdo->query("DELETE FROM demandes");
    $totalDeleted = $stmt->rowCount();
    echo "   🗑️ TOTAL demandes supprimées: $totalDeleted\n";
    
    echo "\n";
    
    // 3. Supprimer les notifications fictives
    echo "3️⃣ Suppression des notifications fictives...\n";
    
    $stmt = $pdo->query("DELETE FROM notifications WHERE message LIKE '%test%' OR title LIKE '%Test%' OR message LIKE '%demande%' OR title LIKE '%rapport%'");
    $deletedCount = $stmt->rowCount();
    echo "   🗑️ Notifications fictives supprimées: $deletedCount\n";
    
    echo "\n";
    
    // 4. Vérifier et nettoyer les utilisateurs (garder seulement les admins)
    echo "4️⃣ Nettoyage des utilisateurs...\n";
    
    $stmt = $pdo->query("DELETE FROM users WHERE (name LIKE '%Test%' OR email LIKE '%test%' OR email LIKE '%example%') AND role != 'admin'");
    $deletedCount = $stmt->rowCount();
    echo "   🗑️ Utilisateurs fictifs supprimés: $deletedCount\n";
    
    // Vérifier s'il reste des utilisateurs
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($userCount == 0) {
        echo "   ⚠️ Aucun utilisateur trouvé, création d'un admin par défaut...\n";
        
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
        
        echo "   ✅ Utilisateur admin créé\n";
    }
    
    echo "\n";
    
    // 5. Vérification finale
    echo "5️⃣ VÉRIFICATION FINALE\n";
    echo "=====================\n";
    
    $totalDemandes = $pdo->query("SELECT COUNT(*) FROM demandes")->fetchColumn();
    $totalReports = $pdo->query("SELECT COUNT(*) FROM sim_reports")->fetchColumn();
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $totalNotifications = $pdo->query("SELECT COUNT(*) FROM notifications")->fetchColumn();
    
    echo "   📊 Total demandes: $totalDemandes\n";
    echo "   📊 Total rapports SIM: $totalReports\n";
    echo "   📊 Total utilisateurs: $totalUsers\n";
    echo "   📊 Total notifications: $totalNotifications\n";
    
    echo "\n🎉 NETTOYAGE COMPLET TERMINÉ !\n";
    echo "=============================\n";
    echo "✅ TOUTES les données fictives ont été supprimées\n";
    echo "✅ Votre plateforme CSAR est maintenant VIDE et PROPRE\n";
    echo "✅ Vous pouvez maintenant créer de vraies données\n";
    
    echo "\n🌐 VOTRE PLATEFORME EST MAINTENANT PROPRE :\n";
    echo "==========================================\n";
    echo "🔗 Interface admin: http://127.0.0.1:8000/admin\n";
    echo "🔗 Plateforme publique: http://127.0.0.1:8000\n";
    echo "🔗 Gestion des demandes: http://127.0.0.1:8000/admin/demandes\n";
    echo "🔗 Rapports SIM: http://127.0.0.1:8000/admin/sim-reports\n";
    
    echo "\n📝 PROCHAINES ÉTAPES :\n";
    echo "====================\n";
    echo "1. Connectez-vous à l'admin avec: admin@csar.sn / admin123\n";
    echo "2. Créez de vraies demandes via l'interface\n";
    echo "3. Générez de vrais rapports SIM\n";
    echo "4. Publiez les rapports sur la plateforme publique\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré et que la base 'plateforme-csar' existe.\n";
}


<?php
/**
 * 🧹 NETTOYAGE SIMPLE DES DONNÉES FICTIVES
 * 
 * Script simple pour supprimer les données fictives de la plateforme CSAR
 */

// Configuration de la base de données
$host = 'localhost';
$dbname = 'plateforme-csar';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🧹 NETTOYAGE DES DONNÉES FICTIVES CSAR\n";
    echo "=====================================\n\n";
    
    // 1. Supprimer les demandes fictives
    echo "1️⃣ Suppression des demandes fictives...\n";
    
    $fakeCodes = ['CSAR-2025-001', 'CSAR-2025-002', 'CSAR-2025-003', 'CSAR-2025-004', 'CSAR-2025-005', 'CSAR-REAL001', 'CSAR-REAL002', 'CSAR-TEST001', 'CSAR-TEST002', 'CSAR-TEST003'];
    
    $deletedCount = 0;
    foreach ($fakeCodes as $code) {
        $stmt = $pdo->prepare("DELETE FROM demandes WHERE code_suivi = ?");
        $stmt->execute([$code]);
        $deletedCount += $stmt->rowCount();
    }
    
    // Supprimer par noms fictifs
    $fakeNames = ['Mariama Diop', 'Amadou Ba', 'Fatou Sarr', 'Ibrahima Fall', 'Aïcha Ndiaye', 'Mamadou Diallo'];
    foreach ($fakeNames as $name) {
        $stmt = $pdo->prepare("DELETE FROM demandes WHERE nom_demandeur = ?");
        $stmt->execute([$name]);
        $deletedCount += $stmt->rowCount();
    }
    
    echo "   ✅ $deletedCount demandes fictives supprimées\n";
    
    // 2. Supprimer les rapports SIM fictifs
    echo "2️⃣ Suppression des rapports SIM fictifs...\n";
    
    $stmt = $pdo->query("DELETE FROM sim_reports WHERE title LIKE '%test%' OR title LIKE '%Test%' OR description LIKE '%test%'");
    $deletedCount = $stmt->rowCount();
    echo "   ✅ $deletedCount rapports SIM fictifs supprimés\n";
    
    // 3. Supprimer les utilisateurs fictifs (garder les admins)
    echo "3️⃣ Suppression des utilisateurs fictifs...\n";
    
    $stmt = $pdo->query("DELETE FROM users WHERE (name LIKE '%Test%' OR email LIKE '%test%' OR email LIKE '%example%') AND role != 'admin'");
    $deletedCount = $stmt->rowCount();
    echo "   ✅ $deletedCount utilisateurs fictifs supprimés\n";
    
    // 4. Supprimer les notifications fictives
    echo "4️⃣ Suppression des notifications fictives...\n";
    
    $stmt = $pdo->query("DELETE FROM notifications WHERE message LIKE '%test%' OR title LIKE '%Test%'");
    $deletedCount = $stmt->rowCount();
    echo "   ✅ $deletedCount notifications fictives supprimées\n";
    
    echo "\n🎉 NETTOYAGE TERMINÉ !\n";
    echo "=====================\n";
    echo "✅ Toutes les données fictives ont été supprimées\n";
    echo "✅ Votre plateforme CSAR est maintenant propre\n";
    echo "✅ Vous pouvez maintenant publier des rapports SIM publics\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré et que la base 'plateforme-csar' existe.\n";
}


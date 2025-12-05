<?php
/**
 * 📄 CRÉATION D'UN RAPPORT SIM PUBLIC
 * 
 * Ce script crée un rapport SIM public pour tester la publication
 */

// Configuration de la base de données
$host = 'localhost';
$dbname = 'plateforme-csar';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "📄 CRÉATION D'UN RAPPORT SIM PUBLIC\n";
    echo "===================================\n\n";
    
    // Vérifier si la table sim_reports existe
    $checkTable = $pdo->query("SHOW TABLES LIKE 'sim_reports'")->fetch();
    if (!$checkTable) {
        echo "❌ Table 'sim_reports' n'existe pas\n";
        exit;
    }
    
    // Créer un rapport SIM public
    $stmt = $pdo->prepare("
        INSERT INTO sim_reports (
            title, 
            description, 
            report_type, 
            status, 
            is_public, 
            published_at, 
            created_at, 
            updated_at, 
            download_count, 
            view_count,
            generated_by
        ) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?, ?)
    ");
    
    $reportData = [
        'Rapport Opérationnel CSAR - Janvier 2025',
        'Ce rapport présente les activités opérationnelles du CSAR pour le mois de janvier 2025. Il inclut les statistiques d\'aide alimentaire, médicale et financière distribuées dans les différentes régions du Sénégal, ainsi que les recommandations pour les mois à venir.',
        'operational',
        'published',
        1, // is_public = true
        date('Y-m-d H:i:s'), // published_at
        0, // download_count
        0, // view_count
        1  // generated_by (admin)
    ];
    
    $stmt->execute($reportData);
    $reportId = $pdo->lastInsertId();
    
    echo "✅ Rapport SIM public créé avec succès !\n";
    echo "   📋 ID: $reportId\n";
    echo "   📄 Titre: " . $reportData[0] . "\n";
    echo "   📊 Type: " . $reportData[2] . "\n";
    echo "   🌐 Public: Oui\n";
    echo "   📅 Publié le: " . $reportData[5] . "\n";
    
    echo "\n🌐 VOTRE RAPPORT EST MAINTENANT VISIBLE SUR :\n";
    echo "============================================\n";
    echo "🔗 Plateforme publique: http://localhost:8000\n";
    echo "🔗 Section Rapports SIM: http://localhost:8000/rapports-sim\n";
    echo "🔗 Interface admin: http://localhost:8000/admin/sim-reports\n";
    
    echo "\n📊 STATISTIQUES ACTUELLES :\n";
    echo "==========================\n";
    
    $totalReports = $pdo->query("SELECT COUNT(*) FROM sim_reports")->fetchColumn();
    $publicReports = $pdo->query("SELECT COUNT(*) FROM sim_reports WHERE is_public = 1")->fetchColumn();
    
    echo "   📈 Total rapports: $totalReports\n";
    echo "   🌐 Rapports publics: $publicReports\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Vérifiez que MySQL est démarré et que la base 'plateforme-csar' existe.\n";
}


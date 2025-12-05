<?php
$pdo = new PDO("mysql:host=localhost;dbname=plateforme-csar;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "🔧 CORRECTION RAPIDE DU TABLEAU DE BORD\n";
echo "======================================\n\n";

// Ajouter les colonnes manquantes
$columns = [
    "ALTER TABLE demandes ADD COLUMN statut ENUM('en_attente','en_cours','approuvee','rejetee','terminee') DEFAULT 'en_attente'",
    "ALTER TABLE demandes ADD COLUMN type_demande ENUM('aide_alimentaire','aide_medicale','aide_financiere','information_generale','demande_audience','autre')",
    "ALTER TABLE demandes ADD COLUMN priorite ENUM('faible','moyenne','haute','urgente') DEFAULT 'moyenne'",
    "ALTER TABLE demandes ADD COLUMN assignee_id INT UNSIGNED NULL",
    "ALTER TABLE demandes ADD COLUMN date_demande DATE",
    "ALTER TABLE demandes ADD COLUMN date_traitement DATE NULL",
    "ALTER TABLE demandes ADD COLUMN commentaire_admin TEXT NULL",
    "ALTER TABLE demandes ADD COLUMN region VARCHAR(100)",
    "ALTER TABLE demandes ADD COLUMN commune VARCHAR(100)",
    "ALTER TABLE demandes ADD COLUMN departement VARCHAR(100)",
    "ALTER TABLE demandes ADD COLUMN adresse TEXT",
    "ALTER TABLE demandes ADD COLUMN description TEXT",
    "ALTER TABLE demandes ADD COLUMN sms_envoye BOOLEAN DEFAULT FALSE",
    "ALTER TABLE demandes ADD COLUMN sms_message_id VARCHAR(100) NULL",
    "ALTER TABLE demandes ADD COLUMN sms_envoye_at DATETIME NULL",
    "ALTER TABLE demandes ADD COLUMN latitude DECIMAL(10,8) NULL",
    "ALTER TABLE demandes ADD COLUMN longitude DECIMAL(11,8) NULL"
];

foreach ($columns as $sql) {
    try {
        $pdo->exec($sql);
        echo "✅ Colonne ajoutée\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "✅ Colonne existe déjà\n";
        } else {
            echo "⚠️ Erreur: " . $e->getMessage() . "\n";
        }
    }
}

// Créer une demande de test
try {
    $pdo->exec("INSERT INTO demandes (code_suivi, nom_demandeur, email, telephone, type_demande, statut, region, commune, departement, adresse, description, priorite, date_demande, created_at, updated_at) VALUES ('CSAR-2025-001', 'Test User', 'test@csar.sn', '+221701234567', 'aide_alimentaire', 'en_attente', 'Dakar', 'Parcelles Assainies', 'Dakar', 'Rue 10, Parcelles Assainies, Dakar', 'Demande de test pour vérifier le fonctionnement du système.', 'moyenne', CURDATE(), NOW(), NOW())");
    echo "✅ Demande de test créée\n";
} catch (PDOException $e) {
    echo "⚠️ Erreur création demande: " . $e->getMessage() . "\n";
}

echo "\n🎉 CORRECTION TERMINÉE !\n";
echo "L'erreur du tableau de bord devrait maintenant être résolue.\n";
echo "Actualisez votre page admin: http://127.0.0.1:8000/admin\n";
?>


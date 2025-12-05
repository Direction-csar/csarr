<?php
$pdo = new PDO("mysql:host=localhost;dbname=plateforme-csar;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "🧹 NETTOYAGE RAPIDE DES DONNÉES FICTIVES\n";
echo "=======================================\n\n";

// Supprimer TOUS les rapports SIM
$stmt = $pdo->query("DELETE FROM sim_reports");
echo "✅ Rapports SIM supprimés: " . $stmt->rowCount() . "\n";

// Supprimer TOUTES les demandes
$stmt = $pdo->query("DELETE FROM demandes");
echo "✅ Demandes supprimées: " . $stmt->rowCount() . "\n";

// Supprimer les notifications
$stmt = $pdo->query("DELETE FROM notifications");
echo "✅ Notifications supprimées: " . $stmt->rowCount() . "\n";

// Vérifier les utilisateurs
$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
if ($userCount == 0) {
    $pdo->query("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES ('Admin CSAR', 'admin@csar.sn', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW())");
    echo "✅ Utilisateur admin créé\n";
} else {
    echo "✅ Utilisateurs existants: $userCount\n";
}

echo "\n🎉 NETTOYAGE TERMINÉ !\n";
echo "Votre plateforme est maintenant propre.\n";
echo "Connectez-vous avec: admin@csar.sn / admin123\n";
?>


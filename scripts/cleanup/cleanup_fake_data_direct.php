<?php

/**
 * Script de nettoyage des données fictives - Version directe PDO
 */

// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'csar_platform_2025';
$db_user = 'laravel_user';
$db_pass = 'csar@2025Host1';

echo "🧹 Nettoyage des données fictives...\n";
echo "====================================\n\n";

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données réussie\n\n";

    // Supprimer les messages de test
    echo "1️⃣ Suppression des messages de test...\n";
    $stmt = $pdo->prepare("DELETE FROM messages WHERE sujet LIKE ? OR sujet LIKE ? OR expediteur LIKE ?");
    $stmt->execute(['%test%', '%Test%', '%test%']);
    $deleted = $stmt->rowCount();
    echo "   📧 Messages supprimés: $deleted\n";

    // Supprimer les notifications de test
    echo "2️⃣ Suppression des notifications de test...\n";
    $stmt = $pdo->prepare("DELETE FROM notifications WHERE title LIKE ? OR title LIKE ?");
    $stmt->execute(['%test%', '%Test%']);
    $deleted = $stmt->rowCount();
    echo "   🔔 Notifications supprimées: $deleted\n";

    // Supprimer les contacts de test
    echo "3️⃣ Suppression des contacts de test...\n";
    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE email LIKE ? OR email LIKE ?");
    $stmt->execute(['%test%', '%@example.com']);
    $deleted = $stmt->rowCount();
    echo "   📞 Contacts supprimés: $deleted\n";

    // Supprimer les demandes de test
    echo "4️⃣ Suppression des demandes de test...\n";
    $stmt = $pdo->prepare("DELETE FROM public_requests WHERE email LIKE ? OR email LIKE ?");
    $stmt->execute(['%test%', '%@example.com']);
    $deleted = $stmt->rowCount();
    echo "   📋 Demandes supprimées: $deleted\n";

    // Supprimer les abonnés de test
    echo "5️⃣ Suppression des abonnés de test...\n";
    $stmt = $pdo->prepare("DELETE FROM newsletter_subscribers WHERE email LIKE ? OR email LIKE ?");
    $stmt->execute(['%test%', '%@example.com']);
    $deleted = $stmt->rowCount();
    echo "   📧 Abonnés supprimés: $deleted\n";

    // Supprimer les utilisateurs de test (sauf les vrais comptes)
    echo "6️⃣ Suppression des utilisateurs de test...\n";
    $stmt = $pdo->prepare("DELETE FROM users WHERE email LIKE ? OR email LIKE ? OR name LIKE ?");
    $stmt->execute(['%test%', '%@example.com', '%Test%']);
    $deleted = $stmt->rowCount();
    echo "   👥 Utilisateurs supprimés: $deleted\n";

    // Nettoyer les données vides ou invalides
    echo "7️⃣ Nettoyage des données vides...\n";
    
    // Messages sans contenu
    $stmt = $pdo->prepare("DELETE FROM messages WHERE sujet IS NULL OR sujet = '' OR contenu IS NULL OR contenu = ''");
    $stmt->execute();
    $deleted = $stmt->rowCount();
    echo "   📧 Messages vides supprimés: $deleted\n";

    // Notifications sans titre
    $stmt = $pdo->prepare("DELETE FROM notifications WHERE title IS NULL OR title = '' OR message IS NULL OR message = ''");
    $stmt->execute();
    $deleted = $stmt->rowCount();
    echo "   🔔 Notifications vides supprimées: $deleted\n";

    // Contacts sans email valide
    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE email IS NULL OR email = '' OR email NOT LIKE '%@%'");
    $stmt->execute();
    $deleted = $stmt->rowCount();
    echo "   📞 Contacts invalides supprimés: $deleted\n";

    echo "\n✅ Nettoyage terminé avec succès !\n";
    echo "==================================\n\n";

    // Afficher les statistiques finales
    echo "📊 Statistiques finales :\n";
    
    $tables = [
        'users' => 'Utilisateurs',
        'messages' => 'Messages',
        'notifications' => 'Notifications',
        'contact_messages' => 'Contacts',
        'public_requests' => 'Demandes',
        'newsletter_subscribers' => 'Abonnés Newsletter'
    ];

    foreach ($tables as $table => $label) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "   $label: $count\n";
    }

} catch (PDOException $e) {
    echo "❌ Erreur de connexion à la base de données: " . $e->getMessage() . "\n";
    echo "Vérifiez que :\n";
    echo "- MySQL est démarré\n";
    echo "- La base de données '$db_name' existe\n";
    echo "- L'utilisateur '$db_user' a les permissions\n";
    echo "- Le mot de passe est correct\n";
} catch (Exception $e) {
    echo "❌ Erreur lors du nettoyage: " . $e->getMessage() . "\n";
}

<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SUPPRESSION DES DONNÉES DE TEST COMMUNICATION ===\n";

try {
    // 1. Supprimer les messages de test
    echo "\n1. Suppression des messages de test:\n";
    
    $deletedMessages = Illuminate\Support\Facades\DB::table('messages')
        ->where('sujet', 'LIKE', 'Test Communication%')
        ->delete();
    echo "✅ $deletedMessages messages de test supprimés\n";
    
    // 2. Supprimer les notifications de test
    echo "\n2. Suppression des notifications de test:\n";
    
    $deletedNotifications = Illuminate\Support\Facades\DB::table('notifications')
        ->where('title', 'LIKE', 'Test Communication%')
        ->delete();
    echo "✅ $deletedNotifications notifications de test supprimées\n";
    
    // 3. Supprimer les abonnés newsletter de test
    echo "\n3. Suppression des abonnés newsletter de test:\n";
    
    $deletedSubscribers = Illuminate\Support\Facades\DB::table('newsletter_subscribers')
        ->where('email', 'LIKE', 'test%@example.com')
        ->delete();
    echo "✅ $deletedSubscribers abonnés de test supprimés\n";
    
    // 4. Vérifier que tout est supprimé
    echo "\n4. Vérification finale:\n";
    
    $remainingMessages = Illuminate\Support\Facades\DB::table('messages')->count();
    $remainingNotifications = Illuminate\Support\Facades\DB::table('notifications')->count();
    $remainingSubscribers = Illuminate\Support\Facades\DB::table('newsletter_subscribers')->count();
    
    echo "📧 Messages restants: $remainingMessages\n";
    echo "🔔 Notifications restantes: $remainingNotifications\n";
    echo "📬 Abonnés restants: $remainingSubscribers\n";
    
    if ($remainingMessages == 0 && $remainingNotifications == 0 && $remainingSubscribers == 0) {
        echo "\n✅ Toutes les données de test ont été supprimées avec succès!\n";
        echo "Le module de communication est maintenant propre et prêt pour la production.\n";
    } else {
        echo "\n⚠️  Il reste encore des données dans les tables.\n";
    }
    
    // 5. Statistiques finales
    echo "\n5. Statistiques finales du module communication:\n";
    echo "  - Messages: $remainingMessages\n";
    echo "  - Notifications: $remainingNotifications\n";
    echo "  - Abonnés newsletter: $remainingSubscribers\n";
    echo "  - Canaux disponibles: Email, SMS, Notification\n";
    echo "  - Fonctionnalités: Création, Lecture, Suppression, Statistiques\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la suppression: " . $e->getMessage() . "\n";
}


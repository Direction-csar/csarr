<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VÉRIFICATION DES DONNÉES PROFIL vs BASE DE DONNÉES ===\n";

try {
    // Récupérer l'utilisateur admin
    $admin = App\Models\User::where('email', 'admin@csar.sn')->first();
    
    if (!$admin) {
        echo "❌ Utilisateur admin non trouvé\n";
        exit;
    }
    
    echo "✅ Utilisateur: {$admin->name} (ID: {$admin->id})\n\n";
    
    // 1. Vérifier les MESSAGES
    echo "📨 MESSAGES DANS LA BASE DE DONNÉES:\n";
    $allMessages = Illuminate\Support\Facades\DB::table('messages')->get();
    echo "Total messages: " . $allMessages->count() . "\n";
    
    if ($allMessages->count() > 0) {
        foreach ($allMessages as $msg) {
            $luStatus = $msg->lu ? "✓ Lu" : "✗ Non lu";
            echo "  - Sujet: {$msg->sujet}\n";
            echo "    Expéditeur: {$msg->expediteur}\n";
            echo "    Statut: {$luStatus}\n";
            echo "    Date: {$msg->created_at}\n";
            echo "    User ID: {$msg->user_id}\n\n";
        }
    } else {
        echo "  Aucun message dans la base de données\n\n";
    }
    
    // 2. Vérifier les NOTIFICATIONS
    echo "🔔 NOTIFICATIONS DANS LA BASE DE DONNÉES:\n";
    $allNotifications = Illuminate\Support\Facades\DB::table('notifications')->get();
    echo "Total notifications: " . $allNotifications->count() . "\n";
    
    if ($allNotifications->count() > 0) {
        foreach ($allNotifications as $notif) {
            $readStatus = $notif->read ? "✓ Lu" : "✗ Non lu";
            echo "  - Titre: {$notif->title}\n";
            echo "    Message: {$notif->message}\n";
            echo "    Type: {$notif->type}\n";
            echo "    Statut: {$readStatus}\n";
            echo "    Date: {$notif->created_at}\n";
            echo "    User ID: {$notif->user_id}\n\n";
        }
    } else {
        echo "  Aucune notification dans la base de données\n\n";
    }
    
    // 3. Vérifier ce qui est affiché sur le profil
    echo "📊 STATISTIQUES CALCULÉES POUR LE PROFIL:\n";
    
    // Messages Reçus
    $messagesCount = Illuminate\Support\Facades\DB::table('messages')->count();
    echo "Messages Reçus (affichés sur profil): {$messagesCount}\n";
    
    // Notifications
    $notificationsCountUser = Illuminate\Support\Facades\DB::table('notifications')
        ->where('user_id', $admin->id)
        ->count();
    echo "Notifications pour admin (affichées sur profil): {$notificationsCountUser}\n";
    
    // Actions Effectuées (StockMovement)
    $actionsCount = Illuminate\Support\Facades\DB::table('stock_movements')
        ->where('created_by', $admin->id)
        ->count();
    echo "Actions Effectuées (mouvements de stock): {$actionsCount}\n";
    
    // Sessions Actives (toujours 1 pour session actuelle)
    echo "Sessions Actives: 1 (session actuelle)\n\n";
    
    // 4. Vérifier les notifications récentes affichées
    echo "🔔 NOTIFICATIONS RÉCENTES (affichées sur profil):\n";
    $recentNotifications = Illuminate\Support\Facades\DB::table('notifications')
        ->where('user_id', $admin->id)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    if ($recentNotifications->count() > 0) {
        foreach ($recentNotifications as $notif) {
            $readStatus = $notif->read ? "✓ Lu" : "✗ Non lu";
            echo "  - {$notif->title} ({$readStatus})\n";
        }
    } else {
        echo "  Aucune notification récente\n";
    }
    
    echo "\n";
    
    // 5. Vérification finale
    echo "=== CONCLUSION ===\n";
    if ($messagesCount > 0 || $notificationsCountUser > 0) {
        echo "✅ Les données affichées sur le profil proviennent de la BASE DE DONNÉES MySQL\n";
        echo "✅ Ce sont des DONNÉES RÉELLES, pas des données fictives\n";
        echo "✅ Vous pouvez les voir aussi sur http://localhost:8000/admin/communication\n";
    } else {
        echo "⚠️ Aucune donnée dans la base de données pour le moment\n";
        echo "ℹ️ Les données affichées sur le profil sont peut-être codées en dur (fictives)\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}


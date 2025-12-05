<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== RÉCUPÉRATION DES DONNÉES RÉELLES POUR LE PROFIL ===\n";

try {
    $user = App\Models\User::where('email', 'admin@csar.sn')->first();
    
    if ($user) {
        echo "✅ Utilisateur trouvé: {$user->name}\n";
        
        // Statistiques réelles
        $totalActions = Illuminate\Support\Facades\DB::table('stock_movements')->where('created_by', $user->id)->count();
        $totalMessages = Illuminate\Support\Facades\DB::table('messages')->count();
        $totalNotifications = Illuminate\Support\Facades\DB::table('notifications')->count();
        $unreadMessages = Illuminate\Support\Facades\DB::table('messages')->where('lu', false)->count();
        $unreadNotifications = Illuminate\Support\Facades\DB::table('notifications')->where('read', false)->count();
        
        echo "\n📊 Statistiques réelles:\n";
        echo "Actions effectuées: $totalActions\n";
        echo "Messages totaux: $totalMessages\n";
        echo "Messages non lus: $unreadMessages\n";
        echo "Notifications totales: $totalNotifications\n";
        echo "Notifications non lues: $unreadNotifications\n";
        
        // Activités récentes réelles
        echo "\n📝 Activités récentes:\n";
        $recentMovements = Illuminate\Support\Facades\DB::table('stock_movements')
            ->where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        foreach ($recentMovements as $movement) {
            echo "- {$movement->type} - {$movement->reference} ({$movement->created_at})\n";
        }
        
        // Messages récents
        echo "\n💬 Messages récents:\n";
        $recentMessages = Illuminate\Support\Facades\DB::table('messages')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        foreach ($recentMessages as $message) {
            echo "- {$message->sujet} par {$message->expediteur} ({$message->created_at})\n";
        }
        
        // Notifications récentes
        echo "\n🔔 Notifications récentes:\n";
        $recentNotifications = Illuminate\Support\Facades\DB::table('notifications')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        foreach ($recentNotifications as $notification) {
            echo "- {$notification->title} ({$notification->created_at})\n";
        }
        
    } else {
        echo "❌ Utilisateur admin non trouvé\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}


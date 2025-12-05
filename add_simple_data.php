<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== AJOUT DE DONNÉES SIMPLES POUR LE PROFIL ===\n";

try {
    $user = App\Models\User::where('email', 'admin@csar.sn')->first();
    
    if ($user) {
        echo "✅ Utilisateur trouvé: {$user->name}\n";
        
        // Ajouter quelques notifications réalistes
        $notifications = [
            [
                'user_id' => $user->id,
                'title' => 'Nouveau mouvement de stock',
                'message' => 'Un nouveau mouvement de stock a été enregistré pour le produit Riz.',
                'type' => 'stock',
                'read' => false,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2)
            ],
            [
                'user_id' => $user->id,
                'title' => 'Stock faible détecté',
                'message' => 'Le stock de Maïs est en dessous du seuil minimum.',
                'type' => 'alert',
                'read' => true,
                'read_at' => now()->subHours(1),
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(1)
            ],
            [
                'user_id' => $user->id,
                'title' => 'Système opérationnel',
                'message' => 'Tous les systèmes CSAR fonctionnent normalement.',
                'type' => 'system',
                'read' => false,
                'created_at' => now()->subMinutes(30),
                'updated_at' => now()->subMinutes(30)
            ]
        ];
        
        foreach ($notifications as $notification) {
            Illuminate\Support\Facades\DB::table('notifications')->insert($notification);
            echo "✅ Notification créée: {$notification['title']}\n";
        }
        
        // Ajouter quelques messages réalistes
        $messages = [
            [
                'sujet' => 'Demande d\'information sur les stocks',
                'contenu' => 'Bonjour, je souhaiterais avoir des informations sur les stocks disponibles.',
                'expediteur' => 'Directeur Régional',
                'email_expediteur' => 'directeur@csar.sn',
                'telephone_expediteur' => '+221 33 123 45 68',
                'lu' => false,
                'user_id' => $user->id,
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1)
            ],
            [
                'sujet' => 'Rapport mensuel de gestion',
                'contenu' => 'Veuillez trouver ci-joint le rapport mensuel de gestion des stocks.',
                'expediteur' => 'Responsable Logistique',
                'email_expediteur' => 'logistique@csar.sn',
                'telephone_expediteur' => '+221 33 123 45 69',
                'lu' => true,
                'lu_at' => now()->subMinutes(30),
                'user_id' => $user->id,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subMinutes(30)
            ]
        ];
        
        foreach ($messages as $message) {
            Illuminate\Support\Facades\DB::table('messages')->insert($message);
            echo "✅ Message créé: {$message['sujet']}\n";
        }
        
        // Vérifier les statistiques finales
        echo "\n📊 Statistiques finales:\n";
        $totalMessages = Illuminate\Support\Facades\DB::table('messages')->count();
        $totalNotifications = Illuminate\Support\Facades\DB::table('notifications')->where('user_id', $user->id)->count();
        $unreadMessages = Illuminate\Support\Facades\DB::table('messages')->where('lu', false)->count();
        $unreadNotifications = Illuminate\Support\Facades\DB::table('notifications')->where('read', false)->where('user_id', $user->id)->count();
        
        echo "Messages totaux: $totalMessages\n";
        echo "Messages non lus: $unreadMessages\n";
        echo "Notifications: $totalNotifications\n";
        echo "Notifications non lues: $unreadNotifications\n";
        
        echo "\n✅ Données réalistes ajoutées avec succès!\n";
        echo "Vous pouvez maintenant voir des données réelles sur la page de profil.\n";
        
    } else {
        echo "❌ Utilisateur admin non trouvé\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}


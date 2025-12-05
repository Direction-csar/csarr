<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== AJOUT DE DONNÉES RÉALISTES POUR LE PROFIL ===\n";

try {
    $user = App\Models\User::where('email', 'admin@csar.sn')->first();
    
    if ($user) {
        echo "✅ Utilisateur trouvé: {$user->name}\n";
        
        // Ajouter quelques mouvements de stock réalistes
        $stockMovements = [
            [
                'stock_id' => 1,
                'warehouse_id' => 1,
                'type' => 'in',
                'produit' => 'Riz',
                'unite' => 'kg',
                'prix_unitaire' => 500,
                'total' => 50000,
                'entrepot_id' => 1,
                'responsable' => 'Admin CSAR',
                'motif' => 'Réception de stock',
                'date_mouvement' => now()->subDays(2),
                'fournisseur' => 'Fournisseur Riz Sénégal',
                'numero_facture' => 'FAC-001',
                'quantity' => 100,
                'quantity_before' => 0,
                'quantity_after' => 100,
                'reason' => 'Réception',
                'reference' => 'REC-001',
                'reference_number' => 1,
                'created_by' => $user->id,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2)
            ],
            [
                'stock_id' => 2,
                'warehouse_id' => 1,
                'type' => 'out',
                'produit' => 'Maïs',
                'unite' => 'kg',
                'prix_unitaire' => 400,
                'total' => 20000,
                'entrepot_id' => 1,
                'responsable' => 'Admin CSAR',
                'motif' => 'Distribution',
                'date_mouvement' => now()->subDays(1),
                'destinataire' => 'Centre de distribution',
                'numero_bon' => 'BON-001',
                'quantity' => 50,
                'quantity_before' => 100,
                'quantity_after' => 50,
                'reason' => 'Distribution',
                'reference' => 'DIST-001',
                'reference_number' => 2,
                'created_by' => $user->id,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1)
            ],
            [
                'stock_id' => 3,
                'warehouse_id' => 1,
                'type' => 'in',
                'produit' => 'Blé',
                'unite' => 'kg',
                'prix_unitaire' => 600,
                'total' => 30000,
                'entrepot_id' => 1,
                'responsable' => 'Admin CSAR',
                'motif' => 'Réception urgente',
                'date_mouvement' => now()->subHours(3),
                'fournisseur' => 'Fournisseur Blé International',
                'numero_facture' => 'FAC-002',
                'quantity' => 50,
                'quantity_before' => 0,
                'quantity_after' => 50,
                'reason' => 'Réception urgente',
                'reference' => 'REC-002',
                'reference_number' => 3,
                'created_by' => $user->id,
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3)
            ]
        ];
        
        foreach ($stockMovements as $movement) {
            Illuminate\Support\Facades\DB::table('stock_movements')->insert($movement);
            echo "✅ Mouvement créé: {$movement['type']} - {$movement['reference']}\n";
        }
        
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
        $totalActions = Illuminate\Support\Facades\DB::table('stock_movements')->where('created_by', $user->id)->count();
        $totalMessages = Illuminate\Support\Facades\DB::table('messages')->count();
        $totalNotifications = Illuminate\Support\Facades\DB::table('notifications')->where('user_id', $user->id)->count();
        
        echo "Actions effectuées: $totalActions\n";
        echo "Messages totaux: $totalMessages\n";
        echo "Notifications: $totalNotifications\n";
        
        echo "\n✅ Données réalistes ajoutées avec succès!\n";
        echo "Vous pouvez maintenant voir des données réelles sur la page de profil.\n";
        
    } else {
        echo "❌ Utilisateur admin non trouvé\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

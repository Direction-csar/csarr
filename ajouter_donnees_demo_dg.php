<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Ajout de données de démonstration pour l'interface DG...\n\n";

try {
    // 1. Ajouter des demandes de démonstration
    echo "📋 Ajout de demandes de démonstration...\n";
    
    $demandes = [
        [
            'name' => 'Fatou Diop',
            'email' => 'fatou.diop@email.com',
            'phone' => '+221 77 123 45 67',
            'subject' => 'Demande d\'aide alimentaire d\'urgence',
            'description' => 'Famille de 6 personnes en situation difficile, besoin d\'aide alimentaire urgente.',
            'type' => 'aide_alimentaire',
            'status' => 'pending',
            'address' => 'Dakar, Sénégal',
            'full_name' => 'Fatou Diop',
            'region' => 'Dakar',
            'urgency' => 'high',
            'preferred_contact' => 'phone',
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2)
        ],
        [
            'name' => 'Moussa Fall',
            'email' => 'moussa.fall@email.com',
            'phone' => '+221 78 234 56 78',
            'subject' => 'Demande de soutien alimentaire',
            'description' => 'Personne âgée isolée, besoin de soutien alimentaire régulier.',
            'type' => 'aide_alimentaire',
            'status' => 'approved',
            'address' => 'Thiès, Sénégal',
            'full_name' => 'Moussa Fall',
            'region' => 'Thiès',
            'urgency' => 'medium',
            'preferred_contact' => 'email',
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(1)
        ],
        [
            'name' => 'Aminata Ba',
            'email' => 'aminata.ba@email.com',
            'phone' => '+221 76 345 67 89',
            'subject' => 'Demande d\'aide pour famille nombreuse',
            'description' => 'Famille de 8 personnes, père au chômage, besoin d\'aide alimentaire.',
            'type' => 'aide_urgence',
            'status' => 'pending',
            'address' => 'Kaolack, Sénégal',
            'full_name' => 'Aminata Ba',
            'region' => 'Kaolack',
            'urgency' => 'high',
            'preferred_contact' => 'phone',
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1)
        ],
        [
            'name' => 'Ibrahima Sarr',
            'email' => 'ibrahima.sarr@email.com',
            'phone' => '+221 77 456 78 90',
            'subject' => 'Demande de soutien nutritionnel',
            'description' => 'Enfants malnutris, besoin de compléments nutritionnels.',
            'type' => 'aide_alimentaire',
            'status' => 'approved',
            'address' => 'Saint-Louis, Sénégal',
            'full_name' => 'Ibrahima Sarr',
            'region' => 'Saint-Louis',
            'urgency' => 'high',
            'preferred_contact' => 'phone',
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'created_at' => now()->subDays(7),
            'updated_at' => now()->subDays(3)
        ],
        [
            'name' => 'Mariama Diallo',
            'email' => 'mariama.diallo@email.com',
            'phone' => '+221 78 567 89 01',
            'subject' => 'Demande d\'aide alimentaire',
            'description' => 'Mère célibataire avec 3 enfants, situation financière difficile.',
            'type' => 'aide_alimentaire',
            'status' => 'rejected',
            'address' => 'Ziguinchor, Sénégal',
            'full_name' => 'Mariama Diallo',
            'region' => 'Ziguinchor',
            'urgency' => 'medium',
            'preferred_contact' => 'email',
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(8)
        ]
    ];

    foreach ($demandes as $demande) {
        DB::table('public_requests')->insert($demande);
    }
    echo "✅ " . count($demandes) . " demandes ajoutées\n\n";

    // 2. Ajouter des entrepôts de démonstration
    echo "🏢 Ajout d'entrepôts de démonstration...\n";
    
    $entrepots = [
        [
            'name' => 'Entrepôt Principal CSAR',
            'address' => 'Dakar, Sénégal',
            'region' => 'Dakar',
            'city' => 'Dakar',
            'capacity' => 5000,
            'is_active' => true,
            'description' => 'Entrepôt principal pour la distribution nationale',
            'phone' => '+221 33 123 45 67',
            'email' => 'principal@csar.sn',
            'status' => 'active',
            'created_at' => now()->subMonths(6),
            'updated_at' => now()->subDays(1)
        ],
        [
            'name' => 'Entrepôt Régional Thiès',
            'address' => 'Thiès, Sénégal',
            'region' => 'Thiès',
            'city' => 'Thiès',
            'capacity' => 2000,
            'is_active' => true,
            'description' => 'Entrepôt régional pour la zone de Thiès',
            'phone' => '+221 33 234 56 78',
            'email' => 'thies@csar.sn',
            'status' => 'active',
            'created_at' => now()->subMonths(4),
            'updated_at' => now()->subDays(2)
        ],
        [
            'name' => 'Entrepôt Kaolack',
            'address' => 'Kaolack, Sénégal',
            'region' => 'Kaolack',
            'city' => 'Kaolack',
            'capacity' => 1500,
            'is_active' => true,
            'description' => 'Entrepôt pour la région de Kaolack',
            'phone' => '+221 33 345 67 89',
            'email' => 'kaolack@csar.sn',
            'status' => 'active',
            'created_at' => now()->subMonths(3),
            'updated_at' => now()->subDays(3)
        ]
    ];

    foreach ($entrepots as $entrepot) {
        DB::table('warehouses')->insert($entrepot);
    }
    echo "✅ " . count($entrepots) . " entrepôts ajoutés\n\n";

    // 3. Ajouter des stocks de démonstration
    echo "📦 Ajout de stocks de démonstration...\n";
    
    $stocks = [
        [
            'stock_id' => 1,
            'warehouse_id' => 1,
            'type' => 'in',
            'quantity' => 1500,
            'quantity_before' => 0,
            'quantity_after' => 1500,
            'reason' => 'Réception de stock',
            'reference' => 'REC-001',
            'created_by' => 1,
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(1)
        ],
        [
            'stock_id' => 2,
            'warehouse_id' => 1,
            'type' => 'in',
            'quantity' => 800,
            'quantity_before' => 0,
            'quantity_after' => 800,
            'reason' => 'Réception de stock',
            'reference' => 'REC-002',
            'created_by' => 1,
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(1)
        ],
        [
            'stock_id' => 3,
            'warehouse_id' => 2,
            'type' => 'in',
            'quantity' => 25,
            'quantity_before' => 0,
            'quantity_after' => 25,
            'reason' => 'Réception de stock',
            'reference' => 'REC-003',
            'created_by' => 1,
            'created_at' => now()->subDays(7),
            'updated_at' => now()->subDays(2)
        ],
        [
            'stock_id' => 4,
            'warehouse_id' => 1,
            'type' => 'in',
            'quantity' => 1200,
            'quantity_before' => 0,
            'quantity_after' => 1200,
            'reason' => 'Réception de stock',
            'reference' => 'REC-004',
            'created_by' => 1,
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subDays(1)
        ],
        [
            'stock_id' => 5,
            'warehouse_id' => 3,
            'type' => 'out',
            'quantity' => 100,
            'quantity_before' => 100,
            'quantity_after' => 0,
            'reason' => 'Distribution',
            'reference' => 'DIST-001',
            'created_by' => 1,
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(5)
        ]
    ];

    foreach ($stocks as $stock) {
        DB::table('stock_movements')->insert($stock);
    }
    echo "✅ " . count($stocks) . " articles en stock ajoutés\n\n";

    // 4. Ajouter du personnel de démonstration
    echo "👥 Ajout de personnel de démonstration...\n";
    
    $personnel = [
        [
            'prenoms_nom' => 'Mamadou Ndiaye',
            'email' => 'mamadou.ndiaye@csar.sn',
            'contact_telephonique' => '+221 77 111 22 33',
            'poste_actuel' => 'Directeur Régional',
            'direction_service' => 'Direction',
            'statut' => 'actif',
            'localisation_region' => 'Dakar',
            'date_recrutement_csar' => now()->subYears(3),
            'created_at' => now()->subYears(3),
            'updated_at' => now()->subDays(1)
        ],
        [
            'prenoms_nom' => 'Aïcha Diagne',
            'email' => 'aicha.diagne@csar.sn',
            'contact_telephonique' => '+221 78 222 33 44',
            'poste_actuel' => 'Responsable Logistique',
            'direction_service' => 'Logistique',
            'statut' => 'actif',
            'localisation_region' => 'Dakar',
            'date_recrutement_csar' => now()->subYears(2),
            'created_at' => now()->subYears(2),
            'updated_at' => now()->subDays(2)
        ],
        [
            'prenoms_nom' => 'Ousmane Fall',
            'email' => 'ousmane.fall@csar.sn',
            'contact_telephonique' => '+221 76 333 44 55',
            'poste_actuel' => 'Agent de Stock',
            'direction_service' => 'Opérations',
            'statut' => 'actif',
            'localisation_region' => 'Thiès',
            'date_recrutement_csar' => now()->subMonths(8),
            'created_at' => now()->subMonths(8),
            'updated_at' => now()->subDays(3)
        ],
        [
            'prenoms_nom' => 'Khadija Ba',
            'email' => 'khadija.ba@csar.sn',
            'contact_telephonique' => '+221 77 444 55 66',
            'poste_actuel' => 'Comptable',
            'direction_service' => 'Administration',
            'statut' => 'en_conge',
            'localisation_region' => 'Dakar',
            'date_recrutement_csar' => now()->subYears(1),
            'created_at' => now()->subYears(1),
            'updated_at' => now()->subDays(5)
        ],
        [
            'prenoms_nom' => 'Cheikh Sarr',
            'email' => 'cheikh.sarr@csar.sn',
            'contact_telephonique' => '+221 78 555 66 77',
            'poste_actuel' => 'Chauffeur',
            'direction_service' => 'Logistique',
            'statut' => 'actif',
            'localisation_region' => 'Kaolack',
            'date_recrutement_csar' => now()->subMonths(6),
            'created_at' => now()->subMonths(6),
            'updated_at' => now()->subDays(1)
        ]
    ];

    foreach ($personnel as $employe) {
        DB::table('personnel')->insert($employe);
    }
    echo "✅ " . count($personnel) . " employés ajoutés\n\n";

    // 5. Ajouter des utilisateurs de démonstration
    echo "👤 Ajout d'utilisateurs de démonstration...\n";
    
    $utilisateurs = [
        [
            'name' => 'Admin CSAR',
            'email' => 'admin@csar.sn',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
            'created_at' => now()->subMonths(12),
            'updated_at' => now()->subDays(1)
        ],
        [
            'name' => 'Responsable Régional',
            'email' => 'responsable@csar.sn',
            'password' => bcrypt('password'),
            'role' => 'responsable',
            'is_active' => true,
            'created_at' => now()->subMonths(8),
            'updated_at' => now()->subDays(2)
        ],
        [
            'name' => 'Agent Terrain',
            'email' => 'agent@csar.sn',
            'password' => bcrypt('password'),
            'role' => 'agent',
            'is_active' => true,
            'created_at' => now()->subMonths(6),
            'updated_at' => now()->subDays(3)
        ]
    ];

    foreach ($utilisateurs as $utilisateur) {
        DB::table('users')->insert($utilisateur);
    }
    echo "✅ " . count($utilisateurs) . " utilisateurs ajoutés\n\n";

    echo "🎉 Données de démonstration ajoutées avec succès !\n";
    echo "📊 Résumé :\n";
    echo "   - " . count($demandes) . " demandes\n";
    echo "   - " . count($entrepots) . " entrepôts\n";
    echo "   - " . count($stocks) . " articles en stock\n";
    echo "   - " . count($personnel) . " employés\n";
    echo "   - " . count($utilisateurs) . " utilisateurs\n\n";
    
    echo "🚀 L'interface DG devrait maintenant afficher des données réalistes !\n";

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "📍 Fichier : " . $e->getFile() . " ligne " . $e->getLine() . "\n";
}

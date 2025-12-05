<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "=== AJOUT DE DONNÉES DE DÉMONSTRATION DG ===\n\n";

try {
    // 1. Ajout de personnel de démonstration
    echo "1. Ajout du personnel de démonstration...\n";
    
    $personnelData = [
        [
            'prenoms_nom' => 'Mamadou Diallo',
            'date_naissance' => '1975-03-15',
            'lieu_naissance' => 'Dakar',
            'tranche_age' => '46-55',
            'nationalite' => 'Sénégalaise',
            'numero_cni' => '1234567890',
            'sexe' => 'Masculin',
            'situation_matrimoniale' => 'Marie',
            'nombre_enfants' => 3,
            'contact_telephonique' => '+221 77 123 4567',
            'email' => 'mamadou.diallo@csar.sn',
            'groupe_sanguin' => 'O+',
            'adresse_complete' => 'Parcelles Assainies, Dakar',
            'matricule' => 'CSAR001',
            'date_recrutement_csar' => '2020-01-15',
            'date_prise_service_csar' => '2020-01-15',
            'statut' => 'Fonctionnaire',
            'poste_actuel' => 'Directeur Général',
            'direction_service' => 'Direction Generale',
            'localisation_region' => 'Dakar',
            'diplome_academique' => 'Master',
            'interet_nouvelles_responsabilites' => 'Oui',
            'taille_vetements' => 'L',
            'contact_urgence_nom' => 'Aïcha Diallo',
            'contact_urgence_telephone' => '+221 77 111 1111',
            'contact_urgence_lien_parente' => 'Épouse',
            'statut_validation' => 'Valide',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'prenoms_nom' => 'Fatou Sarr',
            'date_naissance' => '1980-07-22',
            'lieu_naissance' => 'Thiès',
            'tranche_age' => '36-45',
            'nationalite' => 'Sénégalaise',
            'numero_cni' => '1234567891',
            'sexe' => 'Feminin',
            'situation_matrimoniale' => 'Marie',
            'nombre_enfants' => 2,
            'contact_telephonique' => '+221 77 234 5678',
            'email' => 'fatou.sarr@csar.sn',
            'groupe_sanguin' => 'A+',
            'adresse_complete' => 'Plateau, Dakar',
            'matricule' => 'CSAR002',
            'date_recrutement_csar' => '2020-03-10',
            'date_prise_service_csar' => '2020-03-10',
            'statut' => 'Fonctionnaire',
            'poste_actuel' => 'Directrice Administrative',
            'direction_service' => 'Secretariat general',
            'localisation_region' => 'Dakar',
            'diplome_academique' => 'Master',
            'interet_nouvelles_responsabilites' => 'Oui',
            'taille_vetements' => 'M',
            'contact_urgence_nom' => 'Moussa Sarr',
            'contact_urgence_telephone' => '+221 77 222 2222',
            'contact_urgence_lien_parente' => 'Époux',
            'statut_validation' => 'Valide',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'prenoms_nom' => 'Ibrahima Fall',
            'date_naissance' => '1985-11-08',
            'lieu_naissance' => 'Kaolack',
            'tranche_age' => '36-45',
            'nationalite' => 'Sénégalaise',
            'numero_cni' => '1234567892',
            'sexe' => 'Masculin',
            'situation_matrimoniale' => 'Celibataire',
            'nombre_enfants' => 0,
            'contact_telephonique' => '+221 77 345 6789',
            'email' => 'ibrahima.fall@csar.sn',
            'groupe_sanguin' => 'B+',
            'adresse_complete' => 'Guédiawaye, Dakar',
            'matricule' => 'CSAR003',
            'date_recrutement_csar' => '2021-06-20',
            'date_prise_service_csar' => '2021-06-20',
            'statut' => 'Contractuel',
            'poste_actuel' => 'Responsable Logistique',
            'direction_service' => 'DTL',
            'localisation_region' => 'Dakar',
            'diplome_academique' => 'Licence',
            'interet_nouvelles_responsabilites' => 'Oui',
            'taille_vetements' => 'XL',
            'contact_urgence_nom' => 'Mariama Fall',
            'contact_urgence_telephone' => '+221 77 333 3333',
            'contact_urgence_lien_parente' => 'Sœur',
            'statut_validation' => 'Valide',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'prenoms_nom' => 'Aminata Diop',
            'date_naissance' => '1990-04-12',
            'lieu_naissance' => 'Saint-Louis',
            'tranche_age' => '26-35',
            'nationalite' => 'Sénégalaise',
            'numero_cni' => '1234567893',
            'sexe' => 'Feminin',
            'situation_matrimoniale' => 'Marie',
            'nombre_enfants' => 1,
            'contact_telephonique' => '+221 77 456 7890',
            'email' => 'aminata.diop@csar.sn',
            'groupe_sanguin' => 'O-',
            'adresse_complete' => 'Pikine, Dakar',
            'matricule' => 'CSAR004',
            'date_recrutement_csar' => '2022-02-15',
            'date_prise_service_csar' => '2022-02-15',
            'statut' => 'Contractuel',
            'poste_actuel' => 'Agent de Stock',
            'direction_service' => 'DTL',
            'localisation_region' => 'Dakar',
            'diplome_academique' => 'Baccalaureat',
            'interet_nouvelles_responsabilites' => 'Oui',
            'taille_vetements' => 'S',
            'contact_urgence_nom' => 'Cheikh Diop',
            'contact_urgence_telephone' => '+221 77 444 4444',
            'contact_urgence_lien_parente' => 'Époux',
            'statut_validation' => 'Valide',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'prenoms_nom' => 'Cheikh Ndiaye',
            'date_naissance' => '1988-09-25',
            'lieu_naissance' => 'Diourbel',
            'tranche_age' => '26-35',
            'nationalite' => 'Sénégalaise',
            'numero_cni' => '1234567894',
            'sexe' => 'Masculin',
            'situation_matrimoniale' => 'Marie',
            'nombre_enfants' => 2,
            'contact_telephonique' => '+221 77 567 8901',
            'email' => 'cheikh.ndiaye@csar.sn',
            'groupe_sanguin' => 'A-',
            'adresse_complete' => 'Rufisque, Dakar',
            'matricule' => 'CSAR005',
            'date_recrutement_csar' => '2021-09-05',
            'date_prise_service_csar' => '2021-09-05',
            'statut' => 'Contractuel',
            'poste_actuel' => 'Chauffeur',
            'direction_service' => 'DTL',
            'localisation_region' => 'Dakar',
            'diplome_academique' => 'BFEM',
            'interet_nouvelles_responsabilites' => 'Neutre',
            'taille_vetements' => 'L',
            'contact_urgence_nom' => 'Khadija Ndiaye',
            'contact_urgence_telephone' => '+221 77 555 5555',
            'contact_urgence_lien_parente' => 'Épouse',
            'statut_validation' => 'Valide',
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];
    
    // Vider la table personnel (avec désactivation des contraintes)
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('personnel')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
    // Insérer les données
    foreach ($personnelData as $person) {
        DB::table('personnel')->insert($person);
    }
    
    echo "   ✅ " . count($personnelData) . " employés ajoutés\n";
    
    // 2. Ajout d'entrepôts de démonstration
    echo "\n2. Ajout d'entrepôts de démonstration...\n";
    
    $warehousesData = [
        [
            'name' => 'Entrepôt Principal CSAR',
            'description' => 'Entrepôt principal de la CSAR à Dakar',
            'address' => 'Route de Rufisque, Dakar',
            'latitude' => 14.7167,
            'longitude' => -17.4677,
            'region' => 'Dakar',
            'city' => 'Dakar',
            'phone' => '+221 33 123 4567',
            'email' => 'entrepot.principal@csar.sn',
            'is_active' => 1,
            'capacity' => 5000,
            'current_stock' => 3200,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'Entrepôt Régional Thiès',
            'description' => 'Entrepôt régional de Thiès',
            'address' => 'Avenue Léopold Sédar Senghor, Thiès',
            'latitude' => 14.7833,
            'longitude' => -16.9167,
            'region' => 'Thiès',
            'city' => 'Thiès',
            'phone' => '+221 33 234 5678',
            'email' => 'entrepot.thies@csar.sn',
            'is_active' => 1,
            'capacity' => 2000,
            'current_stock' => 1200,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'Entrepôt Kaolack',
            'description' => 'Entrepôt régional de Kaolack',
            'address' => 'Quartier Médina, Kaolack',
            'latitude' => 14.1500,
            'longitude' => -16.0833,
            'region' => 'Kaolack',
            'city' => 'Kaolack',
            'phone' => '+221 33 345 6789',
            'email' => 'entrepot.kaolack@csar.sn',
            'is_active' => 1,
            'capacity' => 1500,
            'current_stock' => 800,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];
    
    // Vider la table warehouses (avec désactivation des contraintes)
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('warehouses')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
    // Insérer les données
    foreach ($warehousesData as $warehouse) {
        DB::table('warehouses')->insert($warehouse);
    }
    
    echo "   ✅ " . count($warehousesData) . " entrepôts ajoutés\n";
    
    // 3. Ajout de demandes de démonstration
    echo "\n3. Ajout de demandes de démonstration...\n";
    
    $requestsData = [
        [
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'full_name' => 'Aïcha Diagne',
            'email' => 'aicha.diagne@email.com',
            'phone' => '+221 77 111 2222',
            'subject' => 'Demande d\'aide alimentaire',
            'description' => 'Famille de 6 personnes en situation difficile, besoin urgent d\'aide alimentaire',
            'type' => 'aide_alimentaire',
            'status' => 'en_attente',
            'address' => 'Parcelles Assainies, Dakar',
            'region' => 'Dakar',
            'urgency' => 'haute',
            'preferred_contact' => 'telephone',
            'request_date' => now()->subDays(2),
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2)
        ],
        [
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'full_name' => 'Moussa Camara',
            'email' => 'moussa.camara@email.com',
            'phone' => '+221 77 333 4444',
            'subject' => 'Demande d\'aide médicale',
            'description' => 'Besoin d\'aide pour les frais médicaux d\'un enfant malade',
            'type' => 'aide_medicale',
            'status' => 'approuvee',
            'address' => 'Guédiawaye, Dakar',
            'region' => 'Dakar',
            'urgency' => 'moyenne',
            'preferred_contact' => 'email',
            'request_date' => now()->subDays(5),
            'admin_comment' => 'Demande approuvée, aide accordée',
            'assigned_to' => 1,
            'processed_date' => now()->subDays(1),
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(1)
        ],
        [
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'full_name' => 'Khadija Ndiaye',
            'email' => 'khadija.ndiaye@email.com',
            'phone' => '+221 77 555 6666',
            'subject' => 'Demande de transport',
            'description' => 'Besoin d\'aide pour le transport vers l\'hôpital',
            'type' => 'aide_logistique',
            'status' => 'en_attente',
            'address' => 'Pikine, Dakar',
            'region' => 'Dakar',
            'urgency' => 'haute',
            'preferred_contact' => 'telephone',
            'request_date' => now()->subHours(6),
            'created_at' => now()->subHours(6),
            'updated_at' => now()->subHours(6)
        ],
        [
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'full_name' => 'Samba Sow',
            'email' => 'samba.sow@email.com',
            'phone' => '+221 77 777 8888',
            'subject' => 'Demande d\'aide alimentaire',
            'description' => 'Famille nombreuse, situation économique difficile',
            'type' => 'aide_alimentaire',
            'status' => 'rejetee',
            'address' => 'Thiès',
            'region' => 'Thiès',
            'urgency' => 'moyenne',
            'preferred_contact' => 'email',
            'request_date' => now()->subDays(7),
            'admin_comment' => 'Demande rejetée, critères non remplis',
            'assigned_to' => 1,
            'processed_date' => now()->subDays(3),
            'created_at' => now()->subDays(7),
            'updated_at' => now()->subDays(3)
        ],
        [
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'full_name' => 'Rokhaya Mbaye',
            'email' => 'rokhaya.mbaye@email.com',
            'phone' => '+221 77 999 0000',
            'subject' => 'Demande d\'aide médicale',
            'description' => 'Urgence médicale, besoin d\'aide financière',
            'type' => 'aide_medicale',
            'status' => 'en_attente',
            'address' => 'Kaolack',
            'region' => 'Kaolack',
            'urgency' => 'haute',
            'preferred_contact' => 'telephone',
            'request_date' => now()->subHours(2),
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2)
        ]
    ];
    
    // Vider la table public_requests (avec désactivation des contraintes)
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('public_requests')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
    // Insérer les données
    foreach ($requestsData as $request) {
        DB::table('public_requests')->insert($request);
    }
    
    echo "   ✅ " . count($requestsData) . " demandes ajoutées\n";
    
    // 4. Ajout de stocks de démonstration
    echo "\n4. Ajout de stocks de démonstration...\n";
    
    $stocksData = [
        [
            'warehouse_id' => 1,
            'stock_type_id' => 1,
            'item_name' => 'Riz',
            'description' => 'Riz de qualité supérieure',
            'quantity' => 500.00,
            'min_quantity' => 100.00,
            'max_quantity' => 1000.00,
            'expiry_date' => '2026-12-31',
            'batch_number' => 'RIZ001',
            'supplier' => 'Fournisseur Riz Sénégal',
            'unit_price' => 500.00,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'warehouse_id' => 1,
            'stock_type_id' => 1,
            'item_name' => 'Huile de cuisine',
            'description' => 'Huile de tournesol 1L',
            'quantity' => 200.00,
            'min_quantity' => 50.00,
            'max_quantity' => 500.00,
            'expiry_date' => '2026-06-30',
            'batch_number' => 'HUILE001',
            'supplier' => 'Huilerie du Sénégal',
            'unit_price' => 1200.00,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'warehouse_id' => 1,
            'stock_type_id' => 1,
            'item_name' => 'Sucre',
            'description' => 'Sucre en poudre',
            'quantity' => 300.00,
            'min_quantity' => 75.00,
            'max_quantity' => 600.00,
            'expiry_date' => '2027-03-31',
            'batch_number' => 'SUCRE001',
            'supplier' => 'Compagnie Sucrière Sénégalaise',
            'unit_price' => 400.00,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'warehouse_id' => 1,
            'stock_type_id' => 1,
            'item_name' => 'Lait en poudre',
            'description' => 'Lait en poudre pour enfants',
            'quantity' => 150.00,
            'min_quantity' => 30.00,
            'max_quantity' => 300.00,
            'expiry_date' => '2026-09-30',
            'batch_number' => 'LAIT001',
            'supplier' => 'Laitière du Sénégal',
            'unit_price' => 2500.00,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'warehouse_id' => 2,
            'stock_type_id' => 1,
            'item_name' => 'Farine',
            'description' => 'Farine de blé',
            'quantity' => 400.00,
            'min_quantity' => 100.00,
            'max_quantity' => 800.00,
            'expiry_date' => '2026-11-30',
            'batch_number' => 'FARINE001',
            'supplier' => 'Minoterie de Thiès',
            'unit_price' => 350.00,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];
    
    // Vider la table stocks (avec désactivation des contraintes)
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('stocks')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
    // Insérer les données
    foreach ($stocksData as $stock) {
        DB::table('stocks')->insert($stock);
    }
    
    echo "   ✅ " . count($stocksData) . " articles en stock ajoutés\n";
    
    // 5. Mise à jour des entrepôts avec les IDs corrects
    echo "\n5. Mise à jour des entrepôts...\n";
    
    echo "   ✅ Personnel et entrepôts configurés\n";
    
    // 6. Vérification finale
    echo "\n6. Vérification finale...\n";
    
    $personnelCount = DB::table('personnel')->count();
    $warehousesCount = DB::table('warehouses')->count();
    $requestsCount = DB::table('public_requests')->count();
    $stocksCount = DB::table('stocks')->count();
    
    echo "   📊 Personnel: $personnelCount enregistrements\n";
    echo "   📊 Entrepôts: $warehousesCount enregistrements\n";
    echo "   📊 Demandes: $requestsCount enregistrements\n";
    echo "   📊 Stocks: $stocksCount enregistrements\n";
    
    echo "\n🎉 Données de démonstration ajoutées avec succès !\n";
    echo "\n📋 Vous pouvez maintenant tester les fonctionnalités DG:\n";
    echo "   - Dashboard: http://localhost:8000/dg\n";
    echo "   - Personnel: http://localhost:8000/dg/personnel\n";
    echo "   - Rapports: http://localhost:8000/dg/reports\n";
    echo "   - Demandes: http://localhost:8000/dg/demandes\n";
    echo "   - Entrepôts: http://localhost:8000/dg/warehouses\n";
    echo "   - Stocks: http://localhost:8000/dg/stocks\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DE L'AJOUT DE DONNÉES ===\n";

<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔄 Mise à jour/Création des comptes par défaut...\n\n";

try {
    DB::beginTransaction();

    // 1) Assurer l'existence des rôles
    $roleNames = [
        'admin' => 'Administrateur',
        'dg' => 'Directeur Général',
        'drh' => 'Directeur des Ressources Humaines',
        'responsable' => 'Responsable Entrepôt',
        'agent' => 'Agent',
    ];

    $roleIds = [];
    foreach ($roleNames as $name => $display) {
        $existing = DB::table('roles')->where('name', $name)->first();
        if (!$existing) {
            $id = DB::table('roles')->insertGetId([
                'name' => $name,
                'display_name' => $display,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $roleIds[$name] = $id;
            echo "✅ Rôle créé: {$name} (#{$id})\n";
        } else {
            $roleIds[$name] = $existing->id;
        }
    }

    // 2) Assurer l'existence d'un entrepôt par défaut
    $warehouse = DB::table('warehouses')->first();
    if (!$warehouse) {
        $warehouseId = DB::table('warehouses')->insertGetId([
            'name' => 'Entrepôt Principal',
            'description' => 'Entrepôt principal par défaut',
            'address' => 'Adresse par défaut',
            'latitude' => null,
            'longitude' => null,
            'region' => null,
            'city' => null,
            'phone' => null,
            'email' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✅ Entrepôt créé (#{$warehouseId})\n";
    } else {
        $warehouseId = $warehouse->id;
    }

    // 3) Upsert/Update des utilisateurs
    $users = [
        ['email' => 'admin@csar.sn', 'name' => 'Admin Principal', 'role' => 'admin'],
        ['email' => 'dg@csar.sn', 'name' => 'Directeur Général', 'role' => 'dg'],
        ['email' => 'drh@csar.sn', 'name' => 'Directeur RH', 'role' => 'drh'],
        ['email' => 'responsable@csar.sn', 'name' => 'Responsable Entrepôt', 'role' => 'responsable'],
        ['email' => 'agent@csar.sn', 'name' => 'Agent Test', 'role' => 'agent'],
        // Compatibilité README
        ['email' => 'entrepot@csar.sn', 'name' => 'Responsable Entrepôt', 'role' => 'responsable'],
    ];

    foreach ($users as $u) {
        $existing = DB::table('users')->where('email', $u['email'])->first();
        $data = [
            'name' => $u['name'],
            'role' => $u['role'],
            'password' => Hash::make('password'),
            'is_active' => true,
            'role_id' => $roleIds[$u['role']] ?? null,
            'warehouse_id' => $warehouseId,
            'updated_at' => now(),
        ];
        if ($existing) {
            DB::table('users')->where('id', $existing->id)->update($data);
            echo "🔁 Utilisateur mis à jour: {$u['email']} / password\n";
        } else {
            $data['email'] = $u['email'];
            $data['created_at'] = now();
            DB::table('users')->insert($data);
            echo "🆕 Utilisateur créé: {$u['email']} / password\n";
        }

        // Assurer un profil 'personnel' minimal (pour vues RH/Profil)
        $personnel = DB::table('personnel')->where('email', $u['email'])->first();
        if (!$personnel) {
            // Générer un matricule unique simple (fallback si modèle indisponible)
            $suffix = str_pad((string)random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            $matricule = 'CSAR-' . date('Y') . '-' . $suffix;

            // Mapper un poste par rôle
            $poste = match ($u['role']) {
                'admin' => 'Administrateur',
                'dg' => 'Directeur Général',
                'responsable' => 'Responsable Entrepôt',
                default => 'Agent',
            };

            DB::table('personnel')->insert([
                // Identité
                'prenoms_nom' => $u['name'],
                'date_naissance' => '1990-01-01',
                'lieu_naissance' => 'Dakar',
                'tranche_age' => '26-35',
                'nationalite' => 'Senegalaise',
                'numero_cni' => 'CNI' . random_int(100000, 999999),
                'sexe' => 'Masculin',
                'situation_matrimoniale' => 'Celibataire',
                'nombre_enfants' => 0,
                'contact_telephonique' => '770000000',
                'email' => $u['email'],
                'groupe_sanguin' => 'O+',
                'adresse_complete' => 'Adresse par défaut',

                // Situation administrative
                'matricule' => $matricule,
                'date_recrutement_csar' => date('Y-m-d'),
                'date_prise_service_csar' => date('Y-m-d'),
                'statut' => 'Contractuel',
                'poste_actuel' => $poste,
                'direction_service' => 'Direction Generale',
                'localisation_region' => 'Dakar',

                // Parcours/compétences (facultatifs)
                'dernier_poste_avant_csar' => null,
                'formations_professionnelles' => null,
                'diplome_academique' => 'Licence',
                'autres_diplomes_certifications' => null,
                'logiciels_maitrises' => json_encode(['Word', 'Excel']),
                'langues_parlees' => json_encode(['Français']),
                'autres_aptitudes' => null,

                // Aspirations
                'aspirations_professionnelles' => null,
                'interet_nouvelles_responsabilites' => 'Oui',

                // Photo/Taille
                'photo_personnelle' => null,
                'taille_vetements' => 'M',

                // Contact d'urgence
                'contact_urgence_nom' => 'Contact Urgence',
                'contact_urgence_telephone' => '770000001',
                'contact_urgence_lien_parente' => 'Parent',

                // Validation
                'statut_validation' => 'En attente',
                'commentaire_validation' => null,
                'valide_par' => null,
                'date_validation' => null,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "🧩 Profil personnel créé pour: {$u['email']}\n";
        }
    }

    DB::commit();
    echo "\n🎉 Comptes par défaut prêts.\n";
    echo "\nIdentifiants:\n";
    echo "- Admin: admin@csar.sn / password\n";
    echo "- DG: dg@csar.sn / password\n";
    echo "- Responsable: responsable@csar.sn ou entrepot@csar.sn / password\n";
    echo "- Agent: agent@csar.sn / password\n\n";
} catch (\Throwable $e) {
    DB::rollBack();
    echo "❌ Erreur: ".$e->getMessage()."\n";
}

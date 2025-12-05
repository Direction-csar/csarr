<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "🚀 Création des utilisateurs CSAR Platform...\n\n";

    // 1. Créer les rôles
    $adminRoleId = DB::table('roles')->insertGetId([
        'name' => 'admin',
        'display_name' => 'Administrateur',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    $dgRoleId = DB::table('roles')->insertGetId([
        'name' => 'dg',
        'display_name' => 'Directeur Général',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    $responsableRoleId = DB::table('roles')->insertGetId([
        'name' => 'responsable',
        'display_name' => 'Responsable Entrepôt',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    $agentRoleId = DB::table('roles')->insertGetId([
        'name' => 'agent',
        'display_name' => 'Agent',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // 2. Créer l'entrepôt principal
    $warehouseId = DB::table('warehouses')->insertGetId([
        'name' => 'Entrepôt Principal',
        'description' => 'Entrepôt principal du siège social',
        'address' => '123 Rue Principale',
        'latitude' => 48.8566,
        'longitude' => 2.3522,
        'region' => 'Île-de-France',
        'city' => 'Paris',
        'phone' => '+33 1 23 45 67 89',
        'email' => 'warehouse@csar.sn',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // 3. Créer les utilisateurs
    $adminId = DB::table('users')->insertGetId([
        'name' => 'Admin Principal',
        'email' => 'admin@csar.sn',
        'password' => Hash::make('password'),
        'role_id' => $adminRoleId,
        'warehouse_id' => $warehouseId,
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    $dgId = DB::table('users')->insertGetId([
        'name' => 'Directeur Général',
        'email' => 'dg@csar.sn',
        'password' => Hash::make('password'),
        'role_id' => $dgRoleId,
        'warehouse_id' => $warehouseId,
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    $responsableId = DB::table('users')->insertGetId([
        'name' => 'Responsable Entrepôt',
        'email' => 'responsable@csar.sn',
        'password' => Hash::make('password'),
        'role_id' => $responsableRoleId,
        'warehouse_id' => $warehouseId,
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    $agentId = DB::table('users')->insertGetId([
        'name' => 'Agent Test',
        'email' => 'agent@csar.sn',
        'password' => Hash::make('password'),
        'role_id' => $agentRoleId,
        'warehouse_id' => $warehouseId,
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // 4. Créer l'arrière-plan d'accueil
    DB::table('home_backgrounds')->insert([
        'title' => 'Bienvenue sur CSAR Platform',
        'description' => 'Plateforme de gestion des stocks et ressources',
        'image_path' => '/images/default-bg.jpg',
        'is_active' => true,
        'display_order' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    echo "✅ **UTILISATEURS CRÉÉS AVEC SUCCÈS !**\n\n";
    echo "🔐 **IDENTIFIANTS DE CONNEXION :**\n\n";
    
    echo "👑 **ADMINISTRATEUR**\n";
    echo "   URL: http://127.0.0.1:8000/admin/login\n";
    echo "   Email: admin@csar.sn\n";
    echo "   Mot de passe: password\n\n";
    
    echo "🎯 **DIRECTEUR GÉNÉRAL**\n";
    echo "   URL: http://127.0.0.1:8000/dg/login\n";
    echo "   Email: dg@csar.sn\n";
    echo "   Mot de passe: password\n\n";
    
    echo "🏢 **RESPONSABLE ENTRERPÔT**\n";
    echo "   URL: http://127.0.0.1:8000/entrepot/login\n";
    echo "   Email: responsable@csar.sn\n";
    echo "   Mot de passe: password\n\n";
    
    echo "👤 **AGENT**\n";
    echo "   URL: http://127.0.0.1:8000/agent/login\n";
    echo "   Email: agent@csar.sn\n";
    echo "   Mot de passe: password\n\n";
    
    echo "🌐 **SITE PUBLIC**\n";
    echo "   URL: http://127.0.0.1:8000/\n";
    echo "   Accès: Libre (pas de connexion)\n\n";
    
    echo "🎉 Tous les utilisateurs sont maintenant créés et prêts à utiliser !\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}


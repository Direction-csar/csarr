<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Créer un rôle admin
    $roleId = DB::table('roles')->insertGetId([
        'name' => 'admin',
        'display_name' => 'Administrateur',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // Créer un entrepôt par défaut
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

    // Créer un utilisateur admin
    $userId = DB::table('users')->insertGetId([
        'name' => 'Admin',
        'email' => 'admin@csar.sn',
        'password' => Hash::make('password'),
        'role_id' => $roleId,
        'warehouse_id' => $warehouseId,
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // Créer quelques données de base pour home_backgrounds
    DB::table('home_backgrounds')->insert([
        'title' => 'Bienvenue sur CSAR Platform',
        'description' => 'Plateforme de gestion des stocks et ressources',
        'image_path' => '/images/default-bg.jpg',
        'is_active' => true,
        'display_order' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    echo "✅ Utilisateur admin créé avec succès!\n";
    echo "📧 Email: admin@csar.sn\n";
    echo "🔑 Mot de passe: password\n";
    echo "🏢 Entrepôt créé: Entrepôt Principal\n";
    echo "🎨 Arrière-plan d'accueil créé\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

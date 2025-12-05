<?php

// Script simple pour créer un administrateur
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Supprimer l'ancien admin s'il existe
    \App\Models\User::where('email', 'admin@csar.sn')->delete();
    
    // Créer le nouvel administrateur
    $admin = \App\Models\User::create([
        'name' => 'Administrateur CSAR',
        'email' => 'admin@csar.sn',
        'password' => bcrypt('admin123'),
        'role' => 'admin',
        'is_active' => true
    ]);
    
    echo "✅ Compte administrateur créé avec succès !\n";
    echo "ID: {$admin->id}\n";
    echo "Nom: {$admin->name}\n";
    echo "Email: {$admin->email}\n";
    echo "Role: {$admin->role}\n";
    echo "Actif: " . ($admin->is_active ? 'Oui' : 'Non') . "\n";
    
    echo "\n📋 Identifiants de connexion :\n";
    echo "Email: admin@csar.sn\n";
    echo "Mot de passe: admin123\n";
    echo "\n🔗 Accès admin: http://localhost:8000/admin/login\n";
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}

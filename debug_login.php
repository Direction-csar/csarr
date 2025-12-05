<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DIAGNOSTIC CONNEXION ADMIN ===\n";

// Vérifier l'utilisateur admin
$admin = App\Models\User::where('email', 'admin@csar.sn')->first();

if ($admin) {
    echo "✅ Utilisateur trouvé:\n";
    echo "ID: " . $admin->id . "\n";
    echo "Email: " . $admin->email . "\n";
    echo "Role: " . $admin->role . "\n";
    echo "Role ID: " . $admin->role_id . "\n";
    echo "Is Active: " . ($admin->is_active ? 'Yes' : 'No') . "\n";
    echo "Password Hash: " . $admin->password . "\n";
    
    // Test du mot de passe
    echo "\n=== TEST MOT DE PASSE ===\n";
    $testPassword = 'admin123';
    $isValid = Illuminate\Support\Facades\Hash::check($testPassword, $admin->password);
    echo "Test password '$testPassword': " . ($isValid ? '✅ VALIDE' : '❌ INVALIDE') . "\n";
    
    // Test avec Auth::attempt
    echo "\n=== TEST AUTH::ATTEMPT ===\n";
    $credentials = ['email' => 'admin@csar.sn', 'password' => 'admin123'];
    $attempt = Illuminate\Support\Facades\Auth::attempt($credentials);
    echo "Auth::attempt result: " . ($attempt ? '✅ SUCCÈS' : '❌ ÉCHEC') . "\n";
    
    if ($attempt) {
        $user = Illuminate\Support\Facades\Auth::user();
        echo "Utilisateur connecté: " . $user->email . "\n";
        Illuminate\Support\Facades\Auth::logout();
    }
    
} else {
    echo "❌ Utilisateur admin@csar.sn non trouvé\n";
    
    // Chercher tous les utilisateurs
    echo "\n=== TOUS LES UTILISATEURS ===\n";
    $users = App\Models\User::all();
    foreach ($users as $user) {
        echo "ID: {$user->id} | Email: {$user->email} | Role: {$user->role} | Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
    }
}


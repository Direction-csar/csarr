<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Vérification des utilisateurs ===\n\n";

try {
    $users = User::all();
    
    if ($users->isEmpty()) {
        echo "❌ Aucun utilisateur trouvé dans la base de données!\n";
    } else {
        echo "✅ Utilisateurs trouvés: " . $users->count() . "\n\n";
        
        foreach ($users as $user) {
            echo "---\n";
            echo "ID: {$user->id}\n";
            echo "Nom: {$user->name}\n";
            echo "Email: {$user->email}\n";
            echo "Role ID: {$user->role_id}\n";
            echo "Password Hash: " . substr($user->password, 0, 20) . "...\n";
            
            // Tester le mot de passe
            if ($user->email === 'admin@csar.sn') {
                $testPassword = 'admin123';
                $matches = Hash::check($testPassword, $user->password);
                echo "Test mot de passe '$testPassword': " . ($matches ? "✅ OK" : "❌ ÉCHEC") . "\n";
            }
            echo "\n";
        }
    }
    
    // Maintenant, créons/mettons à jour l'utilisateur admin avec un mot de passe que nous savons qui fonctionne
    echo "\n=== Réinitialisation du compte admin ===\n\n";
    
    $admin = User::where('email', 'admin@csar.sn')->first();
    if ($admin) {
        $admin->password = Hash::make('admin123');
        $admin->save();
        echo "✅ Mot de passe admin réinitialisé!\n";
        echo "Email: admin@csar.sn\n";
        echo "Mot de passe: admin123\n\n";
        
        // Vérifier immédiatement
        $check = Hash::check('admin123', $admin->password);
        echo "Vérification: " . ($check ? "✅ Le mot de passe fonctionne!" : "❌ Erreur de vérification") . "\n";
    } else {
        echo "❌ Utilisateur admin non trouvé!\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}


<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "═══════════════════════════════════════════════════════════════\n";
echo "    🔐 RÉINITIALISATION DES MOTS DE PASSE CSAR\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

try {
    // Vérifier et réinitialiser les mots de passe
    $users = [
        'admin@csar.sn' => 'password',
        'dg@csar.sn' => 'password',
        'entrepot@csar.sn' => 'password',
        'responsable@csar.sn' => 'password',
        'agent@csar.sn' => 'password',
        'drh@csar.sn' => 'password'
    ];

    echo "🔄 Réinitialisation des mots de passe...\n";
    echo "───────────────────────────────────────────────────────────────\n\n";

    foreach ($users as $email => $password) {
        $hashedPassword = Hash::make($password);
        
        $affected = DB::table('users')
            ->where('email', $email)
            ->update(['password' => $hashedPassword]);
        
        if ($affected > 0) {
            echo "✅ Mot de passe réinitialisé pour: $email\n";
        } else {
            echo "⚠️  Utilisateur non trouvé: $email\n";
        }
    }

    echo "\n";
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "    ✅ RÉINITIALISATION TERMINÉE\n";
    echo "═══════════════════════════════════════════════════════════════\n\n";

    echo "🔐 IDENTIFIANTS ACTUALISÉS:\n";
    echo "───────────────────────────────────────────────────────────────\n";
    
    $allUsers = DB::table('users')
        ->select('name', 'email')
        ->get();
    
    foreach ($allUsers as $user) {
        echo "   👤 " . $user->name . "\n";
        echo "      Email: " . $user->email . "\n";
        echo "      Mot de passe: password\n\n";
    }

    echo "🌐 URLS DE CONNEXION:\n";
    echo "───────────────────────────────────────────────────────────────\n";
    echo "   Admin:       http://localhost:8000/admin/login\n";
    echo "   DG:          http://localhost:8000/dg/login\n";
    echo "   Entrepôt:    http://localhost:8000/entrepot/login\n";
    echo "   Agent:       http://localhost:8000/agent/login\n\n";

    echo "💡 CONSEIL:\n";
    echo "   Si vous ne pouvez toujours pas vous connecter:\n";
    echo "   1. Videz le cache: php artisan cache:clear\n";
    echo "   2. Videz les sessions: php clear_sessions_and_fix_login.php\n";
    echo "   3. Vérifiez que le serveur tourne: php artisan serve\n\n";

} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}



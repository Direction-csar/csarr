<?php

/**
 * Script de vérification et correction des comptes utilisateurs CSAR
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "═══════════════════════════════════════════════════════════════\n";
echo "   🔍 VÉRIFICATION ET CORRECTION DES COMPTES CSAR\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

try {
    // Test de connexion à la base de données
    echo "1️⃣  Test de connexion à la base de données...\n";
    DB::connection()->getPdo();
    echo "    ✅ Connexion réussie à la base de données\n\n";

    // Vérifier les rôles
    echo "2️⃣  Vérification des rôles...\n";
    $roles = DB::table('roles')->pluck('name', 'id')->toArray();
    
    if (empty($roles)) {
        echo "    ⚠️  Aucun rôle trouvé. Création des rôles...\n";
        
        $rolesData = [
            ['id' => 1, 'name' => 'admin', 'display_name' => 'Administrateur'],
            ['id' => 2, 'name' => 'dg', 'display_name' => 'Directeur Général'],
            ['id' => 3, 'name' => 'responsable', 'display_name' => 'Responsable Entrepôt'],
            ['id' => 4, 'name' => 'agent', 'display_name' => 'Agent'],
            ['id' => 5, 'name' => 'drh', 'display_name' => 'Directeur RH'],
        ];
        
        foreach ($rolesData as $role) {
            DB::table('roles')->insert($role);
            echo "    ✅ Rôle créé: {$role['display_name']}\n";
        }
    } else {
        echo "    ✅ Rôles existants: " . implode(', ', $roles) . "\n";
    }
    echo "\n";

    // Vérifier et créer/corriger les utilisateurs
    echo "3️⃣  Vérification et correction des utilisateurs...\n\n";
    
    $users = [
        [
            'email' => 'admin@csar.sn',
            'name' => 'Administrateur Principal',
            'role_id' => 1,
            'url' => 'http://localhost:8000/admin/login'
        ],
        [
            'email' => 'dg@csar.sn',
            'name' => 'Directeur Général',
            'role_id' => 2,
            'url' => 'http://localhost:8000/dg/login'
        ],
        [
            'email' => 'responsable@csar.sn',
            'name' => 'Responsable Entrepôt',
            'role_id' => 3,
            'url' => 'http://localhost:8000/entrepot/login'
        ],
        [
            'email' => 'agent@csar.sn',
            'name' => 'Agent CSAR',
            'role_id' => 4,
            'url' => 'http://localhost:8000/agent/login'
        ],
        [
            'email' => 'drh@csar.sn',
            'name' => 'Directeur RH',
            'role_id' => 5,
            'url' => 'http://localhost:8000/drh/login'
        ]
    ];

    foreach ($users as $userData) {
        $user = DB::table('users')->where('email', $userData['email'])->first();
        
        if ($user) {
            // L'utilisateur existe, vérifier et corriger
            echo "   👤 {$userData['name']}\n";
            echo "      📧 Email: {$userData['email']}\n";
            
            $updates = [];
            
            // Vérifier le rôle
            if ($user->role_id != $userData['role_id']) {
                $updates['role_id'] = $userData['role_id'];
                echo "      ⚠️  Role corrigé: {$user->role_id} → {$userData['role_id']}\n";
            }
            
            // Vérifier is_active
            if (!$user->is_active) {
                $updates['is_active'] = true;
                echo "      ⚠️  Compte activé\n";
            }
            
            // Réinitialiser le mot de passe à 'password'
            $updates['password'] = Hash::make('password');
            
            if (!empty($updates)) {
                DB::table('users')->where('id', $user->id)->update($updates);
                echo "      ✅ Mot de passe réinitialisé à 'password'\n";
            } else {
                echo "      ✅ Compte OK - Mot de passe réinitialisé à 'password'\n";
            }
            
            echo "      🌐 URL: {$userData['url']}\n\n";
            
        } else {
            // L'utilisateur n'existe pas, le créer
            echo "   ⚠️  {$userData['name']} n'existe pas. Création...\n";
            
            DB::table('users')->insert([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'role_id' => $userData['role_id'],
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            echo "      ✅ Créé avec succès\n";
            echo "      📧 Email: {$userData['email']}\n";
            echo "      🔑 Password: password\n";
            echo "      🌐 URL: {$userData['url']}\n\n";
        }
    }

    echo "\n═══════════════════════════════════════════════════════════════\n";
    echo "                    ✅ VÉRIFICATION TERMINÉE\n";
    echo "═══════════════════════════════════════════════════════════════\n\n";

    echo "📝 RÉSUMÉ DES IDENTIFIANTS:\n\n";
    echo "Tous les comptes utilisent le mot de passe: password\n\n";
    
    echo "👨‍💼 ADMIN\n";
    echo "   URL:   http://localhost:8000/admin/login\n";
    echo "   Email: admin@csar.sn\n\n";
    
    echo "👔 DIRECTEUR GÉNÉRAL (DG)\n";
    echo "   URL:   http://localhost:8000/dg/login\n";
    echo "   Email: dg@csar.sn\n\n";
    
    echo "📦 RESPONSABLE ENTREPÔT\n";
    echo "   URL:   http://localhost:8000/entrepot/login\n";
    echo "   Email: responsable@csar.sn\n\n";
    
    echo "🚚 AGENT\n";
    echo "   URL:   http://localhost:8000/agent/login\n";
    echo "   Email: agent@csar.sn\n\n";
    
    echo "👨‍💼 DRH\n";
    echo "   URL:   http://localhost:8000/drh/login\n";
    echo "   Email: drh@csar.sn\n\n";
    
    echo "═══════════════════════════════════════════════════════════════\n\n";
    
    echo "💡 CONSEILS:\n";
    echo "   1. Effacez le cache du navigateur (Ctrl + Shift + Delete)\n";
    echo "   2. Ou utilisez le mode navigation privée (Ctrl + Shift + N)\n";
    echo "   3. Allez directement sur l'URL de votre rôle\n";
    echo "   4. Connectez-vous avec les identifiants ci-dessus\n\n";

} catch (\Exception $e) {
    echo "\n❌ ERREUR: " . $e->getMessage() . "\n\n";
    
    if (strpos($e->getMessage(), 'could not find driver') !== false) {
        echo "💡 Solution: Activez l'extension PDO MySQL dans php.ini\n";
    } elseif (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "💡 Solution: Vérifiez vos identifiants MySQL dans le fichier .env\n";
    } elseif (strpos($e->getMessage(), 'Connection refused') !== false) {
        echo "💡 Solution: Assurez-vous que MySQL est démarré dans XAMPP\n";
    }
    
    exit(1);
}



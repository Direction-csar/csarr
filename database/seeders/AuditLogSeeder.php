<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuditLog;
use App\Models\User;
use Carbon\Carbon;

class AuditLogSeeder extends Seeder
{
    public function run()
    {
        // Récupérer tous les utilisateurs
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('Aucun utilisateur trouvé. Créez d\'abord des utilisateurs.');
            return;
        }

        $actions = ['login', 'logout', 'create', 'update', 'delete'];
        $modelTypes = [
            'App\\Models\\User',
            'App\\Models\\Personnel',
            'App\\Models\\Warehouse',
            'App\\Models\\PublicRequest',
            'App\\Models\\News',
            'App\\Models\\ContactMessage',
            'App\\Models\\TechnicalPartner',
            'App\\Models\\SimReport',
            'App\\Models\\PriceAlert',
            'App\\Models\\Task',
            'App\\Models\\WeeklyAgenda'
        ];

        $descriptions = [
            'login' => [
                'Connexion réussie à l\'administration',
                'Authentification utilisateur via le formulaire de login',
                'Accès accordé au tableau de bord administrateur'
            ],
            'logout' => [
                'Déconnexion de l\'utilisateur',
                'Fin de session administrative',
                'Logout automatique après expiration de session'
            ],
            'create' => [
                'Création d\'un nouveau enregistrement',
                'Ajout d\'une nouvelle entrée dans la base de données',
                'Enregistrement créé avec succès',
                'Nouvel élément ajouté au système'
            ],
            'update' => [
                'Modification des données existantes',
                'Mise à jour des informations',
                'Édition des propriétés de l\'enregistrement',
                'Actualisation des données'
            ],
            'delete' => [
                'Suppression d\'un enregistrement',
                'Élément retiré du système',
                'Suppression définitive des données',
                'Archivage de l\'enregistrement'
            ]
        ];

        $ips = [
            '192.168.1.100',
            '192.168.1.101',
            '192.168.1.102',
            '10.0.0.15',
            '10.0.0.25',
            '172.16.0.10',
            '127.0.0.1'
        ];

        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Edge/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
        ];

        // Créer 200 logs d'audit sur les 30 derniers jours
        for ($i = 0; $i < 200; $i++) {
            $user = $users->random();
            $action = $actions[array_rand($actions)];
            $modelType = null;
            $modelId = null;
            $oldValues = null;
            $newValues = null;

            // Pour les actions qui ne sont pas login/logout, associer un modèle
            if (!in_array($action, ['login', 'logout'])) {
                $modelType = $modelTypes[array_rand($modelTypes)];
                $modelId = rand(1, 50);

                // Générer des valeurs anciennes/nouvelles pour update
                if ($action === 'update') {
                    $oldValues = [
                        'name' => 'Ancien nom ' . rand(1, 100),
                        'email' => 'ancien.email' . rand(1, 100) . '@example.com',
                        'status' => 'inactive',
                        'updated_at' => Carbon::now()->subMinutes(rand(1, 60))->toDateTimeString()
                    ];
                    $newValues = [
                        'name' => 'Nouveau nom ' . rand(1, 100),
                        'email' => 'nouveau.email' . rand(1, 100) . '@example.com',
                        'status' => 'active',
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ];
                } elseif ($action === 'create') {
                    $newValues = [
                        'name' => 'Nouvel élément ' . rand(1, 100),
                        'email' => 'nouvel.email' . rand(1, 100) . '@example.com',
                        'status' => 'active',
                        'created_at' => Carbon::now()->toDateTimeString()
                    ];
                } elseif ($action === 'delete') {
                    $oldValues = [
                        'name' => 'Élément supprimé ' . rand(1, 100),
                        'email' => 'supprime.email' . rand(1, 100) . '@example.com',
                        'status' => 'active',
                        'deleted_at' => Carbon::now()->toDateTimeString()
                    ];
                }
            }

            AuditLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'model_type' => $modelType,
                'model_id' => $modelId,
                'description' => $descriptions[$action][array_rand($descriptions[$action])],
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => $ips[array_rand($ips)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'created_at' => Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                'updated_at' => Carbon::now()
            ]);
        }

        $this->command->info('200 logs d\'audit créés avec succès!');
    }
}


<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Demande;
use App\Models\StockMovement;
use App\Models\Notification;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin par défaut
        User::firstOrCreate(
            ['email' => 'admin@csar.sn'],
            [
                'name' => 'Administrateur CSAR',
                'email' => 'admin@csar.sn',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'actif',
                'phone' => '+221 77 123 45 67',
                'position' => 'Administrateur',
                'department' => 'IT',
                'address' => 'Dakar, Sénégal'
            ]
        );

        // Créer quelques entrepôts de base
        $warehouses = [
            [
                'nom' => 'Entrepôt Principal Dakar',
                'adresse' => 'Zone Industrielle, Dakar',
                'ville' => 'Dakar',
                'region' => 'Dakar',
                'latitude' => 14.7167,
                'longitude' => -17.4677,
                'capacite' => 10000,
                'unite_capacite' => 'L',
                'taux_occupation' => 75.5,
                'type' => 'principal',
                'statut' => 'actif',
                'responsable' => 'Mamadou Diop',
                'telephone_responsable' => '+221 77 234 56 78',
                'email_responsable' => 'mamadou.diop@csar.sn',
                'description' => 'Entrepôt principal pour la région de Dakar',
                'date_creation' => Carbon::now()->subMonths(6)
            ],
            [
                'nom' => 'Entrepôt Thiès',
                'adresse' => 'Route de Mbour, Thiès',
                'ville' => 'Thiès',
                'region' => 'Thiès',
                'latitude' => 14.7833,
                'longitude' => -16.9167,
                'capacite' => 5000,
                'unite_capacite' => 'L',
                'taux_occupation' => 60.0,
                'type' => 'secondaire',
                'statut' => 'actif',
                'responsable' => 'Aminata Fall',
                'telephone_responsable' => '+221 77 345 67 89',
                'email_responsable' => 'aminata.fall@csar.sn',
                'description' => 'Entrepôt secondaire pour la région de Thiès',
                'date_creation' => Carbon::now()->subMonths(4)
            ],
            [
                'nom' => 'Entrepôt Saint-Louis',
                'adresse' => 'Quartier Nord, Saint-Louis',
                'ville' => 'Saint-Louis',
                'region' => 'Saint-Louis',
                'latitude' => 16.0333,
                'longitude' => -16.5000,
                'capacite' => 3000,
                'unite_capacite' => 'L',
                'taux_occupation' => 45.0,
                'type' => 'depot',
                'statut' => 'actif',
                'responsable' => 'Ibrahima Ndiaye',
                'telephone_responsable' => '+221 77 456 78 90',
                'email_responsable' => 'ibrahima.ndiaye@csar.sn',
                'description' => 'Dépôt pour la région de Saint-Louis',
                'date_creation' => Carbon::now()->subMonths(2)
            ]
        ];

        foreach ($warehouses as $warehouseData) {
            Warehouse::firstOrCreate(
                ['name' => $warehouseData['nom']],
                [
                    'name' => $warehouseData['nom'],
                    'address' => $warehouseData['adresse'],
                    'city' => $warehouseData['ville'],
                    'region' => $warehouseData['region'],
                    'latitude' => $warehouseData['latitude'],
                    'longitude' => $warehouseData['longitude'],
                    'description' => $warehouseData['description'],
                    'is_active' => true,
                    'created_at' => $warehouseData['date_creation']
                ]
            );
        }

        // Créer quelques utilisateurs de base
        $users = [
            [
                'name' => 'Directeur Général',
                'email' => 'dg@csar.sn',
                'password' => Hash::make('password'),
                'role' => 'dg',
                'status' => 'actif',
                'phone' => '+221 77 111 22 33',
                'position' => 'Directeur Général',
                'department' => 'Direction',
                'address' => 'Dakar, Sénégal'
            ],
            [
                'name' => 'Responsable Logistique',
                'email' => 'logistique@csar.sn',
                'password' => Hash::make('password'),
                'role' => 'responsable',
                'status' => 'actif',
                'phone' => '+221 77 222 33 44',
                'position' => 'Responsable Logistique',
                'department' => 'Logistique',
                'address' => 'Dakar, Sénégal'
            ],
            [
                'name' => 'Agent Stock',
                'email' => 'agent@csar.sn',
                'password' => Hash::make('password'),
                'role' => 'agent',
                'status' => 'actif',
                'phone' => '+221 77 333 44 55',
                'position' => 'Agent de Stock',
                'department' => 'Stock',
                'address' => 'Dakar, Sénégal'
            ]
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // Créer quelques notifications de base
        $notifications = [
            [
                'type' => 'system',
                'title' => 'Bienvenue sur la plateforme CSAR',
                'message' => 'Votre compte a été créé avec succès. Vous pouvez maintenant accéder à toutes les fonctionnalités.',
                'read' => false,
                'user_id' => null
            ],
            [
                'type' => 'info',
                'title' => 'Maintenance programmée',
                'message' => 'Une maintenance est prévue ce weekend pour améliorer les performances.',
                'read' => false,
                'user_id' => null
            ]
        ];

        foreach ($notifications as $notificationData) {
            Notification::create($notificationData);
        }

        // Créer quelques messages de base
        $messages = [
            [
                'sujet' => 'Demande d\'information',
                'contenu' => 'Bonjour, j\'aimerais avoir des informations sur les services proposés.',
                'expediteur' => 'Jean Dupont',
                'email_expediteur' => 'jean.dupont@email.com',
                'telephone_expediteur' => '+221 77 555 66 77',
                'lu' => false
            ],
            [
                'sujet' => 'Problème technique',
                'contenu' => 'J\'ai rencontré un problème lors de l\'utilisation de la plateforme.',
                'expediteur' => 'Marie Martin',
                'email_expediteur' => 'marie.martin@email.com',
                'telephone_expediteur' => '+221 77 666 77 88',
                'lu' => false
            ]
        ];

        foreach ($messages as $messageData) {
            Message::create($messageData);
        }

        $this->command->info('Données initiales créées avec succès !');
    }
}
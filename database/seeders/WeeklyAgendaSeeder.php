<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeeklyAgenda;
use App\Models\User;
use Carbon\Carbon;

class WeeklyAgendaSeeder extends Seeder
{
    public function run()
    {
        // Récupérer quelques utilisateurs pour les assignations
        $users = User::limit(3)->get();
        
        if ($users->isEmpty()) {
            $this->command->warn('Aucun utilisateur trouvé. Création d\'événements sans assignation.');
        }

        // Événements pour cette semaine
        $startOfWeek = Carbon::now()->startOfWeek();
        
        $events = [
            [
                'title' => 'Réunion équipe de direction',
                'description' => 'Point mensuel sur les objectifs et les résultats du CSAR',
                'event_type' => 'meeting',
                'start_date' => $startOfWeek->copy()->addDay()->setTime(9, 0), // Mardi 9h
                'end_date' => $startOfWeek->copy()->addDay()->setTime(11, 0), // Mardi 11h
                'location' => 'Salle de conférence principale',
                'priority' => 'high',
                'status' => 'scheduled',
                'assigned_to' => $users->isNotEmpty() ? $users->first()->id : null,
                'created_by' => $users->isNotEmpty() ? $users->first()->id : null,
            ],
            [
                'title' => 'Livraison matériel informatique',
                'description' => 'Réception des nouveaux ordinateurs pour les agents',
                'event_type' => 'delivery',
                'start_date' => $startOfWeek->copy()->addDays(2)->setTime(14, 0), // Mercredi 14h
                'end_date' => $startOfWeek->copy()->addDays(2)->setTime(16, 0), // Mercredi 16h
                'location' => 'Entrepôt principal',
                'priority' => 'medium',
                'status' => 'scheduled',
                'assigned_to' => $users->count() > 1 ? $users->skip(1)->first()->id : null,
                'created_by' => $users->isNotEmpty() ? $users->first()->id : null,
                'notes' => 'Prévoir main-d\'œuvre pour le déchargement',
            ],
            [
                'title' => 'Visite terrain région Nord',
                'description' => 'Inspection des centres de distribution dans la région Nord',
                'event_type' => 'visit',
                'start_date' => $startOfWeek->copy()->addDays(3)->setTime(8, 0), // Jeudi 8h
                'end_date' => $startOfWeek->copy()->addDays(3)->setTime(17, 0), // Jeudi 17h
                'location' => 'Saint-Louis, Louga, Matam',
                'priority' => 'high',
                'status' => 'scheduled',
                'assigned_to' => $users->count() > 2 ? $users->skip(2)->first()->id : null,
                'created_by' => $users->isNotEmpty() ? $users->first()->id : null,
                'tags' => ['inspection', 'terrain', 'nord'],
            ],
            [
                'title' => 'Formation personnel entrepôt',
                'description' => 'Formation sur les nouvelles procédures de stockage',
                'event_type' => 'task',
                'start_date' => $startOfWeek->copy()->addDays(4)->setTime(10, 0), // Vendredi 10h
                'end_date' => $startOfWeek->copy()->addDays(4)->setTime(12, 0), // Vendredi 12h
                'location' => 'Salle de formation',
                'priority' => 'medium',
                'status' => 'scheduled',
                'assigned_to' => $users->isNotEmpty() ? $users->first()->id : null,
                'created_by' => $users->isNotEmpty() ? $users->first()->id : null,
            ],
            [
                'title' => 'Instruction prioritaire - Distribution urgente',
                'description' => 'Distribution d\'aide d\'urgence suite aux inondations',
                'event_type' => 'instruction',
                'start_date' => $startOfWeek->copy()->setTime(6, 0), // Lundi 6h
                'end_date' => $startOfWeek->copy()->setTime(20, 0), // Lundi 20h
                'location' => 'Zones sinistrées',
                'priority' => 'urgent',
                'status' => 'in_progress',
                'assigned_to' => $users->isNotEmpty() ? $users->first()->id : null,
                'created_by' => $users->isNotEmpty() ? $users->first()->id : null,
                'notes' => 'Mobilisation de tous les agents disponibles',
            ]
        ];

        // Événements pour la semaine prochaine
        $startOfNextWeek = Carbon::now()->addWeek()->startOfWeek();
        
        $nextWeekEvents = [
            [
                'title' => 'Bilan hebdomadaire',
                'description' => 'Bilan des activités de la semaine écoulée',
                'event_type' => 'meeting',
                'start_date' => $startOfNextWeek->copy()->setTime(15, 0), // Lundi 15h
                'end_date' => $startOfNextWeek->copy()->setTime(17, 0), // Lundi 17h
                'location' => 'Bureau du directeur',
                'priority' => 'medium',
                'status' => 'scheduled',
                'assigned_to' => $users->isNotEmpty() ? $users->first()->id : null,
                'created_by' => $users->isNotEmpty() ? $users->first()->id : null,
            ],
            [
                'title' => 'Maintenance véhicules',
                'description' => 'Révision programmée de la flotte de véhicules',
                'event_type' => 'task',
                'start_date' => $startOfNextWeek->copy()->addDays(2)->setTime(8, 0), // Mercredi 8h
                'end_date' => $startOfNextWeek->copy()->addDays(2)->setTime(12, 0), // Mercredi 12h
                'location' => 'Garage CSAR',
                'priority' => 'medium',
                'status' => 'scheduled',
                'assigned_to' => $users->count() > 1 ? $users->skip(1)->first()->id : null,
                'created_by' => $users->isNotEmpty() ? $users->first()->id : null,
            ]
        ];

        // Créer les événements
        foreach (array_merge($events, $nextWeekEvents) as $eventData) {
            WeeklyAgenda::create($eventData);
        }

        $this->command->info('Événements d\'agenda créés avec succès !');
    }
}


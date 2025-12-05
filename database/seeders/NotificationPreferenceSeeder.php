<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\NotificationPreference;

class NotificationPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Créer des préférences par défaut pour tous les utilisateurs existants
        $users = User::whereDoesntHave('notificationPreferences')->get();

        foreach ($users as $user) {
            NotificationPreference::create([
                'user_id' => $user->id,
                'email_enabled' => true,
                'task_assignments' => true,
                'request_updates' => true,
                'price_alerts' => $user->hasRole('admin') || $user->hasRole('dg') || $user->hasRole('responsable'),
                'news_updates' => false,
                'system_notifications' => true,
                'weekly_digest' => $user->hasRole('admin') || $user->hasRole('dg')
            ]);
        }

        $this->command->info('Préférences de notification créées pour ' . $users->count() . ' utilisateurs.');
    }
}


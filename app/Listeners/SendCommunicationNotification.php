<?php

namespace App\Listeners;

use App\Events\CommunicationPublished;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendCommunicationNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(CommunicationPublished $event): void
    {
        try {
            $communication = $event->communication;
            
            // Créer la notification pour les utilisateurs internes
            Notification::createNotification(
                'Nouvelle communication officielle',
                "Une nouvelle communication officielle a été publiée : {$communication->title}",
                'communication',
                [
                    'communication_id' => $communication->id,
                    'title' => $communication->title,
                    'category' => $communication->category ?? 'Actualité'
                ],
                $communication,
                'megaphone',
                route('admin.news.show', $communication->id)
            );

            Log::info('Notification créée pour nouvelle communication', [
                'communication_id' => $communication->id,
                'title' => $communication->title
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de notification pour communication', [
                'error' => $e->getMessage(),
                'communication_id' => $event->communication->id
            ]);
        }
    }
}


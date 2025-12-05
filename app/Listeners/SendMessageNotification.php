<?php

namespace App\Listeners;

use App\Events\MessageReceived;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendMessageNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(MessageReceived $event): void
    {
        try {
            $message = $event->message;
            
            // Créer la notification
            Notification::createNotification(
                'Nouveau message de contact',
                "Nouveau message de contact reçu de {$message->nom} ({$message->email}).",
                'message',
                [
                    'message_id' => $message->id,
                    'nom' => $message->nom,
                    'email' => $message->email,
                    'sujet' => $message->sujet ?? 'Contact'
                ],
                $message,
                'mail',
                route('admin.messages.show', $message->id)
            );

            Log::info('Notification créée pour nouveau message', [
                'message_id' => $message->id,
                'nom' => $message->nom
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de notification pour message', [
                'error' => $e->getMessage(),
                'message_id' => $event->message->id
            ]);
        }
    }
}


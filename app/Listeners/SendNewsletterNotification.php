<?php

namespace App\Listeners;

use App\Events\NewsletterSubscribed;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendNewsletterNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(NewsletterSubscribed $event): void
    {
        try {
            $subscriber = $event->subscriber;
            
            // Créer la notification
            Notification::createNotification(
                'Nouvelle inscription à la newsletter',
                "Nouvelle inscription à la newsletter : {$subscriber->email}",
                'newsletter',
                [
                    'subscriber_id' => $subscriber->id,
                    'email' => $subscriber->email,
                    'subscribed_at' => $subscriber->subscribed_at ?? now()
                ],
                $subscriber,
                'send',
                route('admin.newsletter.subscribers.index')
            );

            Log::info('Notification créée pour nouvelle inscription newsletter', [
                'subscriber_id' => $subscriber->id,
                'email' => $subscriber->email
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de notification pour newsletter', [
                'error' => $e->getMessage(),
                'subscriber_id' => $event->subscriber->id
            ]);
        }
    }
}


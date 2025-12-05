<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🧪 Test de configuration email - CSAR')
            ->greeting('Test de notification')
            ->line('Ceci est un email de test pour vérifier la configuration des notifications.')
            ->line('Si vous recevez cet email, la configuration fonctionne correctement !')
            ->line('**Informations du test :**')
            ->line('• **Date :** ' . now()->format('d/m/Y à H:i:s'))
            ->line('• **Destinataire :** ' . $notifiable->email)
            ->line('• **Plateforme :** CSAR - Sécurité Alimentaire et Résilience')
            ->action('Accéder à la plateforme', url('/admin'))
            ->line('Ce message est généré automatiquement lors du test de configuration.')
            ->salutation('L\'équipe technique CSAR');
    }
}


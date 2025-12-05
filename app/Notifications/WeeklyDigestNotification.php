<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeeklyDigestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $weekData;
    protected $user;

    public function __construct($weekData, $user)
    {
        $this->weekData = $weekData;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $startOfWeek = now()->startOfWeek()->format('d/m/Y');
        $endOfWeek = now()->endOfWeek()->format('d/m/Y');

        return (new MailMessage)
            ->subject('📊 Digest hebdomadaire CSAR - Semaine du ' . $startOfWeek)
            ->greeting('Bonjour ' . $this->user->name . ' !')
            ->line('Voici votre résumé d\'activité pour la semaine du ' . $startOfWeek . ' au ' . $endOfWeek . '.')
            ->line('')
            ->line('**📋 Tâches :**')
            ->line('• Nouvelles tâches créées : ' . $this->weekData['tasks_created'])
            ->line('• Tâches terminées : ' . $this->weekData['tasks_completed'])
            ->line('')
            ->line('**📬 Demandes publiques :**')
            ->line('• Demandes traitées : ' . $this->weekData['requests_processed'])
            ->line('')
            ->line('**📰 Actualités :**')
            ->line('• Nouvelles actualités publiées : ' . $this->weekData['news_published'])
            ->line('')
            ->when($this->weekData['price_alerts'] > 0, function ($message) {
                return $message
                    ->line('**🚨 Alertes de prix :**')
                    ->line('• Nouvelles alertes : ' . $this->weekData['price_alerts']);
            })
            ->line('')
            ->action('Accéder au tableau de bord', url('/admin/dashboard'))
            ->line('Merci de votre engagement pour la mission du CSAR !')
            ->salutation('Cordialement, L\'équipe CSAR');
    }

    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'week_data' => $this->weekData,
            'message' => 'Digest hebdomadaire envoyé'
        ];
    }
}


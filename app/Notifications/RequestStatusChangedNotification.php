<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PublicRequest;

class RequestStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $request;
    protected $oldStatus;

    public function __construct(PublicRequest $request, $oldStatus = null)
    {
        $this->request = $request;
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $statusEmojis = [
            'pending' => '⏳',
            'processing' => '🔄',
            'approved' => '✅',
            'rejected' => '❌',
            'completed' => '🎉'
        ];

        $statusLabels = [
            'pending' => 'En attente',
            'processing' => 'En traitement',
            'approved' => 'Approuvée',
            'rejected' => 'Rejetée',
            'completed' => 'Terminée'
        ];

        return (new MailMessage)
            ->subject('📬 Mise à jour de votre demande - ' . $this->request->tracking_code)
            ->greeting('Bonjour ' . $this->request->full_name . ' !')
            ->line('Le statut de votre demande a été mis à jour.')
            ->line('**Informations de la demande :**')
            ->line('• **Code de suivi :** ' . $this->request->tracking_code)
            ->line('• **Type :** ' . $this->request->type)
            ->line('• **Nouveau statut :** ' . ($statusEmojis[$this->request->status] ?? '⚪') . ' ' . ($statusLabels[$this->request->status] ?? $this->request->status))
            ->when($this->request->admin_comment, function ($message) {
                return $message->line('• **Commentaire :** ' . $this->request->admin_comment);
            })
            ->when($this->request->processed_date, function ($message) {
                return $message->line('• **Date de traitement :** ' . $this->request->processed_date->format('d/m/Y à H:i'));
            })
            ->action('Suivre ma demande', url('/suivre-ma-demande?code=' . $this->request->tracking_code))
            ->line('Vous pouvez suivre l\'évolution de votre demande à tout moment avec votre code de suivi.')
            ->salutation('Cordialement, L\'équipe CSAR');
    }

    public function toArray($notifiable)
    {
        return [
            'request_id' => $this->request->id,
            'tracking_code' => $this->request->tracking_code,
            'old_status' => $this->oldStatus,
            'new_status' => $this->request->status,
            'message' => 'Statut de demande mis à jour'
        ];
    }
}


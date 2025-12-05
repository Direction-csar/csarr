<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PriceAlert;

class PriceAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $alert;

    public function __construct(PriceAlert $alert)
    {
        $this->alert = $alert;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $levelEmojis = [
            'low' => '🟢',
            'medium' => '🟡',
            'high' => '🟠',
            'critical' => '🔴'
        ];

        return (new MailMessage)
            ->subject('🚨 Alerte de prix - ' . $this->alert->product_name)
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Une nouvelle alerte de prix a été détectée et nécessite votre attention.')
            ->line('**Détails de l\'alerte :**')
            ->line('• **Produit :** ' . $this->alert->product_name)
            ->line('• **Marché :** ' . $this->alert->market_name)
            ->line('• **Région :** ' . $this->alert->region)
            ->line('• **Prix actuel :** ' . number_format($this->alert->current_price, 0, ',', ' ') . ' FCFA')
            ->line('• **Prix de référence :** ' . number_format($this->alert->reference_price, 0, ',', ' ') . ' FCFA')
            ->line('• **Augmentation :** ' . $this->alert->increase_percentage . '%')
            ->line('• **Niveau d\'alerte :** ' . ($levelEmojis[$this->alert->alert_level] ?? '⚪') . ' ' . ucfirst($this->alert->alert_level))
            ->when($this->alert->alert_level === 'critical', function ($message) {
                return $message
                    ->line('⚠️ **CRITIQUE :** Cette alerte nécessite une action immédiate !')
                    ->line('Veuillez prendre les mesures appropriées dans les plus brefs délais.');
            })
            ->action('Voir l\'alerte', url('/admin/price-alerts/' . $this->alert->id))
            ->line('Merci de surveiller cette situation et de prendre les mesures nécessaires.')
            ->salutation('Cordialement, L\'équipe CSAR');
    }

    public function toArray($notifiable)
    {
        return [
            'alert_id' => $this->alert->id,
            'product_name' => $this->alert->product_name,
            'market_name' => $this->alert->market_name,
            'increase_percentage' => $this->alert->increase_percentage,
            'alert_level' => $this->alert->alert_level,
            'message' => 'Nouvelle alerte de prix détectée'
        ];
    }
}


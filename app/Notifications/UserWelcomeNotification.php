<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserWelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $temporaryPassword;

    public function __construct(User $user, $temporaryPassword = null)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🎉 Bienvenue dans la plateforme CSAR !')
            ->greeting('Bienvenue ' . $this->user->name . ' !')
            ->line('Votre compte a été créé avec succès sur la plateforme CSAR.')
            ->line('**Informations de connexion :**')
            ->line('• Email : ' . $this->user->email)
            ->line('• Rôle : ' . ($this->user->role ? $this->user->role->display_name : 'Utilisateur'))
            ->when($this->temporaryPassword, function ($message) {
                return $message
                    ->line('• Mot de passe temporaire : ' . $this->temporaryPassword)
                    ->line('⚠️ **Important :** Veuillez changer votre mot de passe lors de votre première connexion.');
            })
            ->action('Accéder à la plateforme', url('/admin/login'))
            ->line('Si vous avez des questions, n\'hésitez pas à contacter l\'administrateur.')
            ->line('Merci de faire partie de l\'équipe CSAR !')
            ->salutation('Cordialement, L\'équipe CSAR');
    }

    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'message' => 'Nouveau compte créé sur la plateforme CSAR'
        ];
    }
}


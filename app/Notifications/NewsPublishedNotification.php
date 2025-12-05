<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\News;

class NewsPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $typeEmojis = [
            'article' => '📰',
            'communique' => '📢',
            'evenement' => '🎉'
        ];

        return (new MailMessage)
            ->subject('📰 Nouvelle actualité CSAR - ' . $this->news->title)
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Une nouvelle actualité vient d\'être publiée sur la plateforme CSAR.')
            ->line('**Détails de l\'actualité :**')
            ->line('• **Titre :** ' . $this->news->title)
            ->line('• **Type :** ' . ($typeEmojis[$this->news->type] ?? '📝') . ' ' . ucfirst($this->news->type))
            ->line('• **Date de publication :** ' . $this->news->published_at->format('d/m/Y à H:i'))
            ->line('• **Extrait :** ' . \Str::limit(strip_tags($this->news->content), 150))
            ->action('Lire l\'actualité', url('/actualites/' . $this->news->id))
            ->line('Restez informé des dernières nouvelles du CSAR !')
            ->salutation('Cordialement, L\'équipe CSAR');
    }

    public function toArray($notifiable)
    {
        return [
            'news_id' => $this->news->id,
            'news_title' => $this->news->title,
            'news_type' => $this->news->type,
            'published_at' => $this->news->published_at,
            'message' => 'Nouvelle actualité publiée'
        ];
    }
}


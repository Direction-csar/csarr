<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $priorityEmoji = [
            'low' => '🟢',
            'medium' => '🟡',
            'high' => '🔴',
            'urgent' => '🚨'
        ];

        return (new MailMessage)
            ->subject('📋 Nouvelle tâche assignée - ' . $this->task->title)
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Une nouvelle tâche vous a été assignée sur la plateforme CSAR.')
            ->line('**Détails de la tâche :**')
            ->line('• **Titre :** ' . $this->task->title)
            ->line('• **Priorité :** ' . ($priorityEmoji[$this->task->priority] ?? '⚪') . ' ' . ucfirst($this->task->priority))
            ->line('• **Statut :** ' . ucfirst($this->task->status))
            ->when($this->task->due_date, function ($message) {
                return $message->line('• **Date d\'échéance :** ' . $this->task->due_date->format('d/m/Y à H:i'));
            })
            ->when($this->task->description, function ($message) {
                return $message->line('• **Description :** ' . \Str::limit($this->task->description, 150));
            })
            ->action('Voir la tâche', url('/admin/tasks/' . $this->task->id))
            ->line('Merci de traiter cette tâche dans les délais impartis.')
            ->salutation('Cordialement, L\'équipe CSAR');
    }

    public function toArray($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'task_priority' => $this->task->priority,
            'assigned_to' => $notifiable->id,
            'message' => 'Nouvelle tâche assignée'
        ];
    }
}


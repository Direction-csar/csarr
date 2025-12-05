<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SmsNotification;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $notification;

    public function __construct(SmsNotification $notification)
    {
        $this->notification = $notification;
    }

    public function build()
    {
        $typeColors = [
            'info' => '#3B82F6',
            'warning' => '#F59E0B',
            'success' => '#10B981',
            'error' => '#EF4444'
        ];

        $color = $typeColors[$this->notification->type] ?? '#3B82F6';

        return $this->subject($this->notification->title)
            ->view('emails.notification')
            ->with([
                'notification' => $this->notification,
                'color' => $color
            ]);
    }
}







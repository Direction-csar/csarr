<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $contactData;

    /**
     * Create a new message instance.
     */
    public function __construct($contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Nouveau message de contact - CSAR')
                    ->view('emails.contact-notification')
                    ->with([
                        'name' => $this->contactData['name'],
                        'email' => $this->contactData['email'],
                        'phone' => $this->contactData['phone'] ?? '',
                        'subject' => $this->contactData['subject'] ?? '',
                        'message' => $this->contactData['message'],
                        'date' => now()->format('d/m/Y à H:i')
                    ]);
    }
}


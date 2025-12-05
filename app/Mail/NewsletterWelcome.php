<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriberData;

    /**
     * Create a new message instance.
     */
    public function __construct($subscriberData)
    {
        $this->subscriberData = $subscriberData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Bienvenue à la Newsletter CSAR')
                    ->view('emails.newsletter-welcome')
                    ->with([
                        'email' => $this->subscriberData['email'],
                        'date' => now()->format('d/m/Y à H:i')
                    ]);
    }
}


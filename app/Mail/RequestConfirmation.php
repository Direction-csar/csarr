<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $requestData;

    /**
     * Create a new message instance.
     */
    public function __construct($requestData)
    {
        $this->requestData = $requestData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Confirmation de votre demande - CSAR')
                    ->view('emails.request-confirmation')
                    ->with([
                        'name' => $this->requestData['name'],
                        'email' => $this->requestData['email'],
                        'type' => $this->requestData['type'] ?? 'Demande d\'aide',
                        'tracking_code' => $this->requestData['tracking_code'] ?? '',
                        'date' => now()->format('d/m/Y à H:i')
                    ]);
    }
}


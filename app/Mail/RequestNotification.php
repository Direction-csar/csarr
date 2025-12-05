<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestNotification extends Mailable
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
        return $this->subject('Nouvelle demande reçue - CSAR')
                    ->view('emails.request-notification')
                    ->with([
                        'name' => $this->requestData['name'],
                        'email' => $this->requestData['email'],
                        'phone' => $this->requestData['phone'] ?? '',
                        'type' => $this->requestData['type'] ?? 'Demande d\'aide',
                        'tracking_code' => $this->requestData['tracking_code'] ?? '',
                        'message' => $this->requestData['message'] ?? '',
                        'date' => now()->format('d/m/Y à H:i')
                    ]);
    }
}


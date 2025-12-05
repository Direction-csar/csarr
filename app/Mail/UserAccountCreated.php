<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $userData;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct($userData, $password)
    {
        $this->userData = $userData;
        $this->password = $password;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Votre compte CSAR a été créé')
                    ->view('emails.user-account-created')
                    ->with([
                        'name' => $this->userData['name'],
                        'email' => $this->userData['email'],
                        'role' => $this->userData['role'],
                        'password' => $this->password,
                        'login_url' => url('/admin/login'),
                        'date' => now()->format('d/m/Y à H:i')
                    ]);
    }
}


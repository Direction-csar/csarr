<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $emailClass;
    public $recipient;
    public $data;
    public $tries = 3;
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct($emailClass, $recipient, $data = [])
    {
        $this->emailClass = $emailClass;
        $this->recipient = $recipient;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->recipient)->send(new $this->emailClass($this->data));
            
            Log::info('Email envoyé avec succès', [
                'recipient' => $this->recipient,
                'email_class' => $this->emailClass
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi d\'email', [
                'recipient' => $this->recipient,
                'email_class' => $this->emailClass,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Échec définitif de l\'envoi d\'email', [
            'recipient' => $this->recipient,
            'email_class' => $this->emailClass,
            'error' => $exception->getMessage()
        ]);
    }
}

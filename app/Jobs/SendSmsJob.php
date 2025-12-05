<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phoneNumber;
    public $message;
    public $type;
    public $tries = 3;
    public $timeout = 30;

    /**
     * Create a new job instance.
     */
    public function __construct($phoneNumber, $message, $type = 'notification')
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $smsService = new SmsService();
            $result = $smsService->sendSms($this->phoneNumber, $this->message, $this->type);
            
            Log::info('SMS envoyé avec succès', [
                'phone' => $this->phoneNumber,
                'type' => $this->type,
                'result' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de SMS', [
                'phone' => $this->phoneNumber,
                'type' => $this->type,
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
        Log::error('Échec définitif de l\'envoi de SMS', [
            'phone' => $this->phoneNumber,
            'type' => $this->type,
            'error' => $exception->getMessage()
        ]);
    }
}

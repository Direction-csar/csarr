<?php

namespace App\Jobs;

use App\Models\SmsNotification;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSmsNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $smsNotification;
    protected $smsService;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(SmsNotification $smsNotification)
    {
        $this->smsNotification = $smsNotification;
    }

    /**
     * Execute the job.
     */
    public function handle(SmsService $smsService): void
    {
        $this->smsService = $smsService;

        // Vérifier si la notification est toujours en attente
        if (!$this->smsNotification->isPending()) {
            Log::info("Notification SMS #{$this->smsNotification->id} déjà traitée");
            return;
        }

        try {
            Log::info("Traitement de la notification SMS #{$this->smsNotification->id}");

            // Tenter d'envoyer le SMS
            $result = $this->smsService->sendToProvider($this->smsNotification);

            if ($result['success']) {
                $this->smsNotification->markAsSent();
                Log::info("Notification SMS #{$this->smsNotification->id} envoyée avec succès");
            } else {
                $this->smsNotification->markAsFailed($result['error']);
                Log::error("Échec de l'envoi de la notification SMS #{$this->smsNotification->id}: " . $result['error']);
                
                // Relancer le job si possible
                if ($this->smsNotification->canRetry()) {
                    $this->release(300); // Relancer dans 5 minutes
                }
            }

        } catch (\Exception $e) {
            Log::error("Erreur lors du traitement de la notification SMS #{$this->smsNotification->id}: " . $e->getMessage());
            
            $this->smsNotification->markAsFailed($e->getMessage());
            
            // Relancer le job si possible
            if ($this->smsNotification->canRetry()) {
                $this->release(300); // Relancer dans 5 minutes
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Job de notification SMS #{$this->smsNotification->id} échoué définitivement: " . $exception->getMessage());
        
        $this->smsNotification->markAsFailed($exception->getMessage());
    }
}

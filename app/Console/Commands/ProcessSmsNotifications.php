<?php

namespace App\Console\Commands;

use App\Models\SmsNotification;
use App\Services\SmsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessSmsNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:process {--limit=50 : Nombre maximum de notifications à traiter}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Traiter les notifications SMS en attente';

    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        
        $this->info("Traitement des notifications SMS (limite: {$limit})...");

        // Récupérer les notifications en attente
        $notifications = SmsNotification::where('status', SmsNotification::STATUS_PENDING)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();

        if ($notifications->isEmpty()) {
            $this->info('Aucune notification SMS en attente.');
            return 0;
        }

        $this->info("Traitement de {$notifications->count()} notifications...");

        $processed = 0;
        $sent = 0;
        $failed = 0;

        foreach ($notifications as $notification) {
            try {
                $this->line("Traitement de la notification #{$notification->id}...");
                
                // Tenter d'envoyer le SMS
                $result = $this->smsService->sendToProvider($notification);
                
                if ($result['success']) {
                    $notification->markAsSent();
                    $sent++;
                    $this->info("  ✓ Envoyé avec succès");
                } else {
                    $notification->markAsFailed($result['error']);
                    $failed++;
                    $this->error("  ✗ Échec: " . $result['error']);
                }
                
                $processed++;
                
                // Petite pause pour éviter de surcharger l'API
                usleep(100000); // 0.1 seconde
                
            } catch (\Exception $e) {
                $notification->markAsFailed($e->getMessage());
                $failed++;
                $this->error("  ✗ Erreur: " . $e->getMessage());
                $processed++;
            }
        }

        $this->info("Traitement terminé:");
        $this->info("  - Traitées: {$processed}");
        $this->info("  - Envoyées: {$sent}");
        $this->info("  - Échouées: {$failed}");

        // Retraiter les notifications échouées qui peuvent être relancées
        $retryCount = $this->smsService->retryFailedSms();
        if ($retryCount > 0) {
            $this->info("Relancement de {$retryCount} notifications échouées...");
        }

        return 0;
    }
}

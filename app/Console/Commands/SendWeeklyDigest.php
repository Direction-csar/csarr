<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class SendWeeklyDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:weekly-digest {--force : Forcer l\'envoi même si ce n\'est pas lundi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer le digest hebdomadaire aux utilisateurs abonnés';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Vérifier si c'est lundi (ou forcer avec --force)
        if (!$this->option('force') && now()->dayOfWeek !== 1) {
            $this->info('Le digest hebdomadaire est envoyé uniquement le lundi. Utilisez --force pour forcer l\'envoi.');
            return;
        }

        $this->info('🚀 Envoi du digest hebdomadaire en cours...');

        try {
            $this->notificationService->sendWeeklyDigest();
            $this->info('✅ Digest hebdomadaire envoyé avec succès !');
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de l\'envoi du digest : ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}


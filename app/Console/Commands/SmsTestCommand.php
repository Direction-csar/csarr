<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SmsService;

class SmsTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:test {phone} {--message=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester l\'envoi de SMS';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone');
        $message = $this->option('message') ?: 'Test SMS CSAR - ' . now()->format('H:i:s');

        $this->info("Test d'envoi SMS vers: {$phone}");
        $this->info("Message: {$message}");

        try {
            $smsService = app(SmsService::class);
            
            $result = $smsService->sendSms($phone, $message, 'test');
            
            if ($result && isset($result['success']) && $result['success']) {
                $this->info('✅ SMS envoyé avec succès!');
                $this->info('Message ID: ' . ($result['message_id'] ?? 'N/A'));
                $this->info('Statut: ' . ($result['status'] ?? 'N/A'));
                
                if (isset($result['simulated']) && $result['simulated']) {
                    $this->warn('⚠️  Mode simulation activé (SMS_ENABLED=false)');
                }
            } else {
                $this->error('❌ Échec de l\'envoi SMS');
                $this->error('Réponse: ' . json_encode($result));
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur: ' . $e->getMessage());
        }

        // Afficher la configuration actuelle
        $this->newLine();
        $this->info('Configuration SMS actuelle:');
        $this->info('- Activé: ' . (config('sms.enabled') ? 'Oui' : 'Non'));
        $this->info('- Fournisseur: ' . config('sms.provider', 'Non configuré'));
        $this->info('- API URL: ' . config('sms.api_url', 'Non configuré'));
        $this->info('- Expéditeur: ' . config('sms.sender_name', 'Non configuré'));
    }
}

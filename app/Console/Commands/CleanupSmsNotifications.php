<?php

namespace App\Console\Commands;

use App\Models\SmsNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanupSmsNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:cleanup {--days=30 : Nombre de jours à conserver}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoyer les anciennes notifications SMS';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("Nettoyage des notifications SMS antérieures au {$cutoffDate->format('d/m/Y')}...");

        // Supprimer les notifications livrées ou échouées anciennes
        $deletedCount = SmsNotification::whereIn('status', [
            SmsNotification::STATUS_DELIVERED,
            SmsNotification::STATUS_FAILED
        ])
        ->where('created_at', '<', $cutoffDate)
        ->delete();

        $this->info("{$deletedCount} notifications supprimées.");

        // Afficher les statistiques actuelles
        $stats = [
            'total' => SmsNotification::count(),
            'pending' => SmsNotification::where('status', SmsNotification::STATUS_PENDING)->count(),
            'sent' => SmsNotification::where('status', SmsNotification::STATUS_SENT)->count(),
            'delivered' => SmsNotification::where('status', SmsNotification::STATUS_DELIVERED)->count(),
            'failed' => SmsNotification::where('status', SmsNotification::STATUS_FAILED)->count(),
        ];

        $this->info("Statistiques actuelles:");
        $this->table(
            ['Statut', 'Nombre'],
            [
                ['Total', $stats['total']],
                ['En attente', $stats['pending']],
                ['Envoyées', $stats['sent']],
                ['Livrées', $stats['delivered']],
                ['Échouées', $stats['failed']],
            ]
        );

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AuditLog;
use Carbon\Carbon;

class CleanAuditLogs extends Command
{
    protected $signature = 'audit:clean {--days=90 : Number of days to keep logs}';
    protected $description = 'Clean old audit logs from the database';

    public function handle()
    {
        $days = $this->option('days');
        
        $this->info("Nettoyage des logs d'audit de plus de {$days} jours...");
        
        $cutoffDate = Carbon::now()->subDays($days);
        
        $count = AuditLog::where('created_at', '<', $cutoffDate)->count();
        
        if ($count === 0) {
            $this->info('Aucun log ancien trouvé.');
            return 0;
        }
        
        if ($this->confirm("Voulez-vous supprimer {$count} logs d'audit de plus de {$days} jours?")) {
            $deleted = AuditLog::where('created_at', '<', $cutoffDate)->delete();
            
            $this->info("{$deleted} logs d'audit supprimés avec succès.");
            
            // Log cette action de nettoyage
            AuditLog::create([
                'user_id' => null,
                'action' => 'cleanup',
                'model_type' => AuditLog::class,
                'model_id' => null,
                'description' => "Nettoyage automatique: {$deleted} logs supprimés (plus de {$days} jours)",
                'old_values' => null,
                'new_values' => [
                    'deleted_count' => $deleted,
                    'cutoff_date' => $cutoffDate->toDateTimeString(),
                    'command' => 'audit:clean'
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Artisan Command',
            ]);
            
            return 0;
        }
        
        $this->info('Nettoyage annulé.');
        return 1;
    }
}


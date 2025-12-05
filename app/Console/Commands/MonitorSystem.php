<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MonitoringService;

class MonitorSystem extends Command
{
    protected $signature = 'system:monitor';
    protected $description = 'Vérifier la santé du système et créer des alertes si nécessaire';

    private $monitoring;

    public function __construct(MonitoringService $monitoring)
    {
        parent::__construct();
        $this->monitoring = $monitoring;
    }

    public function handle()
    {
        $this->info('🔍 Vérification de la santé du système...');
        
        $health = $this->monitoring->checkSystemHealth();
        
        $this->line('');
        $this->line('Status: ' . strtoupper($health['status']));
        
        if (isset($health['metrics'])) {
            $this->displayMetrics($health['metrics']);
        }
        
        if (!empty($health['alerts'])) {
            $this->warn('⚠️ Alertes:');
            foreach ($health['alerts'] as $alert) {
                $this->warn("  - {$alert}");
            }
        } else {
            $this->info('✅ Aucun problème détecté');
        }
        
        return $health['status'] === 'healthy' ? 0 : 1;
    }

    private function displayMetrics($metrics)
    {
        foreach ($metrics as $type => $data) {
            $this->line('');
            $this->info(ucfirst($type) . ':');
            
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    if (!is_array($value)) {
                        $this->line("  {$key}: {$value}");
                    }
                }
            }
        }
    }
}























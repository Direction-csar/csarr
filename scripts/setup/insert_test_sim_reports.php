<?php
/**
 * Script pour insérer des données de test dans sim_reports
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Insertion de données de test pour sim_reports ===\n\n";

try {
    // Vérifier le nombre de rapports existants
    $existingCount = DB::table('sim_reports')->count();
    echo "Nombre de rapports existants : {$existingCount}\n";
    
    if ($existingCount === 0) {
        echo "Insertion de données de test...\n";
        
        DB::table('sim_reports')->insert([
            [
                'title' => 'Rapport Financier Q1 2024',
                'description' => 'Rapport financier du premier trimestre 2024',
                'summary' => 'Analyse financière complète du premier trimestre',
                'report_type' => 'financial',
                'status' => 'published',
                'is_public' => true,
                'download_count' => 15,
                'view_count' => 45,
                'file_size' => 2048000, // 2 MB
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Rapport Opérationnel Mars 2024',
                'description' => 'Rapport opérationnel du mois de mars 2024',
                'summary' => 'Bilan opérationnel mensuel',
                'report_type' => 'operational',
                'status' => 'published',
                'is_public' => true,
                'download_count' => 8,
                'view_count' => 23,
                'file_size' => 1536000, // 1.5 MB
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Inventaire Entrepôts Avril 2024',
                'description' => 'Rapport d\'inventaire des entrepôts pour avril 2024',
                'summary' => 'État des stocks et inventaires',
                'report_type' => 'inventory',
                'status' => 'published',
                'is_public' => true,
                'download_count' => 12,
                'view_count' => 34,
                'file_size' => 3072000, // 3 MB
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Rapport Personnel 2024',
                'description' => 'Rapport sur les ressources humaines',
                'summary' => 'Analyse des effectifs et performances',
                'report_type' => 'personnel',
                'status' => 'published',
                'is_public' => true,
                'download_count' => 5,
                'view_count' => 18,
                'file_size' => 1024000, // 1 MB
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Rapport Général CSAR 2024',
                'description' => 'Rapport général d\'activité du CSAR',
                'summary' => 'Bilan général des activités',
                'report_type' => 'general',
                'status' => 'published',
                'is_public' => true,
                'download_count' => 25,
                'view_count' => 67,
                'file_size' => 5120000, // 5 MB
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
        
        echo "✓ 5 rapports de test insérés avec succès\n\n";
    } else {
        echo "✓ Des rapports existent déjà, pas d'insertion nécessaire\n\n";
    }
    
    // Afficher les rapports existants
    $reports = DB::table('sim_reports')->get();
    echo "Rapports disponibles :\n";
    foreach ($reports as $report) {
        echo "- {$report->title} ({$report->report_type}) - {$report->view_count} vues, {$report->download_count} téléchargements\n";
    }
    
    echo "\n=== Test de la page sim-reports ===\n";
    echo "Vous pouvez maintenant accéder à :\n";
    echo "- http://localhost:8000/sim-reports (public)\n";
    echo "- http://localhost:8000/admin/sim-reports (admin)\n\n";
    
    echo "Configuration terminée avec succès !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "Trace : " . $e->getTraceAsString() . "\n";
}
?>

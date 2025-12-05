<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Test de la requête sim-reports...\n";
    
    $reports = DB::table('sim_reports')
        ->where('is_public', true)
        ->where('status', 'published')
        ->get();
    
    echo "Succès ! " . $reports->count() . " rapports trouvés\n";
    
    foreach ($reports as $report) {
        echo "- " . $report->title . " (Type: " . $report->report_type . ")\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
?>
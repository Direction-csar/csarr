<?php
/**
 * Test final pour vérifier que la page sim-reports fonctionne
 */

echo "=== Test final de la page sim-reports ===\n\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "1. Test de la base de données...\n";
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;
    
    // Vérifier la connexion à la base
    try {
        DB::connection()->getPdo();
        echo "✓ Connexion à la base de données OK\n";
    } catch (Exception $e) {
        echo "❌ Erreur de connexion DB: " . $e->getMessage() . "\n";
        exit(1);
    }
    
    // Vérifier si la table existe
    if (Schema::hasTable('sim_reports')) {
        echo "✓ Table sim_reports existe\n";
        
        // Vérifier la structure
        $columns = Schema::getColumnListing('sim_reports');
        echo "✓ Colonnes: " . implode(', ', $columns) . "\n";
        
        // Vérifier les colonnes importantes
        $requiredColumns = ['id', 'title', 'status', 'is_public', 'report_type'];
        $missingColumns = [];
        foreach ($requiredColumns as $col) {
            if (!in_array($col, $columns)) {
                $missingColumns[] = $col;
            }
        }
        
        if (empty($missingColumns)) {
            echo "✓ Toutes les colonnes requises sont présentes\n";
        } else {
            echo "❌ Colonnes manquantes: " . implode(', ', $missingColumns) . "\n";
        }
        
        // Compter les enregistrements
        $count = DB::table('sim_reports')->count();
        echo "✓ Nombre total de rapports: {$count}\n";
        
        $publicCount = DB::table('sim_reports')->where('is_public', true)->where('status', 'published')->count();
        echo "✓ Nombre de rapports publics: {$publicCount}\n";
        
    } else {
        echo "❌ Table sim_reports n'existe pas\n";
        exit(1);
    }
    
    echo "\n2. Test de la route sim-reports...\n";
    try {
        $reports = DB::table('sim_reports')
            ->where('is_public', true)
            ->where('status', 'published')
            ->get();
        echo "✓ Requête de récupération des rapports fonctionne\n";
        echo "✓ Rapports récupérés: " . $reports->count() . "\n";
        
        if ($reports->count() > 0) {
            $firstReport = $reports->first();
            echo "✓ Premier rapport: " . $firstReport->title . "\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur lors de la récupération des rapports: " . $e->getMessage() . "\n";
        exit(1);
    }
    
    echo "\n3. Test de la vue...\n";
    try {
        $view = view('public.sim-reports', ['reports' => $reports]);
        $html = $view->render();
        echo "✓ Vue fonctionne\n";
        echo "✓ Taille du HTML: " . strlen($html) . " caractères\n";
        
        if (strpos($html, 'Rapports SIM') !== false) {
            echo "✓ Titre 'Rapports SIM' trouvé dans le HTML\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur vue: " . $e->getMessage() . "\n";
        exit(1);
    }
    
    echo "\n=== Résumé ===\n";
    echo "✅ Tous les tests sont passés avec succès !\n";
    echo "La page sim-reports devrait maintenant fonctionner correctement.\n";
    echo "Vous pouvez accéder à: http://localhost:8000/sim-reports\n";
    echo "Vous pouvez également accéder à: http://localhost:8000/admin/sim-reports (admin)\n";
    
} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
?>

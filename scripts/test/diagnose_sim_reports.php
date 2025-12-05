<?php
/**
 * Script de diagnostic pour identifier le problème avec sim-reports
 */

echo "=== Diagnostic de l'erreur 500 sur sim-reports ===\n\n";

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
        
        // Compter les enregistrements
        $count = DB::table('sim_reports')->count();
        echo "✓ Nombre de rapports: {$count}\n";
        
    } else {
        echo "❌ Table sim_reports n'existe pas\n";
        exit(1);
    }
    
    echo "\n2. Test du modèle SimReport...\n";
    try {
        $reports = \App\Models\SimReport::where('is_public', true)
                                      ->where('status', 'published')
                                      ->get();
        echo "✓ Modèle SimReport fonctionne\n";
        echo "✓ Rapports publics trouvés: " . $reports->count() . "\n";
    } catch (Exception $e) {
        echo "❌ Erreur modèle SimReport: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
        exit(1);
    }
    
    echo "\n3. Test du contrôleur...\n";
    try {
        $controller = new \App\Http\Controllers\Public\SimReportsController();
        $request = new \Illuminate\Http\Request();
        $response = $controller->index($request);
        echo "✓ Contrôleur fonctionne\n";
    } catch (Exception $e) {
        echo "❌ Erreur contrôleur: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
        exit(1);
    }
    
    echo "\n4. Test de la vue...\n";
    try {
        $view = view('public.sim-reports', ['reports' => $reports]);
        $html = $view->render();
        echo "✓ Vue fonctionne\n";
        echo "✓ Taille du HTML: " . strlen($html) . " caractères\n";
    } catch (Exception $e) {
        echo "❌ Erreur vue: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
        exit(1);
    }
    
    echo "\n5. Test de la route...\n";
    try {
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $simReportsRoute = null;
        foreach ($routes as $route) {
            if ($route->uri() === 'sim-reports' && $route->methods()[0] === 'GET') {
                $simReportsRoute = $route;
                break;
            }
        }
        
        if ($simReportsRoute) {
            echo "✓ Route sim-reports trouvée\n";
            echo "✓ Contrôleur: " . $simReportsRoute->getActionName() . "\n";
        } else {
            echo "❌ Route sim-reports non trouvée\n";
        }
    } catch (Exception $e) {
        echo "❌ Erreur route: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== Diagnostic terminé ===\n";
    echo "Tous les tests sont passés. Le problème pourrait être ailleurs.\n";
    echo "Vérifiez les logs d'erreur du serveur web.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>

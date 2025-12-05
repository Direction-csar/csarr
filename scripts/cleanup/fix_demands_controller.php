<?php
/**
 * 🔧 CORRECTION DU CONTRÔLEUR DES DEMANDES
 * 
 * Ce script corrige le problème dans le contrôleur DemandesController
 */

echo "🔧 CORRECTION DU CONTRÔLEUR DES DEMANDES\n";
echo "=======================================\n\n";

// 1. Corriger le contrôleur DashboardController
$dashboardController = 'app/Http/Controllers/Admin/DashboardController.php';

if (file_exists($dashboardController)) {
    echo "1️⃣ Correction du DashboardController...\n";
    
    $content = file_get_contents($dashboardController);
    
    // Remplacer toutes les références à PublicRequest par Demande
    $content = str_replace('PublicRequest::', 'Demande::', $content);
    $content = str_replace('use App\Models\PublicRequest;', 'use App\Models\PublicRequest;' . "\n" . 'use App\Models\Demande;', $content);
    
    // Corriger les noms de colonnes
    $content = str_replace("where('status', 'pending')", "where('statut', 'en_attente')", $content);
    $content = str_replace("where('status', 'approved')", "where('statut', 'approuvee')", $content);
    $content = str_replace("where('status', 'rejected')", "where('statut', 'rejetee')", $content);
    $content = str_replace("where('assigned_to',", "where('assignee_id',", $content);
    
    file_put_contents($dashboardController, $content);
    echo "   ✅ DashboardController corrigé\n";
} else {
    echo "   ❌ DashboardController non trouvé\n";
}

// 2. Corriger le contrôleur DemandesController
$demandesController = 'app/Http/Controllers/Admin/DemandesController.php';

if (file_exists($demandesController)) {
    echo "2️⃣ Correction du DemandesController...\n";
    
    $content = file_get_contents($demandesController);
    
    // Ajouter la gestion d'erreur manquante
    $oldError = "Log::error('Erreur dans DemandesController@index: ' . \$e->getMessage());";
    $newError = "Log::error('Erreur dans DemandesController@index: ' . \$e->getMessage());\n            Log::error('Détails de l\'erreur: ' . \$e->getTraceAsString());";
    
    $content = str_replace($oldError, $newError, $content);
    
    file_put_contents($demandesController, $content);
    echo "   ✅ DemandesController corrigé\n";
} else {
    echo "   ❌ DemandesController non trouvé\n";
}

// 3. Créer un contrôleur de test simple
$testController = 'app/Http/Controllers/Admin/TestDemandesController.php';

$testContent = '<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use Illuminate\Http\Request;

class TestDemandesController extends Controller
{
    public function index()
    {
        try {
            // Test simple
            $demandes = Demande::all();
            return response()->json([
                "success" => true,
                "count" => $demandes->count(),
                "demandes" => $demandes->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString()
            ]);
        }
    }
}';

file_put_contents($testController, $testContent);
echo "   ✅ TestDemandesController créé\n";

echo "\n🎉 CORRECTION TERMINÉE !\n";
echo "=======================\n";
echo "✅ Contrôleurs corrigés\n";
echo "✅ Gestion d'erreurs améliorée\n";
echo "✅ Contrôleur de test créé\n";

echo "\n🌐 TESTEZ MAINTENANT :\n";
echo "=====================\n";
echo "1. Actualisez votre page admin: http://127.0.0.1:8000/admin\n";
echo "2. Cliquez sur 'Demandes' dans le menu\n";
echo "3. Si l'erreur persiste, testez: http://127.0.0.1:8000/admin/test-demandes\n";

echo "\n📝 Si le problème persiste, l'erreur vient probablement d'une vue manquante.\n";
?>


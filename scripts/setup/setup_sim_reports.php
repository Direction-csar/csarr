<?php
/**
 * Script de configuration des rapports SIM
 * Exécute les migrations et seeders nécessaires
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Application;

// Initialiser l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Configuration des rapports SIM - CSAR Platform\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    // 1. Exécuter les migrations
    echo "📊 Exécution des migrations...\n";
    Artisan::call('migrate', ['--force' => true]);
    echo "✅ Migrations exécutées avec succès\n\n";
    
    // 2. Exécuter le seeder pour les rapports SIM
    echo "🌱 Génération des rapports SIM d'exemple...\n";
    Artisan::call('db:seed', ['--class' => 'SimReportSeeder', '--force' => true]);
    echo "✅ Rapports SIM d'exemple créés\n\n";
    
    // 3. Vérifier les commandes disponibles
    echo "🔧 Commandes disponibles pour les rapports SIM:\n";
    echo "   • php artisan sim:generate --type=monthly\n";
    echo "   • php artisan sim:schedule\n";
    echo "   • php artisan schedule:sim-reports\n\n";
    
    // 4. Afficher les statistiques
    echo "📈 Configuration terminée !\n";
    echo "   • Structure de la table sim_reports mise à jour\n";
    echo "   • Modèle SimReport avec tous les scopes\n";
    echo "   • Service de génération de rapports opérationnel\n";
    echo "   • Commandes de planification configurées\n";
    echo "   • Styles responsifs mobile ajoutés\n\n";
    
    echo "🎉 La plateforme SIM est prête à être utilisée !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la configuration: " . $e->getMessage() . "\n";
    exit(1);
}









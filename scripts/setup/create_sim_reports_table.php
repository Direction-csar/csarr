<?php
/**
 * Script pour créer la table sim_reports
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "=== Création de la table sim_reports ===\n\n";

try {
    // Vérifier si la table existe déjà
    if (Schema::hasTable('sim_reports')) {
        echo "✓ La table sim_reports existe déjà\n";
        
        // Afficher la structure de la table
        $columns = Schema::getColumnListing('sim_reports');
        echo "Colonnes existantes : " . implode(', ', $columns) . "\n\n";
    } else {
        echo "Création de la table sim_reports...\n";
        
        Schema::create('sim_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('summary')->nullable();
            $table->enum('report_type', ['financial', 'operational', 'inventory', 'personnel', 'general'])->default('general');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->enum('status', ['pending', 'generating', 'completed', 'published', 'failed', 'scheduled'])->default('pending');
            $table->string('document_file')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_public')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->bigInteger('file_size')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'is_public']);
            $table->index(['report_type', 'status']);
            $table->index('published_at');
        });
        
        echo "✓ Table sim_reports créée avec succès\n\n";
    }
    
    // Insérer quelques données de test
    $existingCount = DB::table('sim_reports')->count();
    echo "Nombre de rapports existants : {$existingCount}\n";
    
    if ($existingCount === 0) {
        echo "Insertion de données de test...\n";
        
        DB::table('sim_reports')->insert([
            [
                'title' => 'Rapport Financier Q1 2024',
                'description' => 'Rapport financier du premier trimestre 2024',
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
                'report_type' => 'inventory',
                'status' => 'published',
                'is_public' => true,
                'download_count' => 12,
                'view_count' => 34,
                'file_size' => 3072000, // 3 MB
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
        
        echo "✓ Données de test insérées\n\n";
    }
    
    echo "=== Test de la page sim-reports ===\n";
    echo "Vous pouvez maintenant accéder à :\n";
    echo "- http://localhost:8000/sim-reports (public)\n";
    echo "- http://localhost:8000/admin/sim-reports (admin)\n\n";
    
    echo "Configuration terminée avec succès !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "Trace : " . $e->getTraceAsString() . "\n";
}
?>

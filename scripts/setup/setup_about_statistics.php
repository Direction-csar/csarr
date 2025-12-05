<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

// Configuration de la base de données
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'csar_platform',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    echo "🚀 Configuration des statistiques À propos du CSAR...\n\n";
    
    // Créer la table about_statistics
    if (!Capsule::schema()->hasTable('about_statistics')) {
        echo "📊 Création de la table about_statistics...\n";
        Capsule::schema()->create('about_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('value');
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#22c55e');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
        echo "✅ Table about_statistics créée avec succès!\n\n";
    } else {
        echo "ℹ️  Table about_statistics existe déjà.\n\n";
    }
    
    // Insérer les données par défaut
    echo "📝 Insertion des statistiques par défaut...\n";
    
    $statistics = [
        [
            'title' => 'agents',
            'value' => '137',
            'icon' => 'fas fa-users',
            'description' => 'Agents recensés',
            'color' => '#22c55e',
            'is_active' => true,
            'order' => 1
        ],
        [
            'title' => 'entrepots',
            'value' => '71',
            'icon' => 'fas fa-warehouse',
            'description' => 'Magasins de stockage',
            'color' => '#3b82f6',
            'is_active' => true,
            'order' => 2
        ],
        [
            'title' => 'capacite_tonnes',
            'value' => '86',
            'icon' => 'fas fa-weight-hanging',
            'description' => 'Capacité (tonnes)',
            'color' => '#f59e0b',
            'is_active' => true,
            'order' => 3
        ],
        [
            'title' => 'regions',
            'value' => '14',
            'icon' => 'fas fa-map-marker-alt',
            'description' => 'Nombre de régions',
            'color' => '#8b5cf6',
            'is_active' => true,
            'order' => 4
        ],
        [
            'title' => 'annees_experience',
            'value' => '50',
            'icon' => 'fas fa-calendar-alt',
            'description' => 'Années d\'expérience',
            'color' => '#06b6d4',
            'is_active' => true,
            'order' => 5
        ],
        [
            'title' => 'demandes_traitees',
            'value' => '15598',
            'icon' => 'fas fa-chart-bar',
            'description' => 'Demandes',
            'color' => '#ef4444',
            'is_active' => true,
            'order' => 6
        ]
    ];
    
    foreach ($statistics as $statistic) {
        $existing = Capsule::table('about_statistics')->where('title', $statistic['title'])->first();
        
        if (!$existing) {
            $statistic['created_at'] = now();
            $statistic['updated_at'] = now();
            Capsule::table('about_statistics')->insert($statistic);
            echo "✅ Statistique '{$statistic['title']}' ajoutée\n";
        } else {
            echo "ℹ️  Statistique '{$statistic['title']}' existe déjà\n";
        }
    }
    
    echo "\n🎉 Configuration terminée avec succès!\n";
    echo "📋 Statistiques configurées:\n";
    echo "   - 137 Agents recensés\n";
    echo "   - 71 Magasins de stockage\n";
    echo "   - 86 Capacité (tonnes)\n";
    echo "   - 14 Nombre de régions\n";
    echo "   - 50 Années d'expérience\n";
    echo "   - 15 598 Demandes\n\n";
    
    echo "🔗 Accès admin: /admin/about-statistics\n";
    echo "🌐 Page publique: /fr/a-propos\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}


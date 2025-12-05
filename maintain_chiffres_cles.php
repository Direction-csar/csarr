<?php

// Script de maintenance des chiffres clés CSAR

require_once 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\ChiffreCle;

echo "🔧 Maintenance des Chiffres Clés CSAR\n";
echo str_repeat("=", 40) . "\n\n";

try {
    // Vérifier si la table existe
    if (!DB::getSchemaBuilder()->hasTable('chiffres_cles')) {
        echo "❌ Table 'chiffres_cles' n'existe pas!\n";
        echo "🔧 Création de la table...\n";
        
        DB::statement("
            CREATE TABLE chiffres_cles (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                icone VARCHAR(255) NOT NULL,
                titre VARCHAR(255) NOT NULL,
                valeur VARCHAR(255) NOT NULL,
                description TEXT,
                couleur VARCHAR(7) DEFAULT '#007bff',
                statut ENUM('Actif', 'Inactif') DEFAULT 'Actif',
                ordre INT DEFAULT 0,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        echo "✅ Table créée!\n";
    }
    
    // Vérifier le contenu
    $count = DB::table('chiffres_cles')->count();
    echo "📊 Nombre de chiffres clés: $count\n";
    
    if ($count == 0) {
        echo "🔧 Insertion des données par défaut...\n";
        
        $defaultData = [
            [
                'icone' => 'fas fa-users',
                'titre' => 'Agents mobilisés',
                'valeur' => '137',
                'description' => 'Nombre total d\'agents mobilisés sur le terrain',
                'couleur' => '#28a745',
                'statut' => 'Actif',
                'ordre' => 1
            ],
            [
                'icone' => 'fas fa-warehouse',
                'titre' => 'Entrepôts de stockage',
                'valeur' => '71',
                'description' => 'Nombre d\'entrepôts de stockage opérationnels',
                'couleur' => '#17a2b8',
                'statut' => 'Actif',
                'ordre' => 2
            ],
            [
                'icone' => 'fas fa-weight-hanging',
                'titre' => 'Capacité en tonnes',
                'valeur' => '79',
                'description' => 'Capacité totale de stockage en tonnes',
                'couleur' => '#ffc107',
                'statut' => 'Actif',
                'ordre' => 3
            ],
            [
                'icone' => 'fas fa-map-marker-alt',
                'titre' => 'Régions couvertes',
                'valeur' => '14',
                'description' => 'Nombre de régions couvertes par le CSAR',
                'couleur' => '#6f42c1',
                'statut' => 'Actif',
                'ordre' => 4
            ],
            [
                'icone' => 'fas fa-calendar-alt',
                'titre' => 'Années d\'expérience',
                'valeur' => '50',
                'description' => 'Années d\'expérience du CSAR',
                'couleur' => '#dc3545',
                'statut' => 'Actif',
                'ordre' => 5
            ],
            [
                'icone' => 'fas fa-file-alt',
                'titre' => 'Demandes traitées',
                'valeur' => '15598',
                'description' => 'Nombre total de demandes traitées',
                'couleur' => '#20c997',
                'statut' => 'Actif',
                'ordre' => 6
            ],
            [
                'icone' => 'fas fa-chart-line',
                'titre' => 'Taux de satisfaction',
                'valeur' => '94.5%',
                'description' => 'Taux de satisfaction des bénéficiaires',
                'couleur' => '#fd7e14',
                'statut' => 'Actif',
                'ordre' => 7
            ]
        ];
        
        foreach ($defaultData as $data) {
            DB::table('chiffres_cles')->insert(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
        
        echo "✅ Données par défaut insérées!\n";
    }
    
    // Afficher les chiffres clés actuels
    echo "\n📋 Chiffres clés actuels:\n";
    echo str_repeat("-", 60) . "\n";
    printf("%-3s %-20s %-15s %-10s\n", "ID", "Titre", "Valeur", "Statut");
    echo str_repeat("-", 60) . "\n";
    
    $chiffres = DB::table('chiffres_cles')->orderBy('ordre')->get();
    foreach ($chiffres as $chiffre) {
        printf("%-3s %-20s %-15s %-10s\n", 
            $chiffre->id, 
            substr($chiffre->titre, 0, 20), 
            $chiffre->valeur, 
            $chiffre->statut
        );
    }
    
    echo "\n✅ Maintenance terminée!\n";
    echo "🌐 Accès:\n";
    echo "• Administration: http://localhost:8000/admin/chiffres-cles\n";
    echo "• API: http://localhost:8000/admin/chiffres-cles/api\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

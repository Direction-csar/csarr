<?php

// Script simple pour ajouter des entrepôts de test
require_once 'vendor/autoload.php';

// Charger Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Warehouse;

echo "=== AJOUT D'ENTREPÔTS DE TEST ===\n\n";

// Vérifier s'il y a déjà des entrepôts
$existingCount = Warehouse::whereNotNull('latitude')->whereNotNull('longitude')->count();
echo "Entrepôts existants avec coordonnées: $existingCount\n\n";

if ($existingCount == 0) {
    echo "Ajout d'entrepôts de test...\n";
    
    $warehouses = [
        [
            'name' => 'Entrepôt Central CSAR',
            'address' => 'Avenue Léopold Sédar Senghor, Dakar',
            'city' => 'Dakar',
            'region' => 'Dakar',
            'latitude' => 14.7167,
            'longitude' => -17.4677,
            'capacity' => '50000',
            'is_active' => true,
            'status' => 'active'
        ],
        [
            'name' => 'Magasin de Stockage Thiès',
            'address' => 'Route de Thiès',
            'city' => 'Thiès',
            'region' => 'Thiès',
            'latitude' => 14.7894,
            'longitude' => -16.9260,
            'capacity' => '30000',
            'is_active' => true,
            'status' => 'active'
        ],
        [
            'name' => 'Entrepôt Kaolack',
            'address' => 'Centre-ville, Kaolack',
            'city' => 'Kaolack',
            'region' => 'Kaolack',
            'latitude' => 14.1510,
            'longitude' => -16.0756,
            'capacity' => '25000',
            'is_active' => true,
            'status' => 'active'
        ],
        [
            'name' => 'Entrepôt Saint-Louis',
            'address' => 'Quartier Nord, Saint-Louis',
            'city' => 'Saint-Louis',
            'region' => 'Saint-Louis',
            'latitude' => 16.0190,
            'longitude' => -16.4896,
            'capacity' => '20000',
            'is_active' => true,
            'status' => 'active'
        ],
        [
            'name' => 'Entrepôt Ziguinchor',
            'address' => 'Zone industrielle, Ziguinchor',
            'city' => 'Ziguinchor',
            'region' => 'Ziguinchor',
            'latitude' => 12.5641,
            'longitude' => -16.2635,
            'capacity' => '15000',
            'is_active' => true,
            'status' => 'active'
        ]
    ];
    
    foreach ($warehouses as $data) {
        $warehouse = new Warehouse();
        $warehouse->fill($data);
        $warehouse->save();
        echo "✅ Créé: {$warehouse->name} ({$warehouse->city})\n";
    }
    
    echo "\n🎉 Entrepôts de test ajoutés avec succès !\n";
} else {
    echo "✅ Des entrepôts existent déjà.\n";
}

// Vérification finale
$finalCount = Warehouse::whereNotNull('latitude')->whereNotNull('longitude')->where('is_active', true)->count();
echo "\n=== RÉSULTAT FINAL ===\n";
echo "Entrepôts actifs avec coordonnées: $finalCount\n";

if ($finalCount > 0) {
    echo "✅ La carte devrait maintenant afficher des marqueurs !\n";
    echo "🌐 Testez: http://localhost:8000/carte-interactive\n";
} else {
    echo "❌ Problème persistant.\n";
}









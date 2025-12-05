<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stock;
use App\Models\StockType;
use App\Models\Warehouse;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les types de stocks
        $cereales = StockType::create([
            'name' => 'cereales',
            'display_name' => 'Céréales',
            'description' => 'Produits céréaliers de base',
            'unit' => 'kg'
        ]);

        $legumineuses = StockType::create([
            'name' => 'legumineuses',
            'display_name' => 'Légumineuses',
            'description' => 'Haricots, pois, lentilles',
            'unit' => 'kg'
        ]);

        $huiles = StockType::create([
            'name' => 'huiles',
            'display_name' => 'Huiles & Matières grasses',
            'description' => 'Huiles alimentaires et beurres',
            'unit' => 'L'
        ]);

        $produits_transformes = StockType::create([
            'name' => 'produits_transformes',
            'display_name' => 'Produits transformés',
            'description' => 'Farines, sucre, sel',
            'unit' => 'kg'
        ]);

        $urgence = StockType::create([
            'name' => 'urgence',
            'display_name' => 'Produits d\'urgence',
            'description' => 'Biscuits, lait, conserves',
            'unit' => 'unités'
        ]);

        // Récupérer les magasins existants ou en créer
        $warehouse1 = Warehouse::firstOrCreate([
            'name' => 'Magasin CSAR Dakar'
        ], [
            'address' => 'Zone industrielle, Dakar',
            'city' => 'Dakar',
            'region' => 'Dakar',
            'latitude' => 14.7167,
            'longitude' => -17.4677,
            'is_active' => true
        ]);

        $warehouse2 = Warehouse::firstOrCreate([
            'name' => 'Magasin CSAR Thiès'
        ], [
            'address' => 'Route nationale, Thiès',
            'city' => 'Thiès',
            'region' => 'Thiès',
            'latitude' => 14.7833,
            'longitude' => -16.9333,
            'is_active' => true
        ]);

        $warehouse3 = Warehouse::firstOrCreate([
            'name' => 'Magasin CSAR Saint-Louis'
        ], [
            'address' => 'Quartier commercial, Saint-Louis',
            'city' => 'Saint-Louis',
            'region' => 'Saint-Louis',
            'latitude' => 16.0333,
            'longitude' => -16.5000,
            'is_active' => true
        ]);

        // Créer des stocks d'exemple
        $stocks = [
            // Magasin CSAR Dakar
            [
                'warehouse_id' => $warehouse1->id,
                'stock_type_id' => $cereales->id,
                'item_name' => 'Riz blanc',
                'description' => 'Riz blanc de qualité supérieure pour distribution alimentaire',
                'quantity' => 2500.00,
                'min_quantity' => 500.00,
                'max_quantity' => 3000.00,
                'unit_price' => 450.00,
                'supplier' => 'Société Nationale de Commerce'
            ],
            [
                'warehouse_id' => $warehouse1->id,
                'stock_type_id' => $cereales->id,
                'item_name' => 'Maïs',
                'description' => 'Maïs jaune pour consommation humaine et animale',
                'quantity' => 1800.00,
                'min_quantity' => 400.00,
                'max_quantity' => 2000.00,
                'unit_price' => 380.00,
                'supplier' => 'Coopérative Agricole Thiès'
            ],
            [
                'warehouse_id' => $warehouse1->id,
                'stock_type_id' => $huiles->id,
                'item_name' => 'Huile d\'arachide',
                'description' => 'Huile d\'arachide pure pour cuisson',
                'quantity' => 200.00,
                'min_quantity' => 50.00,
                'max_quantity' => 300.00,
                'unit_price' => 1200.00,
                'supplier' => 'Huilerie du Sénégal'
            ],
            [
                'warehouse_id' => $warehouse1->id,
                'stock_type_id' => $produits_transformes->id,
                'item_name' => 'Farine de mil',
                'description' => 'Farine de mil pour bouillie et couscous',
                'quantity' => 500.00,
                'min_quantity' => 100.00,
                'max_quantity' => 600.00,
                'unit_price' => 280.00,
                'supplier' => 'Minoterie de Dakar'
            ],

            // Magasin CSAR Thiès
            [
                'warehouse_id' => $warehouse2->id,
                'stock_type_id' => $cereales->id,
                'item_name' => 'Riz blanc',
                'description' => 'Riz blanc pour distribution régionale',
                'quantity' => 1200.00,
                'min_quantity' => 300.00,
                'max_quantity' => 1500.00,
                'unit_price' => 450.00,
                'supplier' => 'Société Nationale de Commerce'
            ],
            [
                'warehouse_id' => $warehouse2->id,
                'stock_type_id' => $legumineuses->id,
                'item_name' => 'Haricots',
                'description' => 'Haricots rouges pour nutrition',
                'quantity' => 800.00,
                'min_quantity' => 200.00,
                'max_quantity' => 1000.00,
                'unit_price' => 650.00,
                'supplier' => 'Coopérative Agricole Thiès'
            ],
            [
                'warehouse_id' => $warehouse2->id,
                'stock_type_id' => $produits_transformes->id,
                'item_name' => 'Sucre',
                'description' => 'Sucre blanc raffiné',
                'quantity' => 400.00,
                'min_quantity' => 100.00,
                'max_quantity' => 500.00,
                'unit_price' => 320.00,
                'supplier' => 'Compagnie Sucrière Sénégalaise'
            ],
            [
                'warehouse_id' => $warehouse2->id,
                'stock_type_id' => $produits_transformes->id,
                'item_name' => 'Sel',
                'description' => 'Sel iodé pour consommation',
                'quantity' => 200.00,
                'min_quantity' => 50.00,
                'max_quantity' => 250.00,
                'unit_price' => 150.00,
                'supplier' => 'Salines du Sine-Saloum'
            ],

            // Magasin CSAR Saint-Louis
            [
                'warehouse_id' => $warehouse3->id,
                'stock_type_id' => $cereales->id,
                'item_name' => 'Millet',
                'description' => 'Millet pour bouillie traditionnelle',
                'quantity' => 900.00,
                'min_quantity' => 200.00,
                'max_quantity' => 1200.00,
                'unit_price' => 350.00,
                'supplier' => 'Coopérative du Nord'
            ],
            [
                'warehouse_id' => $warehouse3->id,
                'stock_type_id' => $cereales->id,
                'item_name' => 'Sorgho',
                'description' => 'Sorgho pour alimentation locale',
                'quantity' => 600.00,
                'min_quantity' => 150.00,
                'max_quantity' => 800.00,
                'unit_price' => 320.00,
                'supplier' => 'Coopérative du Nord'
            ],
            [
                'warehouse_id' => $warehouse3->id,
                'stock_type_id' => $huiles->id,
                'item_name' => 'Huile de palme',
                'description' => 'Huile de palme rouge pour cuisine',
                'quantity' => 150.00,
                'min_quantity' => 30.00,
                'max_quantity' => 200.00,
                'unit_price' => 950.00,
                'supplier' => 'Huilerie de Saint-Louis'
            ],
            [
                'warehouse_id' => $warehouse3->id,
                'stock_type_id' => $urgence->id,
                'item_name' => 'Biscuits protéinés',
                'description' => 'Biscuits enrichis pour urgence alimentaire',
                'quantity' => 50.00,
                'min_quantity' => 10.00,
                'max_quantity' => 100.00,
                'unit_price' => 2500.00,
                'supplier' => 'Industrie Alimentaire du Sénégal'
            ],

            // Exemples de stocks en alerte (quantité faible)
            [
                'warehouse_id' => $warehouse1->id,
                'stock_type_id' => $huiles->id,
                'item_name' => 'Beurre de karité',
                'description' => 'Beurre de karité pour soins et cuisine',
                'quantity' => 25.00, // Quantité faible
                'min_quantity' => 50.00,
                'max_quantity' => 100.00,
                'unit_price' => 1800.00,
                'supplier' => 'Coopérative des Femmes'
            ],
            [
                'warehouse_id' => $warehouse2->id,
                'stock_type_id' => $legumineuses->id,
                'item_name' => 'Pois chiches',
                'description' => 'Pois chiches pour nutrition',
                'quantity' => 80.00, // Quantité faible
                'min_quantity' => 100.00,
                'max_quantity' => 500.00,
                'unit_price' => 580.00,
                'supplier' => 'Coopérative Agricole Thiès'
            ],
        ];

        foreach ($stocks as $stockData) {
            Stock::create($stockData);
        }

        $this->command->info('✅ Stocks d\'exemple créés avec succès !');
        $this->command->info('📦 Exemples ajoutés :');
        $this->command->info('   - Riz blanc, Maïs, Huile d\'arachide (Dakar)');
        $this->command->info('   - Haricots, Sucre, Sel (Thiès)');
        $this->command->info('   - Millet, Sorgho, Huile de palme (Saint-Louis)');
        $this->command->info('   - Stocks en alerte : Beurre de karité, Pois chiches');
    }
}

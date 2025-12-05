<?php

namespace Database\Seeders;

use App\Models\StockType;
use Illuminate\Database\Seeder;

class StockTypeSeeder extends Seeder
{
    public function run(): void
    {
        $stockTypes = [
            [
                'name' => 'denrees',
                'display_name' => 'Denrées Alimentaires',
                'description' => 'Produits alimentaires de base (riz, mil, maïs, etc.)',
                'unit' => 'kg'
            ],
            [
                'name' => 'carburant',
                'display_name' => 'Carburant',
                'description' => 'Essence, diesel et autres carburants',
                'unit' => 'litres'
            ],
            [
                'name' => 'materiel',
                'display_name' => 'Matériel',
                'description' => 'Équipements et matériels divers',
                'unit' => 'pièces'
            ]
        ];

        foreach ($stockTypes as $stockType) {
            StockType::create($stockType);
        }
    }
} 
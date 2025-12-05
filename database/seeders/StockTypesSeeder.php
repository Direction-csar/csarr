<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stockTypes = [
            [
                'name' => 'food',
                'display_name' => 'Denrées Alimentaires',
                'description' => 'Produits alimentaires et denrées de base',
                'unit' => 'kg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'fuel',
                'display_name' => 'Carburant',
                'description' => 'Carburant et combustibles',
                'unit' => 'litres',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'equipment',
                'display_name' => 'Matériel',
                'description' => 'Équipements et matériel divers',
                'unit' => 'pièces',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($stockTypes as $type) {
            DB::table('stock_types')->insertOrIgnore($type);
        }

        $this->command->info('Types de stock créés avec succès !');
    }
}

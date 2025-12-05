<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class CorrectSenegalWarehousesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprimer tous les entrepôts existants pour éviter les doublons
        Warehouse::truncate();
        
        // 13 régions du Sénégal (excluant Sédhiou comme demandé)
        $senegalRegions = [
            [
                'name' => 'Dakar',
                'latitude' => 14.7167,
                'longitude' => -17.4677,
                'city' => 'Dakar',
                'address' => 'Zone Industrielle, Dakar',
                'phone' => '+221 33 823 45 67',
                'email' => 'dakar@csar.sn',
                'capacity' => 15000,
                'is_active' => true,
                'region' => 'Dakar'
            ],
            [
                'name' => 'Diourbel',
                'latitude' => 14.6550,
                'longitude' => -16.2400,
                'city' => 'Diourbel',
                'address' => 'Centre ville, Diourbel',
                'phone' => '+221 33 972 12 34',
                'email' => 'diourbel@csar.sn',
                'capacity' => 8000,
                'is_active' => true,
                'region' => 'Diourbel'
            ],
            [
                'name' => 'Fatick',
                'latitude' => 14.3333,
                'longitude' => -16.4167,
                'city' => 'Fatick',
                'address' => 'Quartier administratif, Fatick',
                'phone' => '+221 33 949 56 78',
                'email' => 'fatick@csar.sn',
                'capacity' => 6000,
                'is_active' => true,
                'region' => 'Fatick'
            ],
            [
                'name' => 'Kaolack',
                'latitude' => 14.1500,
                'longitude' => -16.0833,
                'city' => 'Kaolack',
                'address' => 'Zone portuaire, Kaolack',
                'phone' => '+221 33 941 23 45',
                'email' => 'kaolack@csar.sn',
                'capacity' => 12000,
                'is_active' => true,
                'region' => 'Kaolack'
            ],
            [
                'name' => 'Kédougou',
                'latitude' => 12.5500,
                'longitude' => -12.1833,
                'city' => 'Kédougou',
                'address' => 'Centre commercial, Kédougou',
                'phone' => '+221 33 985 67 89',
                'email' => 'kedougou@csar.sn',
                'capacity' => 4000,
                'is_active' => true,
                'region' => 'Kédougou'
            ],
            [
                'name' => 'Kolda',
                'latitude' => 12.8833,
                'longitude' => -14.9500,
                'city' => 'Kolda',
                'address' => 'Quartier des affaires, Kolda',
                'phone' => '+221 33 996 78 90',
                'email' => 'kolda@csar.sn',
                'capacity' => 7000,
                'is_active' => true,
                'region' => 'Kolda'
            ],
            [
                'name' => 'Louga',
                'latitude' => 15.6167,
                'longitude' => -16.2167,
                'city' => 'Louga',
                'address' => 'Zone industrielle, Louga',
                'phone' => '+221 33 967 89 01',
                'email' => 'louga@csar.sn',
                'capacity' => 9000,
                'is_active' => true,
                'region' => 'Louga'
            ],
            [
                'name' => 'Matam',
                'latitude' => 15.6500,
                'longitude' => -13.2500,
                'city' => 'Matam',
                'address' => 'Centre administratif, Matam',
                'phone' => '+221 33 966 12 34',
                'email' => 'matam@csar.sn',
                'capacity' => 5000,
                'is_active' => true,
                'region' => 'Matam'
            ],
            [
                'name' => 'Saint-Louis',
                'latitude' => 16.0167,
                'longitude' => -16.4833,
                'city' => 'Saint-Louis',
                'address' => 'Port de Saint-Louis',
                'phone' => '+221 33 938 45 67',
                'email' => 'saintlouis@csar.sn',
                'capacity' => 10000,
                'is_active' => true,
                'region' => 'Saint-Louis'
            ],
            [
                'name' => 'Tambacounda',
                'latitude' => 13.7667,
                'longitude' => -13.6667,
                'city' => 'Tambacounda',
                'address' => 'Gare routière, Tambacounda',
                'phone' => '+221 33 981 34 56',
                'email' => 'tambacounda@csar.sn',
                'capacity' => 8000,
                'is_active' => true,
                'region' => 'Tambacounda'
            ],
            [
                'name' => 'Thiès',
                'latitude' => 14.7833,
                'longitude' => -16.9333,
                'city' => 'Thiès',
                'address' => 'Zone ferroviaire, Thiès',
                'phone' => '+221 33 951 56 78',
                'email' => 'thies@csar.sn',
                'capacity' => 11000,
                'is_active' => true,
                'region' => 'Thiès'
            ],
            [
                'name' => 'Ziguinchor',
                'latitude' => 12.5833,
                'longitude' => -16.2667,
                'city' => 'Ziguinchor',
                'address' => 'Port de Ziguinchor',
                'phone' => '+221 33 991 78 90',
                'email' => 'ziguinchor@csar.sn',
                'capacity' => 13000,
                'is_active' => true,
                'region' => 'Ziguinchor'
            ],
            [
                'name' => 'Kaffrine',
                'latitude' => 14.1167,
                'longitude' => -15.5500,
                'city' => 'Kaffrine',
                'address' => 'Centre ville, Kaffrine',
                'phone' => '+221 33 986 90 12',
                'email' => 'kaffrine@csar.sn',
                'capacity' => 6000,
                'is_active' => true,
                'region' => 'Kaffrine'
            ]
        ];

        // Créer les entrepôts
        foreach ($senegalRegions as $warehouseData) {
            Warehouse::create($warehouseData);
        }

        $this->command->info('✅ 13 entrepôts créés pour les régions du Sénégal (excluant Sédhiou)');
    }
}

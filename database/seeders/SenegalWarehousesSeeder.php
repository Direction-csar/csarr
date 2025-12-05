<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class SenegalWarehousesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 13 régions du Sénégal (excluant Sédhiou comme demandé)
        $senegalRegions = [
            [
                'name' => 'Dakar',
                'lat' => 14.7167,
                'lng' => -17.4677,
                'city' => 'Dakar',
                'address' => 'Zone Industrielle, Dakar',
                'phone' => '+221 33 823 45 67',
                'email' => 'dakar@csar.sn',
                'capacity' => 10000
            ],
            [
                'name' => 'Diourbel',
                'lat' => 14.6550,
                'lng' => -16.2400,
                'city' => 'Diourbel',
                'address' => 'Centre ville, Diourbel',
                'phone' => '+221 33 956 12 34',
                'email' => 'diourbel@csar.sn',
                'capacity' => 5000
            ],
            [
                'name' => 'Fatick',
                'lat' => 14.3370,
                'lng' => -16.4111,
                'city' => 'Fatick',
                'address' => 'Quartier administratif, Fatick',
                'phone' => '+221 33 949 56 78',
                'email' => 'fatick@csar.sn',
                'capacity' => 4000
            ],
            [
                'name' => 'Kaffrine',
                'lat' => 14.1050,
                'lng' => -15.5500,
                'city' => 'Kaffrine',
                'address' => 'Zone commerciale, Kaffrine',
                'phone' => '+221 33 867 89 01',
                'email' => 'kaffrine@csar.sn',
                'capacity' => 3500
            ],
            [
                'name' => 'Kaolack',
                'lat' => 14.1825,
                'lng' => -16.2533,
                'city' => 'Kaolack',
                'address' => 'Port de Kaolack',
                'phone' => '+221 33 941 23 45',
                'email' => 'kaolack@csar.sn',
                'capacity' => 8000
            ],
            [
                'name' => 'Kédougou',
                'lat' => 12.5530,
                'lng' => -12.1788,
                'city' => 'Kédougou',
                'address' => 'Quartier administratif, Kédougou',
                'phone' => '+221 33 985 67 89',
                'email' => 'kedougou@csar.sn',
                'capacity' => 3000
            ],
            [
                'name' => 'Kolda',
                'lat' => 12.8833,
                'lng' => -14.9500,
                'city' => 'Kolda',
                'address' => 'Centre ville, Kolda',
                'phone' => '+221 33 996 34 56',
                'email' => 'kolda@csar.sn',
                'capacity' => 4500
            ],
            [
                'name' => 'Louga',
                'lat' => 15.6100,
                'lng' => -16.2250,
                'city' => 'Louga',
                'address' => 'Zone industrielle, Louga',
                'phone' => '+221 33 967 45 67',
                'email' => 'louga@csar.sn',
                'capacity' => 6000
            ],
            [
                'name' => 'Matam',
                'lat' => 15.6559,
                'lng' => -13.2554,
                'city' => 'Matam',
                'address' => 'Quartier administratif, Matam',
                'phone' => '+221 33 966 78 90',
                'email' => 'matam@csar.sn',
                'capacity' => 4000
            ],
            [
                'name' => 'Saint-Louis',
                'lat' => 16.0179,
                'lng' => -16.4896,
                'city' => 'Saint-Louis',
                'address' => 'Port de Saint-Louis',
                'phone' => '+221 33 961 12 34',
                'email' => 'saintlouis@csar.sn',
                'capacity' => 7000
            ],
            [
                'name' => 'Tambacounda',
                'lat' => 13.7700,
                'lng' => -13.6700,
                'city' => 'Tambacounda',
                'address' => 'Centre ville, Tambacounda',
                'phone' => '+221 33 981 56 78',
                'email' => 'tambacounda@csar.sn',
                'capacity' => 5500
            ],
            [
                'name' => 'Thiès',
                'lat' => 14.7900,
                'lng' => -16.9300,
                'city' => 'Thiès',
                'address' => 'Zone industrielle, Thiès',
                'phone' => '+221 33 951 90 12',
                'email' => 'thies@csar.sn',
                'capacity' => 7500
            ],
            [
                'name' => 'Ziguinchor',
                'lat' => 12.5590,
                'lng' => -16.2734,
                'city' => 'Ziguinchor',
                'address' => 'Port de Ziguinchor',
                'phone' => '+221 33 991 34 56',
                'email' => 'ziguinchor@csar.sn',
                'capacity' => 6500
            ]
        ];

        foreach ($senegalRegions as $region) {
            Warehouse::updateOrCreate(
                ['name' => 'Entrepôt CSAR ' . $region['name']],
                [
                    'description' => 'Entrepôt régional CSAR - ' . $region['name'],
                    'address' => $region['address'],
                    'latitude' => $region['lat'],
                    'longitude' => $region['lng'],
                    'region' => $region['name'],
                    'city' => $region['city'],
                    'phone' => $region['phone'],
                    'email' => $region['email'],
                    'capacity' => $region['capacity'],
                    'is_active' => true,
                    'status' => 'active',
                    'current_stock' => rand(500, $region['capacity'] * 0.8), // Stock aléatoire entre 500 et 80% de la capacité
                ]
            );
        }

        $this->command->info('✅ 13 entrepôts CSAR créés avec succès !');
        $this->command->info('📋 Régions incluses: Dakar, Diourbel, Fatick, Kaffrine, Kaolack, Kédougou, Kolda, Louga, Matam, Saint-Louis, Tambacounda, Thiès, Ziguinchor');
        $this->command->info('❌ Sédhiou exclu comme demandé');
    }
}


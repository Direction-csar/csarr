<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use Illuminate\Support\Str;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['name' => 'Dakar', 'lat' => 14.7167, 'lng' => -17.4677],
            ['name' => 'Diourbel', 'lat' => 14.6550, 'lng' => -16.2400],
            ['name' => 'Fatick', 'lat' => 14.3370, 'lng' => -16.4111],
            ['name' => 'Kaffrine', 'lat' => 14.1050, 'lng' => -15.5500],
            ['name' => 'Kaolack', 'lat' => 14.1825, 'lng' => -16.2533],
            ['name' => 'Kédougou', 'lat' => 12.5530, 'lng' => -12.1788],
            ['name' => 'Kolda', 'lat' => 12.8833, 'lng' => -14.9500],
            ['name' => 'Louga', 'lat' => 15.6100, 'lng' => -16.2250],
            ['name' => 'Matam', 'lat' => 15.6559, 'lng' => -13.2554],
            ['name' => 'Saint-Louis', 'lat' => 16.0179, 'lng' => -16.4896],
            ['name' => 'Sédhiou', 'lat' => 12.7081, 'lng' => -15.5569],
            ['name' => 'Tambacounda', 'lat' => 13.7700, 'lng' => -13.6700],
            ['name' => 'Thiès', 'lat' => 14.7900, 'lng' => -16.9300],
            ['name' => 'Ziguinchor', 'lat' => 12.5590, 'lng' => -16.2734],
        ];

        foreach ($regions as $r) {
            Warehouse::updateOrCreate(
                ['name' => 'Magasin CSAR ' . $r['name']],
                [
                    'description' => 'Entrepôt régional CSAR - Données géo modifiables',
                    'address' => 'Adresse centrale',
                    'latitude' => $r['lat'],
                    'longitude' => $r['lng'],
                    'region' => $r['name'],
                    'city' => $r['name'],
                    'capacity' => 0,
                    'is_active' => true,
                    'status' => 'active',
                ]
            );
        }
    }
} 
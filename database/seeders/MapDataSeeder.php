<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PublicRequest;
use App\Models\Warehouse;

class MapDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Coordonnées des principales villes du Sénégal
        $senegalCities = [
            ['name' => 'Dakar', 'lat' => 14.7167, 'lng' => -17.4677],
            ['name' => 'Thiès', 'lat' => 14.7833, 'lng' => -16.9333],
            ['name' => 'Kaolack', 'lat' => 14.1500, 'lng' => -16.0833],
            ['name' => 'Saint-Louis', 'lat' => 16.0333, 'lng' => -16.5000],
            ['name' => 'Ziguinchor', 'lat' => 12.5833, 'lng' => -16.2667],
            ['name' => 'Diourbel', 'lat' => 14.6500, 'lng' => -16.2333],
            ['name' => 'Fatick', 'lat' => 14.3333, 'lng' => -16.4167],
            ['name' => 'Kolda', 'lat' => 12.8833, 'lng' => -14.9500],
            ['name' => 'Louga', 'lat' => 15.6167, 'lng' => -16.2167],
            ['name' => 'Tambacounda', 'lat' => 13.7667, 'lng' => -13.6667],
            ['name' => 'Matam', 'lat' => 15.6667, 'lng' => -13.2500],
            ['name' => 'Kaffrine', 'lat' => 14.1167, 'lng' => -15.5500],
            ['name' => 'Kédougou', 'lat' => 12.5500, 'lng' => -12.1833],
            ['name' => 'Sédhiou', 'lat' => 12.7000, 'lng' => -15.5500],
        ];

        // Ajouter des demandes avec géolocalisation
        $requestTypes = ['Nourriture', 'Carburant', 'Médicaments', 'Vêtements', 'Abri'];
        $statuses = ['pending', 'approved', 'rejected'];
        
        for ($i = 0; $i < 20; $i++) {
            $city = $senegalCities[array_rand($senegalCities)];
            $status = $statuses[array_rand($statuses)];
            
            PublicRequest::create([
                'tracking_code' => 'CSAR-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'type' => $requestTypes[array_rand($requestTypes)],
                'status' => $status,
                'full_name' => 'Demande ' . ($i + 1),
                'phone' => '+221 77 ' . rand(1000000, 9999999),
                'email' => 'demande' . ($i + 1) . '@example.com',
                'address' => 'Adresse ' . ($i + 1) . ', ' . $city['name'],
                'latitude' => $city['lat'] + (rand(-50, 50) / 1000), // Variation de ±0.05 degrés
                'longitude' => $city['lng'] + (rand(-50, 50) / 1000),
                'region' => $city['name'],
                'description' => 'Description de la demande ' . ($i + 1),
                'request_date' => now()->subDays(rand(1, 30)),
                'sms_sent' => rand(0, 1)
            ]);
        }

        // Ajouter des entrepôts avec géolocalisation
        $warehouseTypes = ['Nourriture', 'Carburant', 'Médicaments', 'Polyvalent'];
        
        for ($i = 0; $i < 15; $i++) {
            $city = $senegalCities[array_rand($senegalCities)];
            $isActive = rand(0, 1);
            
            Warehouse::create([
                'name' => 'Entrepôt ' . $warehouseTypes[array_rand($warehouseTypes)] . ' ' . $city['name'],
                'description' => 'Entrepôt de stockage à ' . $city['name'],
                'address' => 'Zone industrielle, ' . $city['name'],
                'latitude' => $city['lat'] + (rand(-30, 30) / 1000),
                'longitude' => $city['lng'] + (rand(-30, 30) / 1000),
                'region' => $city['name'],
                'city' => $city['name'],
                'phone' => '+221 33 ' . rand(1000000, 9999999),
                'email' => 'entrepot' . ($i + 1) . '@csar.sn',
                'is_active' => $isActive,
                'capacity' => rand(1000, 10000),
                'current_stock' => rand(100, 8000),
                'status' => $isActive ? 'active' : 'inactive'
            ]);
        }

        $this->command->info('Données de carte interactive créées avec succès !');
        $this->command->info('- 20 demandes avec géolocalisation');
        $this->command->info('- 15 entrepôts avec géolocalisation');
    }
}

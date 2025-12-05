<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Statistics;

class StatisticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statistics = [
            [
                'key' => 'agents_count',
                'title' => 'Agents mobilisés',
                'description' => 'Agents mobilisés',
                'value' => '137',
                'icon' => 'fas fa-users',
                'color' => '#22c55e',
                'section' => 'about',
                'order' => 1,
                'is_active' => true,
                'notes' => 'Nombre total d\'agents du CSAR'
            ],
            [
                'key' => 'warehouses_count',
                'title' => 'Entrepôts de stockage',
                'description' => 'Entrepôts de stockage',
                'value' => '71',
                'icon' => 'fas fa-warehouse',
                'color' => '#3b82f6',
                'section' => 'about',
                'order' => 2,
                'is_active' => true,
                'notes' => 'Nombre d\'entrepôts de stockage stratégique'
            ],
            [
                'key' => 'capacity_tonnes',
                'title' => 'Capacité en tonnes',
                'description' => 'Tonnes de capacité',
                'value' => '79',
                'icon' => 'fas fa-weight-hanging',
                'color' => '#f59e0b',
                'section' => 'about',
                'order' => 3,
                'is_active' => true,
                'notes' => 'Capacité totale de stockage en tonnes'
            ],
            [
                'key' => 'regions_count',
                'title' => 'Régions couvertes',
                'description' => 'Régions couvertes',
                'value' => '50+',
                'icon' => 'fas fa-map-marker-alt',
                'color' => '#8b5cf6',
                'section' => 'about',
                'order' => 4,
                'is_active' => true,
                'notes' => 'Nombre de régions couvertes par le CSAR'
            ],
            [
                'key' => 'experience_years',
                'title' => 'Années d\'expérience',
                'description' => 'Années d\'expérience',
                'value' => '50',
                'icon' => 'fas fa-calendar-alt',
                'color' => '#06b6d4',
                'section' => 'about',
                'order' => 5,
                'is_active' => true,
                'notes' => 'Années d\'expérience du CSAR'
            ],
            [
                'key' => 'requests_processed',
                'title' => 'Demandes traitées',
                'description' => 'Nombre de demandes traitées',
                'value' => '15598',
                'icon' => 'fas fa-chart-bar',
                'color' => '#ef4444',
                'section' => 'about',
                'order' => 6,
                'is_active' => true,
                'notes' => 'Nombre total de demandes traitées'
            ],
            [
                'key' => 'satisfaction_rate',
                'title' => 'Taux de satisfaction',
                'description' => 'Taux de satisfaction client',
                'value' => '94.5%',
                'icon' => 'fas fa-star',
                'color' => '#10b981',
                'section' => 'about',
                'order' => 7,
                'is_active' => true,
                'notes' => 'Taux de satisfaction des clients'
            ]
        ];

        foreach ($statistics as $statistic) {
            Statistics::updateOrCreate(
                ['key' => $statistic['key']],
                $statistic
            );
        }

        $this->command->info('Statistiques par défaut créées avec succès !');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutStatistic;

class AboutStatisticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statistics = [
            [
                'title' => 'agents',
                'value' => '137',
                'icon' => 'fas fa-users',
                'description' => 'Agents recensés',
                'color' => '#22c55e',
                'is_active' => true,
                'order' => 1
            ],
            [
                'title' => 'entrepots',
                'value' => '71',
                'icon' => 'fas fa-warehouse',
                'description' => 'Magasins de stockage',
                'color' => '#3b82f6',
                'is_active' => true,
                'order' => 2
            ],
            [
                'title' => 'capacite_tonnes',
                'value' => '86',
                'icon' => 'fas fa-weight-hanging',
                'description' => 'Capacité (tonnes)',
                'color' => '#f59e0b',
                'is_active' => true,
                'order' => 3
            ],
            [
                'title' => 'regions',
                'value' => '14',
                'icon' => 'fas fa-map-marker-alt',
                'description' => 'Nombre de régions',
                'color' => '#8b5cf6',
                'is_active' => true,
                'order' => 4
            ],
            [
                'title' => 'annees_experience',
                'value' => '50',
                'icon' => 'fas fa-calendar-alt',
                'description' => 'Années d\'expérience',
                'color' => '#06b6d4',
                'is_active' => true,
                'order' => 5
            ],
            [
                'title' => 'demandes_traitees',
                'value' => '15598',
                'icon' => 'fas fa-chart-bar',
                'description' => 'Demandes',
                'color' => '#ef4444',
                'is_active' => true,
                'order' => 6
            ]
        ];

        foreach ($statistics as $statistic) {
            AboutStatistic::updateOrCreate(
                ['title' => $statistic['title']],
                $statistic
            );
        }
    }
}


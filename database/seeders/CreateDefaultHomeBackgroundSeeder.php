<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateDefaultHomeBackgroundSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier si des arrière-plans existent déjà
        $existingBackgrounds = DB::table('home_backgrounds')->count();
        if ($existingBackgrounds > 0) {
            echo "Des arrière-plans existent déjà, skip...\n";
            return;
        }

        // Créer un arrière-plan d'accueil par défaut
        DB::table('home_backgrounds')->insert([
            'title' => 'Bienvenue sur CSAR Platform',
            'description' => 'Plateforme de gestion des stocks et ressources alimentaires',
            'image_path' => 'csar-logo.jpg',
            'is_active' => true,
            'display_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo "✅ Arrière-plan d'accueil créé avec succès !\n";
        echo "🎨 Image: csar-logo.jpg\n";
    }
}


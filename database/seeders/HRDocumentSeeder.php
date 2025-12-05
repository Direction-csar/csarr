<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HRDocument;
use App\Models\Personnel;
use App\Models\User;
use Carbon\Carbon;

class HRDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer le premier personnel et admin
        $personnel = Personnel::first();
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        $admin = $adminRole ? User::where('role_id', $adminRole->id)->first() : User::first();

        if (!$personnel || !$admin) {
            return;
        }

        // Créer des documents RH de test
        $documents = [
            [
                'personnel_id' => $personnel->id,
                'type' => 'contrat_travail',
                'titre' => 'Contrat de travail à durée indéterminée',
                'description' => 'Contrat de travail signé lors du recrutement',
                'fichier' => 'contrat_travail_' . time() . '.pdf',
                'extension' => 'pdf',
                'taille_fichier' => 1024000,
                'date_emission' => Carbon::now()->subMonths(6),
                'date_expiration' => null,
                'statut' => 'actif',
                'commentaires' => 'Contrat en cours de validité',
                'cree_par' => $admin->id
            ],
            [
                'personnel_id' => $personnel->id,
                'type' => 'certificat_medical',
                'titre' => 'Certificat médical d\'aptitude',
                'description' => 'Certificat médical pour l\'embauche',
                'fichier' => 'certificat_medical_' . time() . '.pdf',
                'extension' => 'pdf',
                'taille_fichier' => 512000,
                'date_emission' => Carbon::now()->subMonths(5),
                'date_expiration' => Carbon::now()->addMonths(7),
                'statut' => 'actif',
                'commentaires' => 'Certificat valide pour 1 an',
                'cree_par' => $admin->id
            ],
            [
                'personnel_id' => $personnel->id,
                'type' => 'attestation_travail',
                'titre' => 'Attestation de travail',
                'description' => 'Attestation pour démarches administratives',
                'fichier' => 'attestation_travail_' . time() . '.pdf',
                'extension' => 'pdf',
                'taille_fichier' => 256000,
                'date_emission' => Carbon::now()->subMonths(2),
                'date_expiration' => null,
                'statut' => 'actif',
                'commentaires' => 'Attestation sans date d\'expiration',
                'cree_par' => $admin->id
            ],
            [
                'personnel_id' => $personnel->id,
                'type' => 'certificat_formation',
                'titre' => 'Certificat de formation Excel',
                'description' => 'Certificat de formation en bureautique',
                'fichier' => 'certificat_formation_' . time() . '.pdf',
                'extension' => 'pdf',
                'taille_fichier' => 768000,
                'date_emission' => Carbon::now()->subMonths(1),
                'date_expiration' => Carbon::now()->addYears(2),
                'statut' => 'actif',
                'commentaires' => 'Formation validée avec succès',
                'cree_par' => $admin->id
            ]
        ];

        foreach ($documents as $document) {
            HRDocument::create($document);
        }

        $this->command->info('Documents RH créés avec succès !');
    }
}


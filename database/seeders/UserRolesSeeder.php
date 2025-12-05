<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer ou mettre à jour l'utilisateur Admin
        User::updateOrCreate(
            ['email' => 'admin@csar.sn'],
            [
                'name' => 'Administrateur CSAR',
                'password' => Hash::make('password'),
                'role_id' => 1, // Admin
                'email_verified_at' => now(),
            ]
        );

        // Créer ou mettre à jour l'utilisateur DG
        User::updateOrCreate(
            ['email' => 'dg@csar.sn'],
            [
                'name' => 'Directeur Général',
                'password' => Hash::make('password'),
                'role_id' => 2, // DG
                'email_verified_at' => now(),
            ]
        );

        // Créer ou mettre à jour l'utilisateur Responsable d'entrepôt
        User::updateOrCreate(
            ['email' => 'responsable@csar.sn'],
            [
                'name' => 'Responsable Entrepôt Dakar',
                'password' => Hash::make('password'),
                'role_id' => 3, // Responsable
                'email_verified_at' => now(),
            ]
        );

        // Créer ou mettre à jour l'utilisateur Agent CSAR
        User::updateOrCreate(
            ['email' => 'agent@csar.sn'],
            [
                'name' => 'Agent CSAR',
                'password' => Hash::make('password'),
                'role_id' => 4, // Agent
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Utilisateurs de test créés/mis à jour avec succès !');
        $this->command->info('Admin: admin@csar.sn / password');
        $this->command->info('DG: dg@csar.sn / password');
        $this->command->info('Responsable: responsable@csar.sn / password');
        $this->command->info('Agent: agent@csar.sn / password');
    }
}

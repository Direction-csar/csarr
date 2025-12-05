<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::all()->keyBy('name');

        // Comptes principaux
        User::updateOrCreate(
            ['email' => 'admin@csar.sn'],
            [
                'name' => 'Administrateur CSAR',
                'password' => Hash::make('password'),
                'role_id' => $roles['admin']->id,
                'phone' => '+221701234567',
                'position' => 'Administrateur Système',
                'department' => 'Direction Générale',
            ]
        );

        User::updateOrCreate(
            ['email' => 'dg@csar.sn'],
            [
                'name' => 'Directrice Générale',
                'password' => Hash::make('password'),
                'role_id' => $roles['dg']->id,
                'phone' => '+221701234568',
                'position' => 'Directrice Générale',
                'department' => 'Direction Générale',
            ]
        );

        User::updateOrCreate(
            ['email' => 'responsable@csar.sn'],
            [
                'name' => 'Responsable Entrepôt Dakar',
                'password' => Hash::make('password'),
                'role_id' => $roles['responsable']->id,
                'phone' => '+221701234569',
                'position' => 'Responsable Entrepôt',
                'department' => 'Logistique',
            ]
        );

        User::updateOrCreate(
            ['email' => 'agent@csar.sn'],
            [
                'name' => 'Agent CSAR',
                'password' => Hash::make('password'),
                'role_id' => $roles['agent']->id,
                'phone' => '+221701234570',
                'position' => 'Agent de Terrain',
                'department' => 'Opérations',
            ]
        );

        
}
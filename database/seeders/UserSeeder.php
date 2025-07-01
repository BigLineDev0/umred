<?php

namespace Database\Seeders;

use Faker\Guesser\Name;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Utilisateur Admin
        $admin = User::create([
            'name' => 'Administrateur',
            'prenom' => 'Système',
            'email' => 'admin@umred.sn',
            'password' => Hash::make('password'),
            'telephone' => '77 123 45 67',
            'adresse' => 'UFR Santé, Thiès',
        ]);
        $admin->assignRole('admin');

        // Utilisateur Chercheur
        $chercheur = User::create([
            'name' => 'Soumoundou',
            'prenom' => 'Mamadou',
            'email' => 'chercheur@umred.sn',
            'password' => Hash::make('password'),
            'telephone' => '77 234 56 78',
            'adresse' => 'Thiès',
        ]);
        $chercheur->assignRole('chercheur');

        // Utilisateur Technicien
        $technicien = User::create([
            'name' => 'Ndiaye',
            'prenom' => 'Ousmane',
            'email' => 'technicien@umred.sn',
            'password' => Hash::make('password'),
            'telephone' => '77 345 67 89',
            'adresse' => 'Thiès',
        ]);
        $technicien->assignRole('technicien');
    }
}

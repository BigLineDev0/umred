<?php

namespace Database\Seeders;

use App\Models\Laboratoire;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LaboratoireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Laboratoire::create([
            'nom' => 'Laboratoire de Biologie Moléculaire',
            'description' => 'Laboratoire spécialisé en biologie moléculaire et génétique',
            'localisation' => 'Bâtiment A - Étage 2',
            'statut' => 'actif',
        ]);

        Laboratoire::create([
            'nom' => 'Laboratoire de Microbiologie',
            'description' => 'Laboratoire de microbiologie et immunologie',
            'localisation' => 'Bâtiment B - Étage 1',
            'statut' => 'actif',
        ]);
        
        Laboratoire::create([
            'nom' => 'Laboratoire de Chimie Analytique',
            'description' => 'Laboratoire dédié à la chimie analytique et organique',
            'localisation' => 'Bâtiment C - Étage 3',
            'statut' => 'actif',
        ]);

        Laboratoire::create([
            'nom' => 'Laboratoire de Physique Appliquée',
            'description' => 'Laboratoire de recherche en physique appliquée',
            'localisation' => 'Bâtiment D - Étage 2',
            'statut' => 'actif',
        ]);
    }
}

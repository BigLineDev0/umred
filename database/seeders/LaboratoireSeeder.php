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
            'nom' => 'Laboratoire de chimie organique minérale et chimie thérapeutique',
            'description' => 'Aucune description disponible',
            'localisation' => 'Bâtiment A - Étage 2',
            'statut' => 'actif',
        ]);

        Laboratoire::create([
            'nom' => 'Laboratoire biophysique galénique',
            'description' => 'Aucune description disponible',
            'localisation' => 'Bâtiment B - Étage 1',
            'statut' => 'actif',
        ]);
        
        Laboratoire::create([
            'nom' => 'Laboratoire biologie végétale botanique et pharmagnosie',
            'description' => 'Aucune description disponible',
            'localisation' => 'Bâtiment C - Étage 3',
            'statut' => 'actif',
        ]);

        Laboratoire::create([
            'nom' => 'Laboratoire biologie moléculaire immunologie et génétique',
            'description' => 'Aucune description disponible',
            'localisation' => 'Bâtiment D - Étage 2',
            'statut' => 'actif',
        ]);

        Laboratoire::create([
            'nom' => 'Laboratoire Parasitologie-Bactériologie',
            'description' => 'Aucune description disponible',
            'localisation' => 'Bâtiment D - Étage 2',
            'statut' => 'actif',
        ]);

        Laboratoire::create([
            'nom' => 'Laboratoire anatomo-pathologie',
            'description' => 'Aucune description disponible',
            'localisation' => 'Bâtiment D - Étage 2',
            'statut' => 'actif',
        ]);
    }
}

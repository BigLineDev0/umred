<?php

namespace Database\Seeders;

use App\Models\Equipement;
use Illuminate\Database\Seeder;

class EquipementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Équipement 1
        $eq1 = Equipement::create([
            'nom' => 'Hotte biologique',
            'description' => 'Microscope électronique à transmission haute résolution',
            'statut' => 'disponible',
        ]);
        $eq1->laboratoires()->attach([1]); // associé au laboratoire ID 1

        // Équipement 2
        $eq2 = Equipement::create([
            'nom' => 'PCR Thermocycler',
            'description' => 'Thermocycleur pour amplification PCR',
            'statut' => 'disponible',
        ]);
        $eq2->laboratoires()->attach([1, 2]); // aussi au labo 1, 2

        // Équipement 3
        $eq3 = Equipement::create([
            'nom' => 'Autoclave',
            'description' => 'Stérilisateur à vapeur',
            'statut' => 'disponible',
        ]);
        $eq3->laboratoires()->attach([2]); // labo 2

        // Équipement 4
        $eq4 = Equipement::create([
            'nom' => 'Microscope test',
            'description' => 'test equipement',
            'statut' => 'maintenance',
        ]);
        $eq4->laboratoires()->attach([2, 3]); // labo 2, 3

        // Équipement 5
        $eq5 = Equipement::create([
            'nom' => 'Centrifugeuse',
            'description' => 'Centrifugeuse de laboratoire pour séparation des échantillons',
            'statut' => 'maintenance',
        ]);
        $eq5->laboratoires()->attach([1, 3]); // labo 1, 3

        // Équipement 6
        $eq6 = Equipement::create([
            'nom' => 'Spectrophotomètre',
            'description' => 'Appareil pour mesurer l’absorbance des solutions',
            'statut' => 'maintenance',
        ]);
        $eq6->laboratoires()->attach([3]); // labo 3

        // Équipement 7
        $eq6 = Equipement::create([
            'nom' => 'Spectrophotomètre',
            'description' => 'Appareil pour mesurer l’absorbance des solutions',
            'statut' => 'reserve',
        ]);
        $eq6->laboratoires()->attach([3]); // labo 3
    }
}

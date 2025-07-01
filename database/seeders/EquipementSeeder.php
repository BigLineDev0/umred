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
            'nom' => 'Microscope électronique',
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
    }
}

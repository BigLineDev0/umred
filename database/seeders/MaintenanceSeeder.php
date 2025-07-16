<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance;
use App\Models\User;
use App\Models\Equipement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On récupère des techniciens (par rôle si tu utilises spatie/laravel-permission)
        $techniciens = User::role('technicien')->get();
        $equipements = Equipement::where('statut', 'maintenance')->get();

        if ($techniciens->isEmpty() || $equipements->isEmpty()) {
            $this->command->warn('Aucun technicien ou équipement disponible pour les maintenances.');
            return;
        }

        // Générer 10 maintenances aléatoires
        for ($i = 0; $i < 10; $i++) {
            Maintenance::create([
                'user_id' => $techniciens->random()->id,
                'equipement_id' => $equipements->random()->id,
                'description' => 'Maintenance ' . Str::random(10),
                'date_prevue' => now()->addDays(rand(-10, 10)),
                'statut' => collect(['en_cours', 'terminée'])->random(),
            ]);
        }
    }
}

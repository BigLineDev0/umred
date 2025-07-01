<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Equipement;
use App\Models\HoraireReservation;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        // Créer 10 réservations
        Reservation::factory()->count(10)->create()->each(function ($reservation) {

            // Associer 1 à 3 équipements disponibles du laboratoire
            $equipements = Equipement::whereHas('laboratoires', function ($query) use ($reservation) {
                $query->where('laboratoire_id', $reservation->laboratoire_id);
            })->where('statut', 'disponible')->inRandomOrder()->take(rand(1, 3))->pluck('id');

            $reservation->equipements()->attach($equipements);

            // Créer 1 à 3 horaires
            for ($i = 0; $i < rand(1, 3); $i++) {
                $heureDebut = now()->setTime(rand(8, 16), 0, 0);
                HoraireReservation::create([
                    'reservation_id' => $reservation->id,
                    'heure_debut' => $heureDebut,
                    'heure_fin' => $heureDebut->copy()->addHour(),
                ]);
            }
        });
    }
}

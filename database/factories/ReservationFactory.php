<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Laboratoire;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'objectif' => $this->faker->sentence(5),
            'statut' => $this->faker->randomElement(['en_attente', 'confirmée', 'annulée', 'terminée']),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'laboratoire_id' => Laboratoire::inRandomOrder()->first()?->id ?? Laboratoire::factory(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Ville;
use App\Models\Commune;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Commune>
 */
class CommuneFactory extends Factory
{
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'nom' => $this->faker->city(),
            // Crée automatiquement une ville liée s'il n'y en a pas d'indiquée lors de la création
            'ville_id' => Ville::factory(),
        ];
    }
}

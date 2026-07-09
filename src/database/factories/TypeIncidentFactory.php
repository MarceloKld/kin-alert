<?php

namespace Database\Factories;

use App\Models\TypeIncident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TypeIncident>
 */
class TypeIncidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre' => $this->faker->word,
        'description' => $this->faker->sentence
        ];
    }
}

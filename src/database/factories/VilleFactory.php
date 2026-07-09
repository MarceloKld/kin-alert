<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ville;

class VilleFactory extends Factory
{
    protected $model = Ville::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->city()
        ];
    }
}
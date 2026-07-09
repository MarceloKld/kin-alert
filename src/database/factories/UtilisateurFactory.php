<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Utilisateur;

class UtilisateurFactory extends Factory
{
    protected $model = Utilisateur::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'mot_de_passe' => bcrypt('123456789'),
            'telephone' => '0817655587',
            'role' => 'utilisateur',
            'statut' => 'actif'
        ];
    }
}
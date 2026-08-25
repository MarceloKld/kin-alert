<?php

namespace App\Policies;

use App\Models\Utilisateur;

class VillePolicy
{
    /**
     * Autoriser la création de villes aux administrateurs et gestionnaires
     */
    public function create(Utilisateur $user): bool
    {
        return in_array($user->role, ['administrateur', 'gestionnaire']);
    }
}
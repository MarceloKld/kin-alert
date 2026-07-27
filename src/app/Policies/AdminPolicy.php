<?php

namespace App\Policies;

use App\Models\Utilisateur;

class AdminPolicy
{
    public function accessAdmin(Utilisateur $user): bool
    {
        return in_array($user->role, ['administrateur', 'gestionnaire']);
    }

    public function adminOnly(Utilisateur $user): bool
    {
        return $user->role === 'administrateur';
    }
}
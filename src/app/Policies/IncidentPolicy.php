<?php

namespace App\Policies;

use App\Models\Incident;
use App\Models\Utilisateur;

/**
 * IncidentPolicy
 * 
 * Définit les règles d'autorisation
 */
class IncidentPolicy
{
    /**
     * Tout utilisateur connecté peut voir la liste
     */
    public function viewAny(Utilisateur $user): bool
    {
        return true;
    }

    /**
     * Voir un incident spécifique
     */
    public function view(Utilisateur $user, Incident $incident): bool
    {
        return true;
    }

    /**
     * Création autorisée pour tout utilisateur actif
     */
    public function create(Utilisateur $user): bool
    {
        return $user->statut === 'actif';
    }

    /**
     * Modifier un incident
     * 
     * ✅ Admin → tout modifier
     * ✅ Auteur → modifier son propre incident
     */
    public function update(Utilisateur $user, Incident $incident): bool
    {
        if ($user->role === 'administrateur') {
            return true;
        }

        return $incident->utilisateur_id === $user->id;
    }

    /**
     * Supprimer un incident
     */
    public function delete(Utilisateur $user, Incident $incident): bool
    {
        if ($user->role === 'administrateur') {
            return true;
        }

        return $incident->utilisateur_id === $user->id;
    }

    /**
 * Rejeter un incident
 */
public function reject(Utilisateur $user, Incident $incident): bool
{
    return in_array($user->role, ['administrateur', 'gestionnaire']);
}

}
<?php

namespace App\Services;
use App\Exceptions\AlreadyConfirmedException;
use App\Events\IncidentValidated;
use App\Models\Incident;
use App\Models\Confirmation;
use App\Models\HistoriqueStatut;
use Illuminate\Support\Facades\DB;

class ConfirmationService
{
    /**
     * Nombre de confirmations nécessaires
     */
    const SEUIL_VALIDATION = 2;

    /**
     * Confirme un incident
     */
    public function confirmer(Incident $incident, int $userId)
    {
        return DB::transaction(function () use ($incident, $userId) {

            // ✅ Vérifier que l'utilisateur n'a pas déjà confirmé
            $existe = Confirmation::where('incident_id', $incident->id)
                ->where('utilisateur_id', $userId)
                ->exists();

           if ($existe) {
    throw new AlreadyConfirmedException();
}

            // ✅ Ajouter confirmation
            Confirmation::create([
                'incident_id' => $incident->id,
                'utilisateur_id' => $userId
            ]);

            // ✅ Incrémentation atomique
            $incident->increment('nombre_confirmations');

            // ✅ Vérifier si seuil atteint
            if (
                $incident->statut === 'en_attente' &&
                $incident->nombre_confirmations >= self::SEUIL_VALIDATION
            ) {
                $ancienStatut = $incident->statut;

                $incident->update([
                    'statut' => 'valide'
                ]);

                // ✅ Historique
                HistoriqueStatut::create([
                    'incident_id' => $incident->id,
                    'ancien_statut' => $ancienStatut,
                    'nouveau_statut' => 'valide',
                    'modifie_par' => $userId
                ]);

                event(new IncidentValidated($incident));
            }

            return $incident->fresh();
        });
    }
}
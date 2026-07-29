<?php

namespace App\Services;

use App\Exceptions\AlreadyConfirmedException;
use App\Events\IncidentValidated;
use App\Models\Incident;
use App\Models\Confirmation;
use App\Models\HistoriqueStatut;
use App\Services\SystemSettingService;
use Illuminate\Support\Facades\DB;

class ConfirmationService
{
    protected SystemSettingService $settingsService;

    public function __construct(SystemSettingService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * Confirme un incident
     */
    public function confirmer(Incident $incident, int $userId)
    {
        return DB::transaction(function () use ($incident, $userId) {

            // Vérifier que l'utilisateur n'a pas déjà confirmé
            $existe = Confirmation::where('incident_id', $incident->id)
                ->where('utilisateur_id', $userId)
                ->exists();

            if ($existe) {
                throw new AlreadyConfirmedException();
            }

            // Ajouter confirmation
            Confirmation::create([
                'incident_id' => $incident->id,
                'utilisateur_id' => $userId
            ]);

            // Incrémentation atomique
            $incident->increment('nombre_confirmations');

            // Récupération dynamique du seuil (par défaut 2 si non défini)
            $threshold = (int) $this->settingsService->get('incident_validation_threshold', 2);

            // Vérifier si seuil atteint
            if (
                $incident->statut === 'en_attente' &&
                $incident->nombre_confirmations >= $threshold
            ) {
                $ancienStatut = $incident->statut;

                $incident->update([
                    'statut' => 'valide'
                ]);

                // Historique
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
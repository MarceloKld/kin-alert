<?php

namespace App\Services;

use App\Models\Incident;
use App\Models\HistoriqueStatut;
use Illuminate\Support\Facades\DB;

class IncidentService
{
    public function create(array $data, int $userId): Incident
    {
        return DB::transaction(function () use ($data, $userId) {

            $incident = Incident::create(array_merge(
    $data,
    [
        'utilisateur_id' => $userId,
        'statut' => 'en_attente',
        'nombre_confirmations' => 0
    ]
));

            HistoriqueStatut::create([
                'incident_id' => $incident->id,
                'ancien_statut' => null,
                'nouveau_statut' => 'en_attente',
                'modifie_par' => $userId
            ]);

            return $incident;
        });
    }
}
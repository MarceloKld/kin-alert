<?php

namespace App\Services;
use App\Models\Preuve;

use Illuminate\Support\Facades\Storage;
use App\Models\Incident;
use App\Models\HistoriqueStatut;
use Illuminate\Support\Facades\DB;

class IncidentService
{
   public function create(array $data, int $userId): Incident
{
    return DB::transaction(function () use ($data, $userId) {
        
        // 1. Création de l'incident
        $incident = Incident::create([
            'titre' => $data['titre'],
            'description' => $data['description'],
            'type_incident_id' => $data['type_incident_id'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'commune_id' => $data['commune_id'],
            'avenue' => $data['avenue'] ?? null,
            'statut' => 'en_attente',
            'nombre_confirmations' => 0,
            'utilisateur_id' => $userId
        ]);

        // 2. Conservation de l'historique (sans casser l'historique)
        HistoriqueStatut::create([
            'incident_id' => $incident->id,
            'ancien_statut' => null,
            'nouveau_statut' => 'en_attente',
            'modifie_par' => $userId
        ]);

        // 3. Gestion de la photo
        if (request()->hasFile('photo')) {

    foreach (request()->file('photo') as $photo) {

        $path = $photo->store('preuves', 'public');

        Preuve::create([
            'incident_id' => $incident->id,
            'fichier' => $path,
            'type_fichier' => $photo->getClientOriginalExtension()
        ]);
    }
}

        // 4. Retourne l'incident mis à jour avec ses relations
        return $incident->fresh()->load(['preuves']);
    });
}
}
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminIncidentDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'statut' => $this->statut,
            'nombre_confirmations' => $this->nombre_confirmations,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'avenue' => $this->avenue,
            'date_creation' => $this->date_creation,
            'deleted_at' => $this->deleted_at,

            'utilisateur' => $this->utilisateur,

            'commune' => $this->commune,

            'type_incident' => $this->typeIncident,

            'preuves' => $this->preuves,

            'confirmations' => $this->confirmations,

            'commentaires' => $this->commentaires,

            'historique_statuts' => $this->historiques,
        ];
    }
}

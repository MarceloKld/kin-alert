<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminIncidentResource extends JsonResource
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

            'utilisateur' => [
                'id' => $this->utilisateur?->id,
                'nom' => $this->utilisateur?->nom,
                'email' => $this->utilisateur?->email,
            ],

            'commune' => [
                'id' => $this->commune?->id,
                'nom' => $this->commune?->nom,
            ],

            'type_incident' => [
    'id' => $this->typeIncident?->id,
    'titre' => $this->typeIncident?->titre,
],
        ];
    }
}

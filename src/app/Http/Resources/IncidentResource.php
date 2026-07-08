<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * IncidentResource
 *
 * Transforme le modèle Incident en JSON propre
 */
class IncidentResource extends JsonResource
{
    /**
     * Transforme la ressource en tableau JSON
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,

            'localisation' => [
                'latitude' => (float) $this->latitude,
                'longitude' => (float) $this->longitude,
                'avenue' => $this->avenue,
                'commune' => $this->whenLoaded('commune', function () {
                    return [
                        'id' => $this->commune->id,
                        'nom' => $this->commune->nom
                    ];
                }),
            ],

            'statut' => $this->statut,
            'nombre_confirmations' => $this->nombre_confirmations,

            'auteur' => $this->whenLoaded('utilisateur', function () {
                return [
                    'id' => $this->utilisateur->id,
                    'nom' => $this->utilisateur->nom,
                    'role' => $this->utilisateur->role
                ];
            }),

            'type' => $this->whenLoaded('type', function () {
                return [
                    'id' => $this->type->id,
                    'titre' => $this->type->titre
                ];
            }),

            'date_creation' => $this->date_creation,
        ];
    }
}
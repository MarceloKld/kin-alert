<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation dédiée à la création d’un incident
 * 
 * Permet :
 * - Nettoyer les données
 * - Centraliser validation
 * - Alléger le controller
 */
class StoreIncidentRequest extends FormRequest
{
    /**
     * Autorisation (temporairement true)
     * Plus tard → Policy
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:150',
            'description' => 'required|string',
            'type_incident_id' => 'required|exists:types_incidents,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'commune_id' => 'required|exists:communes,id',
            'avenue' => 'nullable|string|max:150',

        // ✅ PHOTO
        'photo' => 'nullable|image|max:2048'
        ];
    }
}
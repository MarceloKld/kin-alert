<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation pour mise à jour incident
 */
class UpdateIncidentRequest extends FormRequest
{
    /**
     * Autorisation sera gérée par Policy
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
            'titre' => 'sometimes|string|max:150',
            'description' => 'sometimes|string',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
            'avenue' => 'nullable|string|max:150'
        ];
    }
}
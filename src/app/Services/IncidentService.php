<?php

namespace App\Services;
use App\Models\Preuve;

use Illuminate\Support\Facades\Storage;
use App\Models\Incident;
use App\Models\HistoriqueStatut;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

        // 3. Gestion de la photo via API HTTP Cloudinary (infaillible)
        if (request()->hasFile('photo')) {
            $cloudName = env('CLOUDINARY_CLOUD_NAME');
            $preset = env('CLOUDINARY_UPLOAD_PRESET');
            $endpoint = "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload";

            foreach (request()->file('photo') as $photo) {
                // Envoi direct du fichier à Cloudinary
                $response = Http::attach(
                    'file',
                    file_get_contents($photo->getRealPath()),
                    $photo->getClientOriginalName()
                )->post($endpoint, [
                    'upload_preset' => $preset,
                ]);

                if ($response->successful()) {
                    $cloudinaryData = $response->json();
                    $path = $cloudinaryData['secure_url']; // URL publique https d'accès à l'image

                    // Enregistrement en base de données
                    Preuve::create([
                        'incident_id' => $incident->id,
                        'fichier' => $path,
                        'type_fichier' => $photo->getClientOriginalExtension()
                    ]);
                } else {
                    Log::error("Erreur Cloudinary : " . $response->body());
                    throw new \Exception("Échec de l'upload vers Cloudinary");
                }
            }
        }

        // 4. Retourne l'incident mis à jour avec ses relations
        return $incident->fresh()->load(['preuves']);
    });
}
}
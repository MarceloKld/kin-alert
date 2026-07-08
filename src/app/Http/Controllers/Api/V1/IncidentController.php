<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncidentRequest;
use App\Services\IncidentService;
use App\Models\Incident;

/**
 * Controller API des incidents
 * 
 * Rôle :
 * - Recevoir requêtes HTTP
 * - Déléguer logique métier au Service
 * - Retourner réponse JSON propre
 */
class IncidentController extends Controller
{
    /**
     * Liste paginée des incidents
     */
    public function index()
    {
        $incidents = Incident::with(['utilisateur', 'commune', 'type'])
            ->latest('date_creation')
            ->paginate(10);

        return response()->json($incidents);
    }

    /**
     * Création d’un nouvel incident
     */
    public function store(StoreIncidentRequest $request, IncidentService $service)
    {
        // Ici on suppose user_id = 1 temporairement
        // Plus tard ce sera auth()->id()
        $incident = $service->create(
            $request->validated(),
            1
        );

        return response()->json([
            'message' => 'Incident créé avec succès',
            'data' => $incident
        ], 201);
    }

    /**
     * Afficher un incident spécifique
     */
    public function show($id)
    {
        $incident = Incident::with(['utilisateur', 'commune', 'type'])
            ->findOrFail($id);

        return response()->json($incident);
    }
}
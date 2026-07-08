<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\UpdateIncidentRequest;
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
    use AuthorizesRequests;
    /**
     * Liste paginée des incidents
     */
   public function index()
{
    $this->authorize('viewAny', Incident::class);

    $incidents = Incident::with(['utilisateur', 'commune', 'type'])
        ->latest('date_creation')
        ->paginate(10);

    return response()->json($incidents);
}

    /**
     * Création d’un nouvel incident
     */
    /**
 * Création d’un nouvel incident
 */
public function store(StoreIncidentRequest $request, IncidentService $service)
{
    $this->authorize('create', Incident::class);

    $incident = $service->create(
        $request->validated(),
        auth()->id()
    );

    return response()->json([
        'message' => 'Incident créé avec succès',
        'data' => $incident
    ], 201);
}

    /**
     * Afficher un incident spécifique
     */
    public function show(Incident $incident)
{
    $this->authorize('view', $incident);

    return response()->json($incident);
}

public function update(UpdateIncidentRequest $request, Incident $incident)
{
    // ✅ Vérification via Policy
    $this->authorize('update', $incident);

    $incident->update($request->validated());

    return response()->json([
        'message' => 'Incident mis à jour',
        'data' => $incident
    ]);
}

public function destroy(Incident $incident)
{
    // ✅ Vérification via Policy
    $this->authorize('delete', $incident);

    $incident->delete();

    return response()->json([
        'message' => 'Incident supprimé'
    ]);
}


}
<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Cache;
use App\Filters\IncidentFilter;
use App\Services\AuditService;
use App\Services\ConfirmationService;
use App\Http\Resources\IncidentResource;
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
   public function index(IncidentFilter $filter)
{
    $this->authorize('viewAny', Incident::class);

    $cacheKey = 'incidents_' . md5(request()->fullUrl());

    return Cache::remember($cacheKey, 60, function () use ($filter) {

        $query = Incident::with(['utilisateur', 'commune', 'type'])
            ->latest('date_creation');

        $incidents = $filter->apply($query)->paginate(10);

        return IncidentResource::collection($incidents);
    });
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
    'data' => new IncidentResource(
        $incident->load(['utilisateur', 'commune', 'type'])
    )
], 201);
}

    /**
     * Afficher un incident spécifique
     */
    public function show(Incident $incident)
{
    $this->authorize('view', $incident);

    $incident->load(['utilisateur', 'commune', 'type']);

    return new IncidentResource($incident);
}

public function update(UpdateIncidentRequest $request, Incident $incident)
{
    // ✅ Vérification via Policy
    $this->authorize('update', $incident);

    $incident->update($request->validated());

    return response()->json([
    'message' => 'Incident mis à jour',
    'data' => new IncidentResource(
        $incident->load(['utilisateur', 'commune', 'type'])
    )
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

public function confirm(Incident $incident, ConfirmationService $service)
{
    $incident = $service->confirmer($incident, auth()->id());

    return response()->json([
        'message' => 'Confirmation enregistrée',
        'data' => new IncidentResource(
            $incident->load(['utilisateur', 'commune', 'type'])
        )
    ]);
}

public function reject(Incident $incident, AuditService $audit)
{
    $this->authorize('reject', $incident);

    $ancienStatut = $incident->statut;

    $incident->update([
        'statut' => 'rejete'
    ]);

    \App\Models\HistoriqueStatut::create([
        'incident_id' => $incident->id,
        'ancien_statut' => $ancienStatut,
        'nouveau_statut' => 'rejete',
        'modifie_par' => auth()->id()
    ]);

    // ✅ Audit
    $audit->log('reject_incident', 'incident', $incident->id);

    return response()->json([
        'message' => 'Incident rejeté avec succès.'
    ]);
}


}
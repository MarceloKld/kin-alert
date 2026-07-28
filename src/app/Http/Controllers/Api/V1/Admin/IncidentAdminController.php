<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\HistoriqueStatut;
use App\Services\AuditService;
use Illuminate\Support\Facades\DB;
use App\Events\IncidentValidated;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\AdminIncidentResource;
use App\Http\Resources\AdminIncidentDetailResource;


class IncidentAdminController extends Controller
{
    use AuthorizesRequests;
  public function forceStatus(Request $request, Incident $incident)
{
    $this->authorize('adminOnly', \App\Models\Utilisateur::class);

    $request->validate([
        'status' => 'required|in:valide,rejete,en_attente'
    ]);

    if ($incident->statut === $request->status) {
        return response()->json([
            'message' => 'Le statut est déjà défini'
        ], 409);
    }

    DB::transaction(function () use ($incident, $request) {

        $ancien = $incident->statut;

        $incident->update([
            'statut' => $request->status
        ]);

        HistoriqueStatut::create([
            'incident_id' => $incident->id,
            'ancien_statut' => $ancien,
            'nouveau_statut' => $request->status,
            'modifie_par' => auth()->id()
        ]);

        if ($request->status === 'valide') {
            event(new IncidentValidated($incident));
        }

        app(AuditService::class)->log(
            'force_status',
            'incident',
            $incident->id
        );
    });

    return response()->json([
        'message' => 'Statut forcé avec succès'
    ], 200);
}

//INCIDENT ADMIN CONTROLLER
public function index(Request $request)
{
    $this->authorize('accessAdmin', \App\Models\Utilisateur::class);

    $perPage = min($request->get('per_page', 20), 100);

    $query = Incident::query()
        ->withTrashed()
        ->with(['utilisateur', 'commune', 'typeIncident']);

    // ✅ FILTRES

    if ($request->filled('status')) {
        $query->where('statut', $request->status);
    }

    if ($request->filled('type_incident_id')) {
        $query->where('type_incident_id', $request->type_incident_id);
    }

    if ($request->filled('commune_id')) {
        $query->where('commune_id', $request->commune_id);
    }

    if ($request->filled('confirmations_min')) {
        $query->where('nombre_confirmations', '>=', $request->confirmations_min);
    }

    if ($request->filled('date_from')) {
        $query->whereDate('date_creation', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('date_creation', '<=', $request->date_to);
    }

    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('titre', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
        });
    }

    $incidents = $query
        ->orderByDesc('date_creation')
        ->paginate($perPage);

    return AdminIncidentResource::collection($incidents);
}

//Vue DÉTAILLÉE PREMIUM admin(ID)
public function show($id)
{
    $this->authorize('accessAdmin', \App\Models\Utilisateur::class);

    $incident = Incident::withTrashed()
        ->with([
            'utilisateur',
            'commune',
            'typeIncident',
            'preuves',
            'confirmations.utilisateur',
            'historiques',
            'commentaires.utilisateur'
        ])
        ->findOrFail($id);

    return new AdminIncidentDetailResource($incident);
}

}

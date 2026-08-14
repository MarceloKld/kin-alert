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
        ->with(['utilisateur', 'commune', 'typeIncident', 'preuves']);

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

//SUPPRIMER
public function destroy($id)
{
    $this->authorize('accessAdmin', \App\Models\Utilisateur::class);

    $incident = Incident::findOrFail($id);

    if ($incident->deleted_at !== null) {
        return response()->json([
            'message' => 'Incident déjà supprimé'
        ], 409);
    }

    DB::transaction(function () use ($incident) {

        $ancien = $incident->statut;

        $incident->delete();

        HistoriqueStatut::create([
            'incident_id' => $incident->id,
            'ancien_statut' => $ancien,
            'nouveau_statut' => 'supprime',
            'modifie_par' => auth()->id()
        ]);

        app(AuditService::class)->log(
            'delete_incident',
            'incident',
            $incident->id
        );
    });

    return response()->json([
        'message' => 'Incident supprimé avec succès'
    ], 200);
}

//RESTAURER
public function restore($id)
{
    $this->authorize('accessAdmin', \App\Models\Utilisateur::class);

    $incident = Incident::withTrashed()->findOrFail($id);

    if ($incident->deleted_at === null) {
        return response()->json([
            'message' => 'Incident non supprimé'
        ], 409);
    }

    DB::transaction(function () use ($incident) {

        $incident->restore();

        HistoriqueStatut::create([
            'incident_id' => $incident->id,
            'ancien_statut' => 'supprime',
            'nouveau_statut' => $incident->statut,
            'modifie_par' => auth()->id()
        ]);

        app(AuditService::class)->log(
            'restore_incident',
            'incident',
            $incident->id
        );
    });

    return response()->json([
        'message' => 'Incident restauré avec succès'
    ], 200);
}

//Logs d'historique d'un incident
public function history($id)
{
    // 1. Sécurité : Vérifier si l'utilisateur est Admin ou Gestionnaire
    $this->authorize('accessAdmin', \App\Models\Utilisateur::class);

    // 2. Vérifier si l'incident existe (renvoie 404 si non trouvé)
    $incident = \App\Models\Incident::findOrFail($id);

    // 3. Récupérer l'historique avec les infos du modificateur
    // On utilise 'modificateur' car c'est le nom dans ton modèle HistoriqueStatut.php
    $history = \App\Models\HistoriqueStatut::where('incident_id', $id)
        ->with('modificateur:id,nom,role') 
        ->latest('date_modification') // Ta colonne personnalisée
        ->get();

    return response()->json([
        'status' => 'success',
        'incident' => [
            'id' => $incident->id,
            'titre' => $incident->titre,
            'statut_actuel' => $incident->statut
        ],
        'history' => $history
    ]);
}

}

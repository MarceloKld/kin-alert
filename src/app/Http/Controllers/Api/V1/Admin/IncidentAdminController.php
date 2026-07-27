<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\HistoriqueStatut;
use App\Services\AuditService;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IncidentAdminController extends Controller
{
    use AuthorizesRequests;
   public function forceStatus(Request $request, Incident $incident)
{
    if (!in_array(auth()->user()->role, ['administrateur', 'gestionnaire', 'moderateur'])) {
            abort(403, 'Accès interdit');
        }

    $request->validate([
        'status' => 'required|in:valide,rejete,en_attente'
    ]);

    $ancien = $incident->statut;

    $incident->update(['statut' => $request->status]);

    HistoriqueStatut::create([
        'incident_id' => $incident->id,
        'ancien_statut' => $ancien,
        'nouveau_statut' => $request->status,
        'modifie_par' => auth()->id()
    ]);

    app(AuditService::class)->log('force_status', 'incident', $incident->id);

    return response()->json(['message' => 'Statut forcé']);
}
}

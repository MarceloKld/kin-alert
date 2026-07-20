<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Commune;
use App\Models\TypeIncident;
use App\Models\Incident;
use App\Models\Utilisateur;

class StatisticsController extends Controller
{
    use AuthorizesRequests;

    /**
     * ✅ Communes avec le plus d'incidents
     */
    public function communesMostIncidents()
    {
        $data = Commune::withCount('incidents')
            ->orderByDesc('incidents_count')
            ->get();

        return response()->json($data);
    }

    /**
     * ✅ Incidents groupés par type
     */
    public function incidentsByType()
    {
        $data = TypeIncident::withCount('incidents')
            ->orderByDesc('incidents_count')
            ->get();

        return response()->json($data);
    }

    /**
     * ✅ Dashboard global
     */
    public function dashboard()
    {
        return response()->json([
            'total_incidents' => Incident::count(),
            'valide' => Incident::where('statut','valide')->count(),
            'en_attente' => Incident::where('statut','en_attente')->count(),
            'rejete' => Incident::where('statut','rejete')->count(),
            'total_utilisateurs' => Utilisateur::count(),
        ]);
    }

    /**
 * ✅ Dashboard personnel de l'utilisateur connecté
 */
public function myDashboard()
{
    $userId = auth()->id();

    return response()->json([
        'total_mes_incidents' => \App\Models\Incident::where('utilisateur_id', $userId)->count(),

        'mes_valides' => \App\Models\Incident::where('utilisateur_id', $userId)
            ->where('statut', 'valide')
            ->count(),

        'mes_en_attente' => \App\Models\Incident::where('utilisateur_id', $userId)
            ->where('statut', 'en_attente')
            ->count(),

        'mes_rejetes' => \App\Models\Incident::where('utilisateur_id', $userId)
            ->where('statut', 'rejete')
            ->count(),

        'total_commentaires_sur_mes_incidents' => \App\Models\Commentaire::whereIn(
            'incident_id',
            \App\Models\Incident::where('utilisateur_id', $userId)->pluck('id')
        )->count(),

        'total_confirmations_sur_mes_incidents' => \App\Models\Confirmation::whereIn(
            'incident_id',
            \App\Models\Incident::where('utilisateur_id', $userId)->pluck('id')
        )->count(),
    ]);
}

}
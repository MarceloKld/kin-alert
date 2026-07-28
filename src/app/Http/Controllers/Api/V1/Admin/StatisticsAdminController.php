<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\AuditService;
use App\Models\Utilisateur;
use App\Models\Incident;
use Illuminate\Support\Facades\DB;
use App\Models\HistoriqueStatut;


class StatisticsAdminController extends Controller
{
    use AuthorizesRequests;
    public function global()
{

    return response()->json([
        'total_users' => Utilisateur::count(),
        'total_incidents' => Incident::count(),
        'incidents_today' => Incident::whereDate('date_creation', today())->count(),
        'incidents_validated' => Incident::where('statut', 'valide')->count(),
        'incidents_rejected' => Incident::where('statut', 'rejete')->count(),
        'users_suspended' => Utilisateur::where('statut', 'suspendu')->count(),
    ]);
}

//STATISTIQUES AVANCÉES PAR PÉRIODE
public function incidentsByPeriod(Request $request)
{
    $this->authorize('accessAdmin', Utilisateur::class);

    $request->validate([
        'group_by' => 'required|in:day,week,month',
        'date_from' => 'nullable|date',
        'date_to' => 'nullable|date',
    ]);

    $groupBy = $request->group_by;

    // Définir format SQL selon group_by
    $format = match ($groupBy) {
        'day' => "%Y-%m-%d",
        'week' => "%x-%v",
        'month' => "%Y-%m",
    };

    $query = Incident::withTrashed();

    if ($request->filled('date_from')) {
        $query->whereDate('date_creation', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('date_creation', '<=', $request->date_to);
    }

    $results = $query->select(
            DB::raw("DATE_FORMAT(date_creation, '$format') as period"),
            DB::raw("COUNT(*) as total")
        )
        ->groupBy('period')
        ->orderBy('period')
        ->get();

    return response()->json([
        'group_by' => $groupBy,
        'data' => $results
    ]);
}

//PERFORMANCE DES GESTIONNAIRES
public function moderatorsPerformance()
{
    $this->authorize('accessAdmin', Utilisateur::class);

    $results = Utilisateur::where('role', 'gestionnaire')
        ->leftJoin('historique_statuts', 'utilisateurs.id', '=', 'historique_statuts.modifie_par')
        ->select(
            'utilisateurs.id',
            'utilisateurs.nom',
            DB::raw("SUM(CASE WHEN historique_statuts.nouveau_statut = 'valide' THEN 1 ELSE 0 END) as incidents_validated"),
            DB::raw("SUM(CASE WHEN historique_statuts.nouveau_statut = 'rejete' THEN 1 ELSE 0 END) as incidents_rejected"),
            DB::raw("COUNT(historique_statuts.id) as total_actions")
        )
        ->groupBy('utilisateurs.id', 'utilisateurs.nom')
        ->orderByDesc('incidents_validated')
        ->get();

    return response()->json($results);
}

}

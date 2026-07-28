<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use App\Models\Incident;
use App\Models\TypeIncident;
use App\Models\Commune;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AdminDashboardController extends Controller
{
    use AuthorizesRequests; // ✅ IMPORTANT
   public function index()
{
    $this->authorize('accessAdmin', \App\Models\Utilisateur::class);

    // =========================
    // STATS GLOBALES
    // =========================

    $stats = [
        'users_total' => Utilisateur::count(),
        'gestionnaires_total' => Utilisateur::where('role', 'gestionnaire')->count(),
        'incidents_total' => Incident::withTrashed()->count(),
        'incidents_pending' => Incident::where('statut', 'en_attente')->count(),
        'incidents_validated' => Incident::where('statut', 'valide')->count(),
        'incidents_rejected' => Incident::where('statut', 'rejete')->count(),
        'incidents_deleted' => Incident::onlyTrashed()->count(),
    ];

    // =========================
    // INCIDENTS PAR MOIS
    // =========================

    $incidentsByMonth = Incident::select(
            DB::raw("DATE_FORMAT(date_creation, '%Y-%m') as month"),
            DB::raw("COUNT(*) as total")
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    // =========================
    // INCIDENTS PAR TYPE
    // =========================

    $incidentsByType = TypeIncident::select('types_incidents.titre')
        ->join('incidents', 'types_incidents.id', '=', 'incidents.type_incident_id')
        ->selectRaw('COUNT(incidents.id) as total')
        ->groupBy('types_incidents.id', 'types_incidents.titre')
        ->get();

    // =========================
    // TOP COMMUNES
    // =========================

    $topCommunes = Commune::select('communes.nom')
        ->join('incidents', 'communes.id', '=', 'incidents.commune_id')
        ->selectRaw('COUNT(incidents.id) as total')
        ->groupBy('communes.id', 'communes.nom')
        ->orderByDesc('total')
        ->limit(5)
        ->get();

    // =========================
    // CROISSANCE UTILISATEURS
    // =========================

    $usersGrowth = Utilisateur::select(
            DB::raw("DATE_FORMAT(date_creation, '%Y-%m') as month"),
            DB::raw("COUNT(*) as total")
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    return response()->json([
        'stats' => $stats,
        'charts' => [
            'incidents_by_month' => $incidentsByMonth,
            'incidents_by_type' => $incidentsByType,
            'top_communes' => $topCommunes,
            'users_growth' => $usersGrowth,
        ]
    ]);
}
}

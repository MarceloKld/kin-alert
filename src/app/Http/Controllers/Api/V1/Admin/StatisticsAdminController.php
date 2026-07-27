<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\AuditService;
use App\Models\Utilisateur;
use App\Models\Incident;
class StatisticsAdminController extends Controller
{
    use AuthorizesRequests;
    public function global()
{
    if (!in_array(auth()->user()->role, ['administrateur', 'gestionnaire'])) {
            abort(403, 'Accès interdit');
        }

    return response()->json([
        'total_users' => Utilisateur::count(),
        'total_incidents' => Incident::count(),
        'incidents_today' => Incident::whereDate('date_creation', today())->count(),
        'incidents_validated' => Incident::where('statut', 'valide')->count(),
        'incidents_rejected' => Incident::where('statut', 'rejete')->count(),
        'users_suspended' => Utilisateur::where('statut', 'suspendu')->count(),
    ]);
}
}

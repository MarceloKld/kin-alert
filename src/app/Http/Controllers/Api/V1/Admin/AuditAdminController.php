<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Utilisateur;

class AuditAdminController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
{
    $query = AuditLog::with('utilisateur:id,nom,role');

    if ($request->user_id) {
        $query->where('utilisateur_id', $request->user_id);
    }

    if ($request->action) {
        $query->where('action', $request->action);
    }

    if ($request->date) {
        $query->whereDate('date_creation', $request->date);
    }

    // Récupère la limite par page passée dans l'URL (ex: ?per_page=15) ou 15 par défaut
    $perPage = min((int) $request->get('per_page', 15), 100);

    // Pagination automatique au lieu de ->get()
    return response()->json($query->latest('date_creation')->paginate($perPage));
}
    // AUDIT LOGS PAR UTILISATEUR
    public function logsByUser($id, Request $request)
    {
        $this->authorize('accessAdmin', Utilisateur::class);

        $perPage = min($request->get('per_page', 20), 100);

        $logs = AuditLog::with('utilisateur:id,nom,role')
            ->where('utilisateur_id', $id)
            ->orderByDesc('date_creation')
            ->paginate($perPage);

        return response()->json($logs);
    }
}
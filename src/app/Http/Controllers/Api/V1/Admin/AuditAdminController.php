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
    $query = AuditLog::query();

    if ($request->user_id) {
        $query->where('utilisateur_id', $request->user_id);
    }

    if ($request->action) {
        $query->where('action', $request->action);
    }

    if ($request->date) {
        $query->whereDate('date_creation', $request->date);
    }

    // Utilisation explicite de 'date_creation' au lieu du 'created_at' par défaut
        return response()->json($query->latest('date_creation')->get());
}

//AUDIT LOGS PAR UTILISATEUR
public function logsByUser($id, Request $request)
{
    $this->authorize('accessAdmin', Utilisateur::class);

    $perPage = min($request->get('per_page', 20), 100);

    $logs = AuditLog::where('utilisateur_id', $id)
        ->orderByDesc('date_creation')
        ->paginate($perPage);

    return response()->json($logs);
}
}

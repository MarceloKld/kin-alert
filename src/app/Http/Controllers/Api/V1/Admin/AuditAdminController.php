<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AuditAdminController extends Controller
{
    use AuthorizesRequests;
   public function index(Request $request)
{
    $this->authorize('accessAdmin', AdminPolicy::class);

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

    return $query->latest()->paginate(20);
}
}

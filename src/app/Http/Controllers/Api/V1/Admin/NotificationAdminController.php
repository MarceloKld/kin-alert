<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\AuditService;
use App\Models\Utilisateur;
use App\Models\Notification;

class NotificationAdminController extends Controller
{
    use AuthorizesRequests;
    public function broadcast(Request $request)
{
    if (auth()->user()->role !== 'administrateur') {
            abort(403, 'Accès interdit');
        }

    $request->validate([
        'message' => 'required|string'
    ]);

    Utilisateur::where('statut', 'actif')->chunk(100, function ($users) use ($request) {
        foreach ($users as $user) {
            Notification::create([
                'utilisateur_id' => $user->id,
                'message' => $request->message,
                'lu' => false
            ]);
        }
    });

    app(AuditService::class)->log('broadcast_notification', 'system');

    return response()->json(['message' => 'Notification envoyée']);
}
}

<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\AuditService;
use App\Models\Utilisateur;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class NotificationAdminController extends Controller
{
    use AuthorizesRequests;
    public function broadcast(Request $request)
{
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

//NOTIFICATION CIBLÉE SÉCURISÉE
public function sendToUsers(Request $request)
{
    $this->authorize('accessAdmin', Utilisateur::class);

    $request->validate([
        'user_ids' => 'required|array|min:1',
        'user_ids.*' => 'exists:utilisateurs,id',
        'message' => 'required|string|max:1000',
    ]);

    DB::transaction(function () use ($request) {

        foreach ($request->user_ids as $userId) {
            Notification::create([
                'utilisateur_id' => $userId,
                'message' => $request->message,
                'lu' => false,
                'date_creation' => now(),
            ]);
        }

        app(AuditService::class)->log(
            'send_notification',
            'notification',
            null
        );
    });

    return response()->json([
        'message' => 'Notifications envoyées avec succès'
    ], 201);
}

}

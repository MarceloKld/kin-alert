<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Http\Resources\NotificationResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NotificationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Liste des notifications de l'utilisateur connecté
     */
    public function index()
    {
        $notifications = Notification::where('utilisateur_id', auth()->id())
            ->latest('date_creation')
            ->paginate(10);

        return NotificationResource::collection($notifications);
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead(Notification $notification)
    {
        // ✅ Sécurité : vérifier que la notification appartient à l'utilisateur
        if ($notification->utilisateur_id !== auth()->id()) {
            return response()->json([
                'message' => 'Accès interdit'
            ], 403);
        }

        $notification->update([
            'lu' => true
        ]);

        return response()->json([
            'message' => 'Notification marquée comme lue'
        ]);
    }
}
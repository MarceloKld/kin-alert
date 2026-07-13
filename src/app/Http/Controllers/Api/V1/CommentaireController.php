<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Commentaire;
use App\Models\Incident;
use App\Services\AuditService;
use Illuminate\Http\Request;

class CommentaireController extends Controller
{
    use AuthorizesRequests;

    /**
     * ✅ Lister commentaires d'un incident
     */
    public function index(Incident $incident)
    {
        $comments = Commentaire::where('incident_id', $incident->id)
            ->with('utilisateur')
            ->latest('date_creation')
            ->get();

        return response()->json($comments);
    }

    /**
     * ✅ Créer commentaire
     */
    public function store(Request $request, Incident $incident, AuditService $audit)
    {
        $request->validate([
            'contenu' => 'required|string'
        ]);

        $comment = Commentaire::create([
            'contenu' => $request->contenu,
            'utilisateur_id' => auth()->id(),
            'incident_id' => $incident->id,
        ]);

        // ✅ Audit
        $audit->log('create_comment', 'incident', $incident->id);

        return response()->json($comment, 201);
    }
}
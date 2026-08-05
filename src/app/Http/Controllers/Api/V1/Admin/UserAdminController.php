<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\AuditService;
use App\Models\AuditLog;

class UserAdminController extends Controller
{
    use AuthorizesRequests;
    public function index()
{
    return Utilisateur::get(); // pagination supprimée
}

public function suspend(Utilisateur $user)
{
    $user->update(['statut' => 'suspendu']);

    app(AuditService::class)->log('suspend_user', 'user', $user->id);

    return response()->json(['message' => 'Utilisateur suspendu']);
}

public function activate(Utilisateur $user)
{
    $user->update(['statut' => 'actif']);

    app(AuditService::class)->log('activate_user', 'user', $user->id);

    return response()->json(['message' => 'Utilisateur activé']);
}

public function destroy(Utilisateur $user)
{
    $user->delete();

    app(AuditService::class)->log('delete_user', 'user', $user->id);

    return response()->json(['message' => 'Utilisateur supprimé']);
}

//listManagers
public function listManagers()
{
    return Utilisateur::where('role', 'gestionnaire')->get();
}

//createManager
public function createManager(Request $request)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'email' => 'required|email|unique:utilisateurs,email',
        'password' => 'required|min:6',
        'telephone' => 'nullable|string|max:20'
    ]);

    $manager = Utilisateur::create([
        'nom' => $request->nom,
        'email' => $request->email,
        'mot_de_passe' => bcrypt($request->password),
        'telephone' => $request->telephone,
        'role' => 'gestionnaire',
        'statut' => 'actif'
    ]);

    app(AuditService::class)->log('create_manager', 'user', $manager->id);

    return response()->json([
        'message' => 'Gestionnaire créé avec succès',
        'manager' => $manager
    ], 201);
}

//deleteManager
public function deleteManager(Utilisateur $user)
{
    if ($user->role !== 'gestionnaire') {
        return response()->json([
            'message' => 'Cet utilisateur n\'est pas un gestionnaire'
        ], 422);
    }

    $user->delete();

    app(AuditService::class)->log('delete_manager', 'user', $user->id);

    return response()->json([
        'message' => 'Gestionnaire supprimé avec succès'
    ]);
}

//Création des nouveaux Endpoints Admin
public function details($id)
    {
        // 1. Sécurité : On vérifie si c'est un admin (via ta Policy)
        $this->authorize('adminOnly', Utilisateur::class);

        // 2. Récupération avec statistiques
        $user = Utilisateur::withCount(['incidents', 'confirmations', 'commentaires'])
            ->findOrFail($id);

        // 3. Récupération des logs récents
        $auditLogs = AuditLog::where('utilisateur_id', $id)
    ->latest('date_creation') // <--- On ajoute 'date_creation' ici
    ->take(10)
    ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'stats' => [
                    'incidents' => $user->incidents_count,
                    'confirmations' => $user->confirmations_count,
                    'commentaires' => $user->commentaires_count,
                ],
                'recent_activity' => $auditLogs
            ]
        ]);
    }

//Historique modération gestionnaire
public function managerActivity($id)
{
    // 1. Sécurité : Seul l'admin peut voir l'activité d'un gestionnaire
    $this->authorize('adminOnly', Utilisateur::class);

    // 2. On récupère le gestionnaire (on vérifie qu'il existe et qu'il est bien gestionnaire ou admin)
    $manager = Utilisateur::whereIn('role', ['gestionnaire', 'administrateur'])->findOrFail($id);

    // 3. On récupère ses actions de modération
    $actions = \App\Models\HistoriqueStatut::where('modifie_par', $id)
        ->with('incident:id,titre') // Charge l'incident lié
        ->latest('date_modification') // Utilise ta colonne personnalisée
        ->paginate(15);

    // 4. On calcule ses stats
    $stats = [
        'total_actions' => \App\Models\HistoriqueStatut::where('modifie_par', $id)->count(),
        'validations' => \App\Models\HistoriqueStatut::where('modifie_par', $id)->where('nouveau_statut', 'valide')->count(),
        'rejets' => \App\Models\HistoriqueStatut::where('modifie_par', $id)->where('nouveau_statut', 'rejete')->count(),
    ];

    return response()->json([
        'status' => 'success',
        'manager' => [
            'id' => $manager->id,
            'nom' => $manager->nom,
            'role' => $manager->role
        ],
        'statistics' => $stats,
        'activities' => $actions
    ]);
}


}

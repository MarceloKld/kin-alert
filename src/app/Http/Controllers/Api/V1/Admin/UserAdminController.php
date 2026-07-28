<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\AuditService;

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

}

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
}

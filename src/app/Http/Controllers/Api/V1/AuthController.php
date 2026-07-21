<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

/**
 * AuthController
 * 
 * Gère :
 * - Login
 * - Logout
 * - Récupération utilisateur connecté
 */
class AuthController extends Controller
{
    /**
     * Login utilisateur
     */
    public function login(Request $request)
    {
        // Validation simple (on améliorera ensuite)
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = Utilisateur::where('email', $request->email)->first();

        // Vérification mot de passe hashé
        if (!$user || !Hash::check($request->password, $user->mot_de_passe)) {
            return response()->json([
                'message' => 'Identifiants invalides'
            ], 401);
        }

        // Génération token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Logout utilisateur (révoque tous les tokens)
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }

    /**
     * Retourne utilisateur authentifié
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
//Méthode register(CREATION D'UN UTILISATEUR)
    public function register(Request $request)
{
   $request->validate([
        'nom' => 'required|string|max:150',
        'email' => 'required|email|unique:utilisateurs,email',
        'telephone' => 'required|string|max:20',
        'password' => 'required|min:6|confirmed'
    ]);

    $user = Utilisateur::create([
        'nom' => $request->nom,
        'email' => $request->email,
        'telephone' => $request->telephone,
        'mot_de_passe' => Hash::make($request->password),
        'role' => 'utilisateur',
        'statut' => 'actif'
    ]);

    return response()->json([
        'message' => 'Utilisateur créé avec succès',
        'user' => [
            'id' => $user->id,
            'nom' => $user->nom,
            'email' => $user->email,
            'telephone' => $user->telephone,
            'role' => $user->role
        ]
    ], 201);
}

}
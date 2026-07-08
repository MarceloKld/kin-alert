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
}
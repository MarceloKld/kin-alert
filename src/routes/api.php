<?php

use App\Http\Controllers\Api\V1\PreuveController;
use App\Http\Controllers\Api\V1\VilleController;
use App\Http\Controllers\Api\V1\CommuneController;
use App\Http\Controllers\Api\V1\TypeIncidentController;
use App\Http\Controllers\Api\V1\CommentaireController;
use App\Http\Controllers\Api\V1\StatisticsController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\IncidentController;
use Illuminate\Support\Facades\Password;


use App\Http\Controllers\Api\V1\Admin\UserAdminController;
use App\Http\Controllers\Api\V1\Admin\IncidentAdminController;
use App\Http\Controllers\Api\V1\Admin\AuditAdminController;
use App\Http\Controllers\Api\V1\Admin\StatisticsAdminController;
use App\Http\Controllers\Api\V1\Admin\NotificationAdminController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Toutes les routes sont préfixées automatiquement par /api
| Nous ajoutons /v1 pour versionner proprement l’API
*/
Route::prefix('v1')->group(function () {

// ✅ ROUTES PUBLIQUES

//AUTHOTENTIFICATION
   Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

     //Itinéraire mot de passe oublié
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])
    ->withoutMiddleware(['auth:sanctum']);
    
    //RÉINITIALISER LE MOT DE PASSE
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    Route::post('test-public', function () {
    return response()->json(['ok' => true]);
});
    // ✅ ROUTES PROTÉGÉES
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('incidents/{incident}/confirm', 
    [IncidentController::class, 'confirm']);
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::patch('notifications/{notification}/read', 
    [NotificationController::class, 'markAsRead']);
    Route::patch('incidents/{incident}/reject', 
    [IncidentController::class, 'reject']);


    // ✅ ADMINISTRATION (/v1/admin/...)
       Route::prefix('admin')
    ->middleware(['auth:sanctum', 'can:accessAdmin,App\Models\Utilisateur'])
    ->group(function () {
            // USERS
            Route::get('users', [UserAdminController::class, 'index']);
            Route::get('users/{user}', [UserAdminController::class, 'show']);
            Route::patch('users/{user}/suspend', [UserAdminController::class, 'suspend']);
            Route::patch('users/{user}/activate', [UserAdminController::class, 'activate']);
            Route::delete('users/{user}', [UserAdminController::class, 'destroy']);

            // MANAGERS
            //✅ Maintenant seul administrateur peut créer / supprimer gestionnaire.
           Route::middleware('can:adminOnly,App\Models\Utilisateur')->group(function () {

            Route::post('managers', [UserAdminController::class, 'createManager']);
             Route::get('managers', [UserAdminController::class, 'listManagers']);
             Route::delete('managers/{user}', [UserAdminController::class, 'deleteManager']);
        });

            // INCIDENTS
            Route::patch('incidents/{incident}/force-status', [IncidentAdminController::class, 'forceStatus']);

            // AUDIT
            Route::get('audit-logs', [AuditAdminController::class, 'index']);

            // BROADCAST NOTIFICATION
            Route::post('notifications/broadcast', [NotificationAdminController::class, 'broadcast']);

            // GLOBAL STATS
            Route::get('statistics/global', [StatisticsAdminController::class, 'global']);
        });


    //✅ 1️⃣ VILLES
        Route::apiResource('villes', VilleController::class)
        ->only(['index', 'store']);

    //✅ 2️⃣ COMMUNES
        Route::apiResource('communes', CommuneController::class)
        ->only(['indx', 'store']);

    //✅ 3️⃣ TYPES INCIDENTS
         Route::apiResource('types-incidents', TypeIncidentController::class)
         ->only(['index', 'store']);

    //✅ 4️⃣ COMMENTAIRES
        Route::get('incidents/{incident}/comments',
    [CommentaireController::class, 'index']);

        Route::post('incidents/{incident}/comments',
    [CommentaireController::class, 'store']);

    //✅ 5️⃣ PREUVES (Upload)
        Route::get('incidents/{incident}/preuves',
    [PreuveController::class, 'index']);
        Route::post('incidents/{incident}/preuves',
    [PreuveController::class, 'store']);
    
    //✅ 1️⃣ Communes avec plus d’incidents
        Route::get('statistics/communes-most-incidents',
    [StatisticsController::class, 'communesMostIncidents']);
    
        Route::apiResource('incidents', IncidentController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy']);

     //STATISTICS
        Route::prefix('statistics')->group(function () {

        Route::get('communes-most-incidents',
    [StatisticsController::class, 'communesMostIncidents']);

        Route::get('incidents-by-type',
    [StatisticsController::class, 'incidentsByType']);

         Route::get('dashboard',
    [StatisticsController::class, 'dashboard']);
    Route::get('my-dashboard',
    [StatisticsController::class, 'myDashboard']);

    //VOIR LES COMMUNES
    Route::get('communes', [CommuneController::class, 'index']);
    
    });

      //les incidents créés par l’utilisateur connecté.
    Route::get('my-incidents', [IncidentController::class, 'myIncidents']);
   












    
    });

});
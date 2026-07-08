<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\IncidentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Toutes les routes sont préfixées automatiquement par /api
| Nous ajoutons /v1 pour versionner proprement l’API
*/

Route::prefix('v1')->group(function () {

    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('incidents/{incident}/confirm', 
    [IncidentController::class, 'confirm']);

       Route::apiResource('incidents', IncidentController::class)
    ->only(['index', 'store', 'show', 'update', 'destroy']);
    });

});
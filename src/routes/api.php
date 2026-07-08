<?php

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

    Route::apiResource('incidents', IncidentController::class)
        ->only(['index', 'store', 'show']);

});
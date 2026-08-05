<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SystemSettingService;
use App\Models\SystemSetting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SystemSettingController extends Controller
{ 
    use AuthorizesRequests;
    protected SystemSettingService $settingsService;

    public function __construct(SystemSettingService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * GET /api/v1/admin/settings
     * Voir tous les réglages
     */
    public function index()
    {
        $this->authorize('adminOnly', \App\Models\Utilisateur::class);
        
        return response()->json([
            'status' => 'success',
            'settings' => SystemSetting::all()
        ]);
    }

    /**
     * PUT /api/v1/admin/settings
     * Modifier un réglage spécifique
     */
    public function update(Request $request)
    {
        $this->authorize('adminOnly', \App\Models\Utilisateur::class);

        $validated = $request->validate([
            'key' => 'required|string|exists:system_settings,key',
            'value' => 'required'
        ]);

        // On utilise le service pour mettre à jour et purger le cache
        $setting = $this->settingsService->set($validated['key'], $validated['value']);

        return response()->json([
            'status' => 'success',
            'message' => "Réglage '{$validated['key']}' mis à jour avec succès",
            'data' => $setting
        ]);
    }
}
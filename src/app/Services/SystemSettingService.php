<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

class SystemSettingService
{
    /**
     * Récupérer une configuration système
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        try {
        return Cache::remember("system_setting_{$key}", 60, function () use ($key, $default) {
            $setting = SystemSetting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    } catch (\Throwable $e) {
        return $default;
    }
    
    }

    /**
     * Mettre à jour ou créer une configuration
     *
     * @param string $key
     * @param mixed $value
     * @return SystemSetting
     */
    public function set(string $key, $value): SystemSetting
    {
        $setting = SystemSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Nettoyer le cache
        Cache::forget("system_setting_{$key}");

        return $setting;
    }
}
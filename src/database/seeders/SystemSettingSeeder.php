<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'incident_validation_threshold',
                'value' => '2', // Seuil par défaut
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tu pourras ajouter d'autres réglages ici plus tard
            // [
            //    'key' => 'max_photos_per_incident',
            //    'value' => '5',
            // ]
        ];

        foreach ($settings as $setting) {
            // updateOrCreate évite les doublons si tu relances le seeder
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
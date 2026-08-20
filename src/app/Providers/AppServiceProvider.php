<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       // On force la configuration manuellement avant que le package Cloudinary ne soit initialisé
    config([
        'cloudinary.cloud' => [
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
            'api_key'    => env('CLOUDINARY_API_KEY'),
            'api_secret' => env('CLOUDINARY_API_SECRET'),
        ],
        'cloudinary.upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
    ]);
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
{
   // Force la configuration même si le cache est corrompu
    if (config('cloudinary') === null || !isset(config('cloudinary')['cloud'])) {
        config([
            'cloudinary.cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'cloudinary.upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
        ]);
    }
}
}

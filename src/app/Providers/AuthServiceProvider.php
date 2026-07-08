<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Incident;
use App\Policies\IncidentPolicy;

/**
 * AuthServiceProvider
 *
 * Ce provider enregistre les Policies
 * associées aux modèles Eloquent.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Mapping Model → Policy
     */
    protected $policies = [
        Incident::class => IncidentPolicy::class,
    ];

    /**
     * Boot method
     */
    public function boot(): void
    {
        // Laravel 11 auto-enregistre les policies
        // Aucun code supplémentaire nécessaire ici.
    }
}
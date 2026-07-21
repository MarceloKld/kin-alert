<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Incident;
use App\Policies\IncidentPolicy;
use Illuminate\Auth\Notifications\ResetPassword;

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
    {$this->registerPolicies();

    ResetPassword::createUrlUsing(function ($user, string $token) {
        return 'http://http://localhost:5173/reset-password?token=' 
            . $token . '&email=' . urlencode($user->email);
    });
    }
}
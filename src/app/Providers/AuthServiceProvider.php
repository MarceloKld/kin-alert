<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Incident;
use App\Policies\IncidentPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Policies\AdminPolicy;

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
        Utilisateur::class => AdminPolicy::class, // <-- Mapping officiel
       \App\Models\Utilisateur::class => \App\Policies\AdminPolicy::class,
    // si tu utilises AdminPolicy pour une classe spécifique,
    ];

    /**
     * Boot method
     */
    public function boot(): void
    {$this->registerPolicies();

    ResetPassword::createUrlUsing(function ($user, string $token) {
        return 'http://localhost:5173/reset-password?token=' 
            . $token . '&email=' . urlencode($user->email);
    });
    }
}
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\IncidentValidated;
use App\Listeners\SendIncidentValidatedNotification;


class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    protected $listen = [
        IncidentValidated::class => [
            SendIncidentValidatedNotification::class,
        ],
    ];
}

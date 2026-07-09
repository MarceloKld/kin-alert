<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\IncidentValidated;
use App\Models\Notification;
use Illuminate\Queue\InteractsWithQueue;

class SendIncidentValidatedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(IncidentValidated $event): void
    {
        $incident = $event->incident;

        Notification::create([
            'utilisateur_id' => $incident->utilisateur_id,
            'message' => "Votre incident '{$incident->titre}' a été validé.",
            'lu' => false
        ]);
    }
}

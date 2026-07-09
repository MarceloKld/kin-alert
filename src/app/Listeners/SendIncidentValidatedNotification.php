<?php

namespace App\Listeners;

use App\Events\IncidentValidated;
use App\Models\Notification;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendIncidentValidatedNotification
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

<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\AuditLog;

class AuditService
{
    public function log(string $action, string $cible, ?int $cibleId = null): void
    {
        AuditLog::create([
            'utilisateur_id' => auth()->id(),
            'action' => $action,
            'cible' => $cible,
            'cible_id' => $cibleId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        Log::channel('audit')->info('Audit Event', [
        'user_id' => auth()->id(),
        'action' => $action,
        'cible' => $cible,
        'cible_id' => $cibleId,
        'ip' => request()->ip()
    ]);
    
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    public $timestamps = false;

    protected $fillable = [
        'utilisateur_id',
        'action',
        'cible',
        'cible_id',
        'ip_address',
        'user_agent'
    ];
}
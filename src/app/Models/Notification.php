<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    public $timestamps = false;

    /**
     * ✅ Autorise ces champs en insertion
     */
    protected $fillable = [
        'utilisateur_id',
        'message',
        'lu'
    ];
}
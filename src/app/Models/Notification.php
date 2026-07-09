<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
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
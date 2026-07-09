<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Confirmation extends Model
{
    use HasFactory;
    protected $table = 'confirmations';

    public $timestamps = false;

    protected $fillable = [
        'utilisateur_id',
        'incident_id'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function incident()
    {
        return $this->belongsTo(Incident::class, 'incident_id');
    }
}
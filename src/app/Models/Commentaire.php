<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commentaire extends Model
{
    use HasFactory;

    protected $table = 'commentaires';

    public $timestamps = false;

    protected $fillable = [
        'contenu',
        'utilisateur_id',
        'incident_id'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }
}
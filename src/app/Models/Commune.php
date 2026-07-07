<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    protected $table = 'communes';
    public $timestamps = false;

    protected $fillable = ['nom', 'ville_id'];

    public function ville()
    {
        return $this->belongsTo(Ville::class, 'ville_id');
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'commune_id');
    }
}
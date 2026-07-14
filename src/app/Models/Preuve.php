<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Preuve extends Model
{
    use HasFactory;

    protected $table = 'preuves';

    public $timestamps = false; // ✅ IMPORTANT

    protected $fillable = [
        'incident_id',
        'fichier',
        'type_fichier'
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueStatut extends Model
{
    use HasFactory;
    protected $table = 'historique_statuts';
    public $timestamps = false;

    protected $fillable = [
        'incident_id',
        'ancien_statut',
        'nouveau_statut',
        'modifie_par'
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class, 'incident_id');
    }

    public function modificateur()
    {
        return $this->belongsTo(Utilisateur::class, 'modifie_par');
    }
}
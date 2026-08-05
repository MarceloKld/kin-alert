<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueStatut extends Model
{
    use HasFactory;

    protected $table = 'historique_statuts';
    
    // Désactive les timestamps automatiques par défaut de Laravel (created_at / updated_at)
    public $timestamps = false;

    protected $fillable = [
        'incident_id',
        'ancien_statut',
        'nouveau_statut',
        'modifie_par',
        'date_modification', // Ajouté pour permettre le Mass Assignment avec now()
    ];

    public function incident()
    {
        // Correction du $ en trop devant belongsTo
        return $this->belongsTo(Incident::class, 'incident_id');
    }

    public function modificateur()
    {
        return $this->belongsTo(Utilisateur::class, 'modifie_par');
    }
}
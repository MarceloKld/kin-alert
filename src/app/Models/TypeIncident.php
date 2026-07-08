<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeIncident extends Model
{
    /**
     * Nom réel de la table en base
     * IMPORTANT : ta table s'appelle "types_incidents"
     */
    protected $table = 'types_incidents';

    /**
     * Ta base n'utilise pas created_at / updated_at
     */
    public $timestamps = false;

    /**
     * Champs autorisés en insertion (Mass Assignment sécurisé)
     */
    protected $fillable = [
        'titre',
        'description'
    ];

    /**
     * Relation :
     * Un type d'incident possède plusieurs incidents
     */
    public function incidents()
    {
        return $this->hasMany(Incident::class, 'type_incident_id');
    }
}
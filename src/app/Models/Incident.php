<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Commentaire;

class Incident extends Model
{
    use HasFactory, SoftDeletes;
    use HasFactory, SoftDeletes;
    use HasFactory;
    protected $table = 'incidents';

    public $timestamps = false;

    protected $fillable = [
        'titre',
        'description',
        'type_incident_id',
        'latitude',
        'longitude',
        'commune_id',
        'avenue',
        'statut',
        'nombre_confirmations',
        'utilisateur_id'
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class, 'commune_id');
    }

    public function type()
    {
        return $this->belongsTo(TypeIncident::class, 'type_incident_id');
    }

    public function confirmations()
    {
        return $this->hasMany(Confirmation::class, 'incident_id');
    }

    public function preuves()
    {
        
        return $this->hasMany(Preuve::class, 'incident_id');
    }

    public function historiques()
    {
        return $this->hasMany(HistoriqueStatut::class, 'incident_id');
    }

    public function commentaires()
{
    return $this->hasMany(Commentaire::class, 'incident_id');
}
    
}
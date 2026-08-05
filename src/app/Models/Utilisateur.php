<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Incident;

class Utilisateur extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;
    use HasApiTokens, Notifiable;
    use Notifiable;

    protected $table = 'utilisateurs';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'telephone',
        'role',
        'statut'
    ];

    protected $hidden = [
        'mot_de_passe'
    ];

    // ✅ Laravel attend "password"
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    // Relations
    public function incidents()
    {
        return $this->hasMany(Incident::class, 'utilisateur_id');
    }

    public function confirmations()
    {
        return $this->hasMany(Confirmation::class, 'utilisateur_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'utilisateur_id');
    }

    public function isAdmin(): bool
{
    return $user->role === 'administrateur';
}

public function isGestionnaire(): bool
{
    return $this->role === 'gestionnaire';
}

public function commentaires()
{
    // C'est cette fonction qui manquait !
    return $this->hasMany(Commentaire::class, 'utilisateur_id');
}

}

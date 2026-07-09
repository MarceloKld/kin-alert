<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ville extends Model
{
    use HasFactory;

    protected $table = 'villes';

    public $timestamps = false;

    protected $fillable = [
        'nom'
    ];
}
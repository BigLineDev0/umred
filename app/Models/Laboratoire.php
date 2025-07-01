<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Laboratoire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'localisation',
        'photo',
        'statut',
    ];

    public function equipements()
    {
        return $this->belongsToMany(Equipement::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}

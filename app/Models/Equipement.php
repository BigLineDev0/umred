<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipement extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'statut',
    ];

    public function laboratoires()
    {
        return $this->belongsToMany(Laboratoire::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'equipement_reservation');
    }
}

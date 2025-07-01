<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'user_id',
        'objectif',
        'laboratoire_id',
        'statut',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }

    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class);
    }

    public function equipements()
    {
        return $this->belongsToMany(Equipement::class, 'equipement_reservation');
    }

    public function horaires()
    {
        return $this->hasMany(HoraireReservation::class);
    }
}

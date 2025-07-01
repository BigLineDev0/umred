<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoraireReservation extends Model
{

    protected $table = 'horaire_reservation';

    protected $fillable = [
        'reservation_id',
        'heure_debut',
        'heure_fin',
    ];

    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}

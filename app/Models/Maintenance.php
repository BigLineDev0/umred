<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'equipement_id',
        'description',
        'date_prevue',
        'statut',
    ];

    protected $casts = [
        'date_prevue' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }
}

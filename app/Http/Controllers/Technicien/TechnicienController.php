<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Models\Equipement;
use App\Models\Maintenance;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechnicienController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_maintenances' => Maintenance::where('user_id', $user->id)->count(),
            'maintenances_en_cours' => Maintenance::where('user_id', $user->id)->where('statut', 'en_cours')->count(),
            'maintenances_terminées' => Maintenance::where('user_id', $user->id)->where('statut', 'terminée')->count(),
            'equipements_disponibles' => Equipement::where('statut', 'disponible')->count(),
        ];

        $recent_maintenances = Maintenance::with('equipement')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('technicien.dashboard', compact(
            'stats',
            'recent_maintenances',
        ));
    }
}

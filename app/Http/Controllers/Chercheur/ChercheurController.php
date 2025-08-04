<?php

namespace App\Http\Controllers\Chercheur;

use App\Http\Controllers\Controller;
use App\Models\Equipement;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChercheurController extends Controller
{
    //  public function __construct()
    // {
    //     $this->middleware(['auth', 'role:chercheur']);
    // }

    public function index()
    {
        $user = auth()->user();

        $stats = [
            'en_attente' => $user->reservations()->where('statut', 'en_attente')->count(),
            'confirmees' => $user->reservations()->where('statut', 'confirmée')->count(),
            'annulees' => $user->reservations()->where('statut', 'annulée')->count(),
            'equipements_utilises' => Equipement::where('statut', 'disponible')->count(),
            'prochaine_reservation' => $user->reservations()
                ->where('statut', 'confirmée')
                ->where('date', '>=', now()->toDateString())
                ->orderBy('date')
                ->first(),
        ];

        // Récupération des 5 dernières réservations de l'utilisateur
       $recent_reservations = Reservation::with('laboratoire')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Récupération des réservations du jour
        $today_reservations = Reservation::with('laboratoire')
            ->whereDate('created_at', now()->toDateString())
            ->latest()
            ->take(5)
            ->get();

        // $available_equipments = Equipement::where('statut', 'disponible')
        //     ->with('laboratoire')
        //     ->take(6)
        //     ->get();

        // $notifications = $user->notifications()
        //     ->where('lu', false)
        //     ->latest()
        //     ->take(5)
        //     ->get();

        return view('chercheur.dashboard', compact('stats', 'recent_reservations', 'today_reservations'));
    }

    public function equipementsDisponibles()
    {
        // On récupère uniquement les équipements marqués comme disponibles
        $equipements = Equipement::where('statut', 'disponible')
            ->with('laboratoires')
            ->latest()
            ->get();

        return view('chercheur.equipements.index', compact('equipements'));
    }
}

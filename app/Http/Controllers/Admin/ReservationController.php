<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Laboratoire;
use App\Models\Equipement;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user', 'laboratoire', 'equipements', 'horaires'])->latest()->get();

        $users = User::all();
        $laboratoires = Laboratoire::with('equipements')->get();
        $equipements = Equipement::all();

        return view('admin.reservations.index', compact('reservations', 'laboratoires', 'users', 'equipements'));
        // return view('admin.reservations.index', compact('reservations'));
    }

    public function create()
    {
            $laboratoires = Laboratoire::all();
            $users = User::all();
            $equipements = Equipement::select('id', 'nom', 'laboratoire_id')->get();
            return view('admin.reservations.index', compact('laboratoires', 'users', 'equipements'));

    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'laboratoire_id' => 'required|exists:laboratoires,id',
            'date' => 'required|date',
            'objectif' => 'required|string|max:255',
            'equipements' => 'required|array',
            'equipements.*' => 'exists:equipements,id',
            'horaires' => 'required|array',
            'horaires.*.heure_debut' => 'required|date_format:H:i',
            'horaires.*.heure_fin' => 'required|date_format:H:i|after:horaires.*.heure_debut',
        ]);

        $reservation = Reservation::create([
            'user_id' => $data['user_id'],
            'laboratoire_id' => $data['laboratoire_id'],
            'date' => $data['date'],
            'objectif' => $data['objectif'],
            'statut' => 'en_attente',
        ]);

        $reservation->equipements()->attach($data['equipements']);

        foreach ($data['horaires'] as $horaire) {
            $reservation->horaires()->create($horaire);
        }

        return redirect()->route('admin.reservations.index')->with('success', 'Réservation créée avec succès.');
    }

    public function show(Reservation $reservation)
    {
        $reservation->load(['user', 'laboratoire', 'equipements', 'horaires']);
        return view('admin.reservations.index', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $users = User::all();
        $laboratoires = Laboratoire::with('equipements')->get();
        $reservation->load(['equipements', 'horaires']);
        return view('admin.reservations.edit', compact('reservation', 'users', 'laboratoires'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        // même validation que dans store() + update des relations
        // à implémenter selon tes besoins
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('admin.reservations.index')->with('success', 'Réservation supprimée.');
    }

    public function updateStatut(Request $request, Reservation $reservation)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,confirmée,annulée,terminée',
        ]);
        $reservation->update(['statut' => $request->statut]);
        return redirect()->back()->with('success', 'Statut mis à jour.');
    }
}

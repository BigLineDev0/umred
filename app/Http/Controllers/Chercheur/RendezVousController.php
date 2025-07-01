<?php

namespace App\Http\Controllers\Chercheur;

use App\Http\Controllers\Controller;
use App\Mail\ReservationConfirmation;
use App\Models\Equipement;
use App\Models\HoraireReservation;
use App\Models\Laboratoire;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RendezVousController extends Controller
{

    /**
     * Afficher la page de réservation pour un laboratoire
     */
    public function create($laboratoire_id)
    {
        $laboratoire = Laboratoire::with('equipements')->findOrFail($laboratoire_id);

        return view('pages.reservation', compact('laboratoire'));
    }

    /**
     * Récupérer les horaires disponibles pour une date donnée
     */
    public function getHorairesDisponibles(Request $request, $laboratoire_id)
    {
        $date = $request->input('date');

        // Validation de la date
        if (!$date) {
            return response()->json(['error' => 'Date requise'], 400);
        }

        // Horaires de base du laboratoire (8h-18h par exemple)
        $horaires_base = [
            ['debut' => '08:00', 'fin' => '09:00'],
            ['debut' => '09:00', 'fin' => '10:00'],
            ['debut' => '10:00', 'fin' => '11:00'],
            ['debut' => '11:00', 'fin' => '12:00'],
            ['debut' => '14:00', 'fin' => '15:00'],
            ['debut' => '15:00', 'fin' => '16:00'],
            ['debut' => '16:00', 'fin' => '17:00'],
            ['debut' => '17:00', 'fin' => '18:00'],
        ];

        try {
            // Récupérer les horaires déjà réservés pour cette date et ce laboratoire
            $horaires_reserves = DB::table('reservations')
                ->join('horaire_reservation', 'reservations.id', '=', 'horaire_reservation.reservation_id')
                ->where('reservations.laboratoire_id', $laboratoire_id)
                ->where('reservations.date', $date)
                ->whereIn('reservations.statut', ['en_attente', 'confirmée'])
                ->select('horaire_reservation.heure_debut', 'horaire_reservation.heure_fin')
                ->get();

            // Filtrer les horaires disponibles
            $horaires_disponibles = collect($horaires_base)->filter(function ($horaire) use ($horaires_reserves) {
                foreach ($horaires_reserves as $reserve) {
                    if ($horaire['debut'] == $reserve->heure_debut && $horaire['fin'] == $reserve->heure_fin) {
                        return false;
                    }
                }
                return true;
            });

            return response()->json($horaires_disponibles->values());
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des horaires: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Enregistrer une nouvelle réservation
     */
    public function store(Request $request)
    {
        // Log pour debug
        Log::info('Données reçues pour réservation:', $request->all());

        $request->validate([
            'laboratoire_id' => 'required|exists:laboratoires,id',
            'date' => 'required|date|after_or_equal:today',
            'equipements' => 'required|array|min:1',
            'equipements.*' => 'exists:equipements,id',
            'horaires' => 'required|array|min:1',
            'horaires.*.debut' => 'required|string',
            'horaires.*.fin' => 'required|string',
            'objectif' => 'required|string|max:500',
        ], [
            'date.after_or_equal' => 'La date de réservation doit être aujourd\'hui ou dans le futur.',
            'equipements.required' => 'Veuillez sélectionner au moins un équipement.',
            'horaires.required' => 'Veuillez sélectionner au moins un créneau horaire.',
            'objectif.required' => 'L\'objectif de la réservation est obligatoire.',
        ]);

        try {
            DB::beginTransaction();

            // Créer la réservation
            $reservation = Reservation::create([
                'date' => $request->date,
                'objectif' => $request->objectif,
                'user_id' => Auth::id(),
                'laboratoire_id' => $request->laboratoire_id,
                'statut' => 'en_attente'
            ]);

            Log::info('Réservation créée avec ID: ' . $reservation->id);

            // Ajouter les horaires
            foreach ($request->horaires as $horaire) {
                HoraireReservation::create([
                    'reservation_id' => $reservation->id,
                    'heure_debut' => $horaire['debut'],
                    'heure_fin' => $horaire['fin']
                ]);
            }

            Log::info('Horaires ajoutés pour la réservation: ' . $reservation->id);

            // Associer les équipements à la réservation (table pivot)
            $reservation->equipements()->attach($request->equipements);

            Log::info('Équipements associés pour la réservation: ' . $reservation->id);

            // Recharger la réservation avec les relations
            $reservation->load(['laboratoire', 'equipements', 'horaires', 'user']);

            DB::commit();

            Log::info('Transaction committée pour la réservation: ' . $reservation->id);

            // Envoyer l'email de confirmation
            try {
                Mail::to($reservation->user->email)->send(new ReservationConfirmation($reservation));
                Log::info('Email envoyé pour la réservation: ' . $reservation->id);
            } catch (\Exception $mailException) {
                // Log l'erreur mais ne pas faire échouer la réservation
                Log::error('Erreur envoi email réservation: ' . $mailException->getMessage());
            }

            // Si c'est une requête AJAX, retourner les données pour le popup
            if ($request->ajax()) {
                Log::info('Retour de la réponse AJAX pour la réservation: ' . $reservation->id);

                return response()->json([
                    'success' => true,
                    'message' => 'Votre réservation a été enregistrée avec succès !',
                    'reservation' => [
                        'id' => $reservation->id,
                        'numero' => str_pad($reservation->id, 6, '0', STR_PAD_LEFT),
                        'laboratoire' => $reservation->laboratoire->nom,
                        'date' => \Carbon\Carbon::parse($reservation->date)->locale('fr')->isoFormat('dddd D MMMM YYYY'),
                        'statut' => ucfirst(str_replace('_', ' ', $reservation->statut)),
                        'equipements' => $reservation->equipements->pluck('nom')->toArray(),
                        'horaires' => $reservation->horaires->map(function($h) {
                            return $h->heure_debut . ' - ' . $h->heure_fin;
                        })->toArray(),
                        'objectif' => $reservation->objectif,
                        'created_at' => $reservation->created_at->locale('fr')->isoFormat('D MMMM YYYY à HH:mm')
                    ]
                ]);
            }

            return redirect()->route('reservations.create')
                ->with('success', 'Votre réservation a été enregistrée avec succès. Elle est en attente de confirmation.')
                ->with('reservation_created', $reservation->id);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la création de la réservation: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de l\'enregistrement de votre réservation: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement de votre réservation: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les réservations de l'utilisateur connecté
     */
    public function mesReservations()
    {
        $reservations = Auth::user()->reservations()
            ->with(['laboratoire', 'horaires', 'equipements'])
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('chercheur.reservations.index', compact('reservations')); // Corrigé ici
    }

    /**
     * Annuler une réservation
     */
    public function annuler($id)
    {
        $reservation = Reservation::where('user_id', Auth::id())
            ->where('id', $id)
            ->whereIn('statut', ['en_attente', 'confirmée'])
            ->firstOrFail();

        $reservation->update(['statut' => 'annulée']);

        return back()->with('success', 'Votre réservation a été annulée avec succès.');
    }
}

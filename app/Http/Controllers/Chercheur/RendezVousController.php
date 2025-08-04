<?php

namespace App\Http\Controllers\Chercheur;

use App\Http\Controllers\Controller;
use App\Mail\ReservationConfirmation;
use App\Models\Equipement;
use App\Models\HoraireReservation;
use App\Models\Laboratoire;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        $laboratoire = Laboratoire::with(['equipements'])->findOrFail($laboratoire_id);
        return view('pages.reservation', compact('laboratoire'));
    }

    /**
     * Récupérer les horaires disponibles pour une date donnée
     */
    public function getHorairesDisponibles(Request $request, $laboratoire_id)
    {
        $date = $request->input('date');
        $now = now();

        if (!$date) {
            return response()->json(['error' => 'Date requise'], 400);
        }

        // Horaires de base du laboratoire
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
            // Récupérer uniquement les réservations actives (non passées)
            $horaires_reserves = DB::table('reservations')
                ->join('horaire_reservation', 'reservations.id', '=', 'horaire_reservation.reservation_id')
                ->where('reservations.laboratoire_id', $laboratoire_id)
                ->where('reservations.date', $date)
                ->where('reservations.statut', 'confirmée')
                ->where(function($query) use ($date, $now) {
                    // Ne considérer que les réservations futures ou en cours
                    $query->where('reservations.date', '>', $now->format('Y-m-d'))
                        ->orWhere(function($q) use ($now) {
                            $q->whereDate('reservations.date', $now->format('Y-m-d'))
                                ->whereTime('horaire_reservation.heure_fin', '>', $now->format('H:i:s'));
                        });
                })
                ->select(
                    DB::raw("TIME_FORMAT(horaire_reservation.heure_debut, '%H:%i') as debut"),
                    DB::raw("TIME_FORMAT(horaire_reservation.heure_fin, '%H:%i') as fin")
                )
                ->get()
                ->map(function($item) {
                    return (array)$item;
                })
                ->toArray();

            return response()->json([
                'horaires_base' => $horaires_base,
                'horaires_reserves' => $horaires_reserves,
                'current_time' => $now->format('H:i'),
                'current_date' => $now->format('Y-m-d')
            ]);

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
        Log::info('Données reçues pour réservation:', $request->all());

        $request->validate([
            'laboratoire_id' => 'required|exists:laboratoires,id',
            'date' => 'required|date|after_or_equal:today',
            'equipements' => 'required|array|min:1',
            'equipements.*' => 'exists:equipements,id',
            'horaires' => 'required|array|min:1',
            'horaires.*.debut' => 'required|string',
            'horaires.*.fin' => 'required|string',
            'objectif' => 'nullable|string|max:500',
        ], [
            'date.after_or_equal' => 'La date de réservation doit être aujourd\'hui ou dans le futur.',
            'equipements.required' => 'Veuillez sélectionner au moins un équipement.',
            'horaires.required' => 'Veuillez sélectionner au moins un créneau horaire.',
        ]);

        try {
            DB::beginTransaction();

            // Vérification des conflits
            $conflits = [];
            foreach ($request->horaires as $horaire_demande) {
                $conflit_existe = DB::table('reservations')
                    ->join('horaire_reservation', 'reservations.id', '=', 'horaire_reservation.reservation_id')
                    ->where('reservations.laboratoire_id', $request->laboratoire_id)
                    ->where('reservations.date', $request->date)
                    ->where('horaire_reservation.heure_debut', $horaire_demande['debut'])
                    ->where('horaire_reservation.heure_fin', $horaire_demande['fin'])
                    ->where('reservations.statut', 'confirmée')
                    ->exists();

                if ($conflit_existe) {
                    $conflits[] = $horaire_demande['debut'] . ' - ' . $horaire_demande['fin'];
                }
            }

            if (!empty($conflits)) {
                DB::rollback();
                $message_conflits = 'Les créneaux suivants ne sont plus disponibles : ' . implode(', ', $conflits);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message_conflits,
                        'type' => 'conflict',
                        'conflits' => $conflits
                    ], 409);
                }

                return back()->withInput()->with('error', $message_conflits);
            }

            // Créer la réservation confirmée directement
            $reservation = Reservation::create([
                'date' => $request->date,
                'objectif' => $request->objectif ?? "Non renseigner",
                'user_id' => Auth::id(),
                'laboratoire_id' => $request->laboratoire_id,
                'statut' => 'confirmée'
            ]);

            // Ajouter les horaires
            foreach ($request->horaires as $horaire) {
                HoraireReservation::create([
                    'reservation_id' => $reservation->id,
                    'heure_debut' => $horaire['debut'],
                    'heure_fin' => $horaire['fin']
                ]);
            }

            // Associer les équipements
            $reservation->equipements()->attach($request->equipements);

            // Recharger la réservation avec les relations
            $reservation->load(['laboratoire', 'equipements', 'horaires', 'user']);

            DB::commit();

            // Envoyer l'email de confirmation
            try {
                Mail::to($reservation->user->email)->send(new ReservationConfirmation($reservation));
                Log::info('Email envoyé pour la réservation: ' . $reservation->id);
            } catch (\Exception $mailException) {
                Log::error('Erreur envoi email réservation: ' . $mailException->getMessage());
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Votre réservation a été confirmée avec succès !',
                    'reservation' => $this->formatReservationData($reservation)
                ]);
            }

            return redirect()->route('reservations.create')
                ->with('success', 'Votre réservation a été confirmée avec succès.')
                ->with('reservation_created', $reservation->id);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la création de la réservation: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de l\'enregistrement de votre réservation: ' . $e->getMessage(),
                    'type' => 'error'
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
        ->with([
            'laboratoire',
            'horaires',
            'equipements',
            'user'
        ])
        ->latest()
        ->get();

        return view('chercheur.reservations.index', compact('reservations'));
    }

    /**
     * Annuler une réservation
     */
    public function annuler($id)
    {
        $reservation = Reservation::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('statut', 'confirmée')
            ->firstOrFail();

        $reservation->update(['statut' => 'annulée']);

        return back()->with('success', 'Votre réservation a été annulée avec succès.');
    }

    /**
     * Formater les données de réservation pour la réponse JSON
     */
    private function formatReservationData($reservation)
    {
        return [
            'id' => $reservation->id,
            'numero' => str_pad($reservation->id, 6, '0', STR_PAD_LEFT),
            'laboratoire' => $reservation->laboratoire->nom,
            'date' => \Carbon\Carbon::parse($reservation->date)->locale('fr')->isoFormat('dddd D MMMM YYYY'),
            'statut' => ucfirst($reservation->statut),
            'equipements' => $reservation->equipements->pluck('nom')->toArray(),
            'horaires' => $reservation->horaires->map(function ($h) {
                return $h->heure_debut . ' - ' . $h->heure_fin;
            })->toArray(),
            'objectif' => $reservation->objectif,
            'created_at' => $reservation->created_at->locale('fr')->isoFormat('D MMMM YYYY à HH:mm')
        ];
    }

    /**
     * Récupérer les détails d'une réservation pour modification
     */

    public function edit(Reservation $reservation)
    {
        // S'assurer que c’est la réservation du chercheur connecté
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier si la réservation n'est pas dépassée
        if ($reservation->date < now()->toDateString()) {
            return redirect()->route('chercheur.reservations.historique')
                ->with('error', 'Impossible de modifier une réservation passée.');
        }

        // Récupérer tous les laboratoires avec leurs équipements
        $laboratoires = Laboratoire::with('equipements')->get();

        // Charger les relations liées à la réservation
        $reservation->load(['laboratoire', 'equipements', 'horaires']);

        return view('chercheur.reservations.edit', compact('reservation', 'laboratoires'));

    }

     public function getEquipements($id, Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $equipements = Equipement::where('laboratoire_id', $id)
            ->with(['reservations' => function ($query) use ($date) {
                $query->whereDate('date', $date)
                      ->where('statut', '!=', 'annulee');
            }])
            ->get()
            ->map(function ($equipement) {
                return [
                    'id' => $equipement->id,
                    'nom' => $equipement->nom,
                    'disponible' => $equipement->reservations->isEmpty()
                ];
            });

        return response()->json($equipements);
    }

    public function getHorairesDisponiblesEdit(Request $request, $laboratoire_id)
    {
        $date = $request->input('date');
        $reservationId = $request->input('reservation_id');
        $now = now();

        if (!$date) {
            return response()->json(['error' => 'Date requise'], 400);
        }

        // Horaires de base du laboratoire (même structure que votre fonction)
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
            // Récupérer les horaires réservés en excluant la réservation actuelle
            $horaires_reserves = DB::table('reservations')
                ->join('horaire_reservation', 'reservations.id', '=', 'horaire_reservation.reservation_id')
                ->where('reservations.laboratoire_id', $laboratoire_id)
                ->where('reservations.date', $date)
                ->where('reservations.statut', 'confirmée')
                ->where('reservations.id', '!=', $reservationId) // Exclure la réservation actuelle
                ->where(function($query) use ($date, $now) {
                    // Ne considérer que les réservations futures ou en cours
                    $query->where('reservations.date', '>', $now->format('Y-m-d'))
                        ->orWhere(function($q) use ($now) {
                            $q->whereDate('reservations.date', $now->format('Y-m-d'))
                                ->whereTime('horaire_reservation.heure_fin', '>', $now->format('H:i:s'));
                        });
                })
                ->select(
                    DB::raw("TIME_FORMAT(horaire_reservation.heure_debut, '%H:%i') as debut"),
                    DB::raw("TIME_FORMAT(horaire_reservation.heure_fin, '%H:%i') as fin")
                )
                ->get()
                ->map(function($item) {
                    return (array)$item;
                })
                ->toArray();

            // Marquer les créneaux comme disponibles ou non
            $creneaux = collect($horaires_base)->map(function ($creneau) use ($horaires_reserves, $now, $date) {
                $debut = $creneau['debut'];
                $fin = $creneau['fin'];

                // Vérifier si le créneau est dans le passé
                $estPasse = false;
                if ($date === $now->format('Y-m-d')) {
                    $estPasse = $now->format('H:i') >= $fin;
                } elseif ($date < $now->format('Y-m-d')) {
                    $estPasse = true;
                }

                // Vérifier si le créneau est réservé
                $estReserve = false;
                foreach ($horaires_reserves as $reserve) {
                    if ($debut === $reserve['debut'] && $fin === $reserve['fin']) {
                        $estReserve = true;
                        break;
                    }
                }

                return [
                    'debut' => $debut,
                    'fin' => $fin,
                    'libelle' => "$debut - $fin",
                    'disponible' => !$estReserve && !$estPasse
                ];
            });

            return response()->json([
                'creneaux' => $creneaux,
                'current_time' => $now->format('H:i'),
                'current_date' => $now->format('Y-m-d')
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des horaires: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    public function getCreneaux(Request $request)
    {
        $laboratoireId = $request->get('laboratoire_id');
        $date = $request->get('date');
        $reservationId = $request->get('reservation_id'); // Pour exclure la réservation actuelle

        // Définir les créneaux disponibles (par exemple de 8h à 18h par tranches d'1h)
        $creneauxDisponibles = [];
        for ($i = 8; $i < 18; $i++) {
            $debut = sprintf('%02d:00', $i);
            $fin = sprintf('%02d:00', $i + 1);
            $creneauxDisponibles[] = [
                'debut' => $debut,
                'fin' => $fin,
                'libelle' => "$debut - $fin"
            ];
        }

        // Récupérer les créneaux déjà réservés pour ce laboratoire et cette date
        $creneauxReserves = HoraireReservation::whereHas('reservation', function ($query) use ($laboratoireId, $date, $reservationId) {
                $query->where('laboratoire_id', $laboratoireId)
                      ->whereDate('date', $date)
                      ->where('statut', '!=', 'annulee');

                // Exclure la réservation actuelle lors de la modification
                if ($reservationId) {
                    $query->where('id', '!=', $reservationId);
                }
            })
            ->get(['heure_debut', 'heure_fin']);

        // Marquer les créneaux comme disponibles ou non
        $creneaux = collect($creneauxDisponibles)->map(function ($creneau) use ($creneauxReserves) {
            $debut = Carbon::createFromFormat('H:i', $creneau['debut']);
            $fin = Carbon::createFromFormat('H:i', $creneau['fin']);

            $disponible = true;
            foreach ($creneauxReserves as $reserve) {
                $debutReserve = Carbon::createFromFormat('H:i:s', $reserve->heure_debut);
                $finReserve = Carbon::createFromFormat('H:i:s', $reserve->heure_fin);

                // Vérifier s'il y a chevauchement
                if ($debut->lt($finReserve) && $fin->gt($debutReserve)) {
                    $disponible = false;
                    break;
                }
            }

            return [
                'debut' => $creneau['debut'],
                'fin' => $creneau['fin'],
                'libelle' => $creneau['libelle'],
                'disponible' => $disponible
            ];
        });

        return response()->json($creneaux);
    }
}

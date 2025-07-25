<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanifierMaintenanceRequest;
use App\Models\Equipement;
use App\Models\Maintenance;
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
            'equipements_maintenances' => Equipement::where('statut', 'maintenance')->count(),
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

    public function equipementsMaintenances()
    {
        // On récupère uniquement les équipements marqués comme maintenance
        $equipements = Equipement::where('statut', 'maintenance')
            ->with('laboratoires')
            ->latest()
            ->get();

        return view('technicien.equipements.index', compact('equipements'));
    }

    public function historiqueMaintenances()
    {
        $user = Auth::user();

        $equipements = Equipement::where('statut', 'maintenance')->get();

        $maintenances = Maintenance::with('equipement')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('technicien.maintenances.index', compact('maintenances', 'equipements'));
    }

    public function planifierMaintenance(PlanifierMaintenanceRequest $request)
    {
        $validated = $request->validated();

        Maintenance::create([
            'equipement_id' => $validated['equipement_id'],
            'user_id' => auth()->id(),
            'date_prevue' => $validated['date_prevue'],
            'description' => $validated['description'],
            'statut' => 'en_cours',
        ]);

        Equipement::where('id', $validated['equipement_id'])
            ->where('statut', '!=', 'maintenance')
            ->update(['statut' => 'maintenance']);

        return redirect()->back()->with('success', 'Maintenance planifiée avec succès.');
    }

    public function modifierMaintenance(PlanifierMaintenanceRequest $request, $id)
    {
        $validated = $request->validated();

        // 1. On récupère la maintenance à modifier ou on échoue si elle n'existe pas
        $maintenance = Maintenance::findOrFail($id);

        // 2. On met à jour la maintenance
        $maintenance->update([
            'equipement_id' => $validated['equipement_id'],
            'date_prevue'   => $validated['date_prevue'],
            'description'   => $validated['description'],
            'statut'        => $validated['statut'] ?? $maintenance->statut, // Garde le statut existant si non fourni
        ]);

        // 3. On met à jour le statut de l’équipement selon le nouveau statut de la maintenance
        $nouveauStatutEquipement = $validated['statut'] === 'terminée' ? 'disponible' : 'maintenance';

        Equipement::where('id', $validated['equipement_id'])
                ->where('statut', '!=', $nouveauStatutEquipement)
                ->update(['statut' => $nouveauStatutEquipement]);

        return redirect()->back()->with('success', 'Maintenance modifiée avec succès.');
    }

    public function terminer($id)
    {
        $maintenance = Maintenance::findOrFail($id);

        $maintenance->update([
            'statut' => 'terminée',
        ]);

        // Optionnel : changer le statut de l'équipement si besoin
        $maintenance->equipement->update([
            'statut' => 'disponible',
        ]);

        return redirect()->back()->with('success', 'Maintenance marquée comme terminée avec succès.');
    }

    public function destroy(Maintenance $maintenance)
    {
        // Vérifier que le technicien connecté est bien celui qui a créé la maintenance (sécurité)
        if (Auth::id() !== $maintenance->user_id) {
            abort(403, 'Action non autorisée.');
        }

        $maintenance->delete();

        return redirect()->back()->with('success', 'La maintenance a été supprimée avec succès.');
    }

}

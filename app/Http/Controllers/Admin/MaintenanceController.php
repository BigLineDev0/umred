<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanifierMaintenanceRequest;
use App\Models\Equipement;
use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{

    public function historiqueMaintenances()
    {
        $user = Auth::user();

        $equipements = Equipement::where('statut', 'maintenance')->get();

        $techniciens = User::role('technicien')->get();

        $maintenances = Maintenance::with(['equipement', 'user'])
            ->latest()
            ->get();

        return view('admin.maintenances.index', compact('maintenances', 'equipements', 'techniciens'));
    }

    public function planifierMaintenance(PlanifierMaintenanceRequest $request)
    {
        $validated = $request->validated();

        Maintenance::create([
            'equipement_id' => $validated['equipement_id'],
            'user_id' => $validated['user_id'] ?? Auth::id(),
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

        $maintenance = Maintenance::findOrFail($id);

        $maintenance->update([
            'equipement_id' => $validated['equipement_id'],
            'user_id'       => $validated['user_id'] ?? Auth::id(),
            'date_prevue'   => $validated['date_prevue'],
            'description'   => $validated['description'],
            'statut'        => $validated['statut'] ?? $maintenance->statut,
        ]);


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

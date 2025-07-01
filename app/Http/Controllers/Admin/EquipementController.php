<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EquipementRequest;
use App\Models\Equipement;
use App\Models\Laboratoire;
use Illuminate\Http\Request;

class EquipementController extends Controller
{
    public function index()
    {
        $equipements = Equipement::with('laboratoires')->latest()->get();
        $laboratoires = Laboratoire::all();

        return view('admin.equipements.index', compact('equipements', 'laboratoires'));
    }

    public function store(EquipementRequest $request)
    {
        $equipement = Equipement::create($request->only(['nom', 'description', 'statut']));
        $equipement->laboratoires()->attach($request->laboratoires);

        return redirect()->route('admin.equipements.index')->with('success', 'Équipement ajouté avec succès.');
    }

    public function show(Equipement $equipement)
    {
        $equipement->load('laboratoires');
        return view('admin.equipements.show', compact('equipement'));
    }

    public function edit(Equipement $equipement)
    {
        $equipement->load('laboratoires');
        $laboratoires = Laboratoire::all();

        return view('admin.equipements.edit', compact('equipement', 'laboratoires'));
    }

    public function update(EquipementRequest $request, Equipement $equipement)
    {
        $equipement->update($request->only(['nom', 'description', 'statut']));
        $equipement->laboratoires()->sync($request->laboratoires);

        return redirect()->route('admin.equipements.index')->with('success', 'Équipement mis à jour avec succès.');
    }

    public function destroy(Equipement $equipement)
    {
        $equipement->laboratoires()->detach();
        $equipement->delete();

        return redirect()->route('admin.equipements.index')->with('success', 'Équipement supprimé avec succès.');
    }
}

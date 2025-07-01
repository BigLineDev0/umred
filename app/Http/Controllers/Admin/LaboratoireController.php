<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LaboratoireRequest;
use App\Models\Laboratoire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaboratoireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laboratoires = Laboratoire::latest()->get();
        return view('admin.laboratoires.index', compact('laboratoires'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.laboratoires.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LaboratoireRequest $request)
    {
        $data = $request->only(['nom', 'description', 'localisation']);
        $data['statut'] = 'actif';

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('laboratoires', 'public');
        }

        Laboratoire::create($data);

        return redirect()->route('admin.laboratoires.index')->with('success', 'Laboratoire ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Laboratoire $laboratoire)
    {
        return view('admin.laboratoires.show', compact('laboratoire'));
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit(Laboratoire $laboratoire)
    {
        return view('admin.laboratoires.show', compact('laboratoire'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LaboratoireRequest $request, Laboratoire $laboratoire)
    {
        $data = $request->only(['nom', 'description', 'localisation', 'statut']);

        if ($request->hasFile('photo')) {
            if ($laboratoire->photo) {
                Storage::disk('public')->delete($laboratoire->photo);
            }
            $data['photo'] = $request->file('photo')->store('laboratoires', 'public');
        }

        $laboratoire->update($data);

        return redirect()->route('admin.laboratoires.index')->with('success', 'Laboratoire mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laboratoire $laboratoire)
    {
        if ($laboratoire->photo) {
            Storage::disk('public')->delete($laboratoire->photo);
        }

        $laboratoire->delete();

        return redirect()->route('admin.laboratoires.index')->with('success', 'Laboratoire supprimé avec succès.');
    }
}

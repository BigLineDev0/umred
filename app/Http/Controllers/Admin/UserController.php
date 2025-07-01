<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Mail\SendUserCredentials;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->get();
        $roles = Role::all();
        return view('admin.utilisateurs.index', compact('users', 'roles'));
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();

        // Photo
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        // Statut par défaut actif (1)
        $data['statut'] = $request->has('statut') ? 1 : 0;

        // Générer un mot de passe aléatoire
        $plainPassword = Str::random(10);
        $data['password'] = Hash::make($plainPassword);

        $user = User::create($data);

        // Attribution du rôle
        $user->assignRole($request->role);

        // Envoi du mot de passe par email
        Mail::to($user->email)->send(new SendUserCredentials($user, $plainPassword));

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur ajouté avec succès. Les identifiants ont été envoyés par email.');
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($user->photo) Storage::disk('public')->delete($user->photo);
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        // $data['statut'] = $request->has('statut') ? 1 : 0;

        $user->update($data);

        if ($request->role) {
            $user->syncRoles([$request->role]);
        }

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();
        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function show(User $user)
    {
        return response()->json($user->load('roles'));
    }
}

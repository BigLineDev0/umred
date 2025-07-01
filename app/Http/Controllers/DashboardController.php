<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Redirection vers le dashboard approprié selon le rôle
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('chercheur')) {
            return redirect()->route('chercheur.dashboard');
        } elseif ($user->hasRole('technicien')) {
            return redirect()->route('technicien.dashboard');
        }

        // Par défaut, redirection vers le dashboard général
        return view('dashboard');
    }
}

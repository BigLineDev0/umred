<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipement;
use App\Models\Laboratoire;
use App\Models\Maintenance;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_laboratories' => Laboratoire::count(),
            'total_equipments' => Equipement::count(),
            'pending_reservations' => Reservation::where('statut', 'en_attente')->count(),
            'active_maintenances' => Maintenance::where('statut', 'en_cours')->count(),
            'available_equipments' => Equipement::where('statut', 'disponible')->count(),
            'occupied_equipments' => Equipement::where('statut', 'indisponible')->count(),

            // Comptage par rôle grâce à Laravel Permission
            'admins'      => User::role('admin')->count(),
            'chercheurs'  => User::role('chercheur')->count(),
            'techniciens' => User::role('technicien')->count(),
        ];

        $recent_reservations = Reservation::with(['user', 'equipement'])
            ->latest()
            ->take(5)
            ->get();

        $recent_maintenances = Maintenance::with(['user', 'equipement'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_reservations', 'recent_maintenances'));
    }
}

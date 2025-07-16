<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EquipementController;
use App\Http\Controllers\Admin\LaboratoireController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Chercheur\ChercheurController;
use App\Http\Controllers\Chercheur\RendezVousController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Technicien\TechnicienController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/a-propos', [PageController::class, 'about'])->name('about');
Route::get('/laboratoires', [PageController::class, 'laboratoires'])->name('laboratoires');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Route dashboard principale avec redirection par rôle
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('chercheur')) {
        return redirect()->route('chercheur.dashboard');
    }

    if ($user->hasRole('technicien')) {
        return redirect()->route('technicien.dashboard');
    }

    return abort(403, 'Rôle non autorisé.');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes communes à tous les utilisateurs authentifiés
Route::middleware(['auth', 'verified'])->group(function () {

    // Routes de profil - accessibles à tous les rôles
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes de réservation communes
    Route::get('/reservations/create/{laboratoire}', [RendezVousController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [RendezVousController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/horaires/{laboratoire_id}', [RendezVousController::class, 'getHorairesDisponibles'])->name('reservations.horaires');

    // Routes Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::resource('laboratoires', LaboratoireController::class);
        Route::resource('equipements', EquipementController::class);
        Route::resource('utilisateurs', UserController::class);
        Route::resource('reservations', ReservationController::class);
        Route::post('reservations/{reservation}/statut', [ReservationController::class, 'updateStatut'])->name('reservations.update-statut');
        Route::get('maintenances', [MaintenanceController::class, 'historiqueMaintenances'])->name('maintenances.index');
        Route::post('maintenances', [MaintenanceController::class, 'planifierMaintenance'])->name('maintenances.store');
        Route::put('maintenances/{id}', [MaintenanceController::class, 'modifierMaintenance'])->name('maintenances.update');
        Route::put('maintenances/{maintenance}/terminer', [MaintenanceController::class, 'terminer'])->name('maintenances.terminer');
        Route::delete('maintenances/{maintenance}', [MaintenanceController::class, 'destroy'])->name('maintenances.destroy');
    });

    // Routes Chercheur
    Route::middleware('role:chercheur')->prefix('chercheur')->name('chercheur.')->group(function () {
        Route::get('/dashboard', [ChercheurController::class, 'index'])->name('dashboard');
        Route::get('/equipements-disponibles', [ChercheurController::class, 'equipementsDisponibles'])->name('equipements.disponibles');
        Route::get('/reservations/historique', [RendezVousController::class, 'mesReservations'])->name('reservations.historique');
        Route::put('/reservations/{reservation}', [RendezVousController::class, 'update'])->name('reservations.update');
        Route::patch('/reservations/{reservation}/annuler', [RendezVousController::class, 'annuler'])->name('reservations.annuler');
    });

    // Routes Technicien
    Route::middleware('role:technicien')->prefix('technicien')->name('technicien.')->group(function () {
        Route::get('/dashboard', [TechnicienController::class, 'index'])->name('dashboard');
        Route::get('/equipements-maintenances', [TechnicienController::class, 'equipementsMaintenances'])->name('equipements.maintenances');
        Route::get('/maintenances', [TechnicienController::class, 'historiqueMaintenances'])->name('historique.maintenances');
        Route::post('/maintenances', [TechnicienController::class, 'planifierMaintenance'])->name('maintenances.store');
        Route::put('/maintenances/{id}', [TechnicienController::class, 'modifierMaintenance'])->name('maintenances.update');
        Route::put('/maintenances/{maintenance}/terminer', [TechnicienController::class, 'terminer'])->name('maintenances.terminer');
        Route::delete('maintenances/{maintenance}', [TechnicienController::class, 'destroy'])->name('maintenances.destroy');


    });
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EquipementController;
use App\Http\Controllers\Admin\LaboratoireController;
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

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/a-propos', [PageController::class, 'about'])->name('about');
Route::get('/laboratoires', [PageController::class, 'laboratoires'])->name('laboratoires');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');


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
})->middleware(['auth'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::resource('laboratoires', LaboratoireController::class);
        Route::resource('equipements', EquipementController::class);
        Route::resource('utilisateurs', UserController::class);
        Route::resource('reservations', ReservationController::class);
        Route::post('reservations/{reservation}/statut', [ReservationController::class, 'updateStatut'])->name('reservations.update-statut');

    });

    // Routes Chercheur
    Route::middleware('role:chercheur')->prefix('chercheur')->name('chercheur.')->group(function () {
        Route::get('/dashboard', [ChercheurController::class, 'index'])->name('dashboard');
        Route::get('/equipements-disponibles', [ChercheurController::class, 'equipementsDisponibles'])->name('equipements.disponibles');
        Route::get('/reservations/historique', [RendezVousController::class, 'mesReservations'])->name('reservations.historique');
        Route::put('/reservations/{reservation}', [RendezVousController::class, 'update'])->name('reservations.update');
        Route::patch('/reservations/{reservation}/annuler', [RendezVousController::class, 'annuler'])->name('reservations.annuler');
    });

    Route::get('/reservations/create/{laboratoire}', [RendezVousController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [RendezVousController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/horaires/{laboratoire_id}', [RendezVousController::class, 'getHorairesDisponibles'])->name('reservations.horaires');


    // Routes Technicien
    Route::middleware('role:technicien')->prefix('technicien')->name('technicien.')->group(function () {
        Route::get('/dashboard', [TechnicienController::class, 'index'])->name('dashboard');
    });
});

require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Chauffeurs;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Vehicules;
use App\Livewire\Admin\Taches;
use App\Livewire\Admin\DemandesTaches;
use App\Livewire\Admin\Notifications;
use App\Livewire\Admin\Affectations;
use App\Livewire\Admin\Dommages;
use App\Livewire\Chauffeur\TacheDetail;
use App\Livewire\Chauffeur\Taches as ChauffeurTaches;
use App\Livewire\Chauffeur\PriseEnCharge;
use App\Livewire\Chauffeur\DommageInterface;
use App\Http\Controllers\ExportController;
use App\Livewire\Admin\Map as AdminMap;


use App\Livewire\Chauffeur\Dashboard as ChauffeurDashboard;


Route::get('/', function () {
    return view('welcome');
});

// Route de redirection après connexion
Route::get('/redirect-after-login', function () {
    $user = \Illuminate\Support\Facades\Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'chauffeur') {
        return redirect()->route('chauffeur.dashboard');
    }

    // Fallback par défaut
    return redirect('/dashboard');
})->middleware('auth')->name('redirect.after.login');

// Route de test FCM (à supprimer en production)
Route::get('/test-fcm', function () {
    return view('test-fcm');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});



// La route ci-dessous doublonnait avec le groupe admin et utilisait un middleware incorrect
// Suppression pour éviter les incohérences



Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/vehicules', Vehicules::class)->name('vehicules');
    Route::get('/chauffeurs', Chauffeurs::class)->name('chauffeurs');
    Route::get('/affectations', Affectations::class)->name('affectations');
    Route::get('/taches', Taches::class)->name('taches');
    Route::get('/demandes-taches', DemandesTaches::class)->name('demandes-taches');
    Route::get('/dommages', Dommages::class)->name('dommages');
    Route::get('/notifications', Notifications::class)->name('notifications');
    Route::get('/map', AdminMap::class)->name('map');

    // Export Excel
    Route::get('export/taches', [ExportController::class, 'tachesParChauffeur'])->name('export.taches');
});



Route::middleware(['auth', 'isChauffeur'])->group(function () {
    Route::get('/chauffeur/dashboard', ChauffeurDashboard::class)->name('chauffeur.dashboard');
    Route::get('/chauffeur/taches', ChauffeurTaches::class)->name('chauffeur.taches');
    Route::get('/chauffeur/prise-en-charge', PriseEnCharge::class)->name('chauffeur.prise-en-charge');
    Route::get('/chauffeur/dommages/{affectationId}', DommageInterface::class)->name('chauffeur.dommages');
    Route::get('/taches/{id}/detail', TacheDetail::class)->name('chauffeur.tache.detail');
});

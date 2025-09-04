<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Chauffeurs;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Vehicules;
use App\Livewire\Admin\Taches;
use App\Livewire\Admin\Notifications;
use App\Livewire\Chauffeur\TacheDetail;


use App\Livewire\Chauffeur\Dashboard as ChauffeurDashboard;


Route::get('/', function () {
    return view('welcome');
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



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/chauffeurs', Chauffeurs::class)->name('admin.chauffeurs');
});



Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::get('vehicules', Vehicules::class)->name('vehicules');
    Route::get('chauffeurs', Chauffeurs::class)->name('chauffeurs');
    Route::get('taches', Taches::class)->name('taches');
    Route::get('notifications', Notifications::class)->name('notifications');
});



Route::middleware(['auth', 'isChauffeur'])->group(function () {
    Route::get('/chauffeur/dashboard', ChauffeurDashboard::class)->name('chauffeur.dashboard');
    Route::get('/taches/{id}/detail', TacheDetail::class)->name('chauffeur.tache.detail');
});

<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Vehicule;
use App\Models\Tache;
use App\Models\Affectation;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        $adminId = Auth::user()->user_id;

        $chauffeursCount = User::where('role', 'chauffeur')
                               ->where('admin_id', $adminId)
                               ->count();

        $vehiculesCount = Vehicule::where('admin_id', $adminId)->count();

        $taches = Tache::whereHas('chauffeur', fn($q) => $q->where('admin_id', $adminId));
        $tachesEnAttente = (clone $taches)->where('status', 'en_attente')->count();
        $tachesEnCours = (clone $taches)->where('status', 'en_cours')->count();
        $tachesTerminees = (clone $taches)->where('status', 'terminée')->count();

        $affectationsActives = Affectation::whereHas('chauffeur', fn($q) => $q->where('admin_id', $adminId))
                                          ->where('status', 'en_cours')
                                          ->count();

        $dernieresTaches = (clone $taches)
            ->with(['chauffeur', 'vehicule.marque', 'vehicule.modele'])
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', compact(
            'chauffeursCount', 'vehiculesCount',
            'tachesEnAttente', 'tachesEnCours', 'tachesTerminees',
            'affectationsActives', 'dernieresTaches'
        ))->layout('layouts.admin');
    }
}

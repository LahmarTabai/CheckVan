<?php

namespace App\Livewire\Chauffeur;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Affectation;
use App\Models\Tache;

class Dashboard extends Component
{
    public $vehiculeAffecte;
    public $tachesRecentes;

    public function mount()
    {
        $chauffeurId = Auth::user()->user_id;

        // Récupère la dernière affectation en cours
        $this->vehiculeAffecte = Affectation::with('vehicule')
            ->where('chauffeur_id', $chauffeurId)
            ->where('status', 'en_cours')
            ->latest()
            ->first();

        // Récupère les 5 dernières tâches du chauffeur
        $this->tachesRecentes = Tache::where('chauffeur_id', $chauffeurId)
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.chauffeur.dashboard')->layout('layouts.chauffeur');
    }
}

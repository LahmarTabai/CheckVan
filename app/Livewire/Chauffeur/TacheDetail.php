<?php

namespace App\Livewire\Chauffeur;

use Livewire\Component;
use App\Models\Tache;
use Illuminate\Support\Facades\Auth;

class TacheDetail extends Component
{
    public $tache;

    public function mount($id)
    {
        $this->tache = Tache::with(['vehicule.marque', 'vehicule.modele', 'photos'])
            ->where('chauffeur_id', Auth::user()->user_id)
            ->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.chauffeur.tache-detail')->layout('layouts.chauffeur');
    }
}

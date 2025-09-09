<?php

namespace App\Livewire\Chauffeur;

use Livewire\Component;
use App\Models\Tache;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Taches extends Component
{
    public function render()
    {
        $taches = Tache::where('chauffeur_id', Auth::id())
                        ->with('vehicule')
                        ->latest()
                        ->get();

        return view('livewire.chauffeur.taches', compact('taches'));
    }

    public function commencerTache($id)
    {
        $tache = Tache::where('id', $id)
                    ->where('chauffeur_id', Auth::id())
                    ->where('status', 'en_attente')
                    ->firstOrFail();

        // Doit être validée par l'admin
        if (!$tache->is_validated) {
            session()->flash('success', "Tâche non validée par l'admin.");
            return;
        }

        // Vérifier qu'un véhicule est pris en charge et correspond
        $affectation = \App\Models\Affectation::where('chauffeur_id', Auth::id())
            ->where('status', 'en_cours')
            ->first();

        if (!$affectation || $affectation->vehicule_id !== $tache->vehicule_id) {
            session()->flash('success', "Vous devez d'abord prendre en charge le véhicule assigné.");
            return;
        }

        $tache->update([
            'status' => 'en_cours',
            'start_date' => Carbon::now(),
            // plus tard : 'start_latitude' => ..., 'start_longitude' => ...
        ]);

        session()->flash('success', 'Tâche commencée.');
    }

    public function terminerTache($id)
    {
        $tache = Tache::where('id', $id)
                    ->where('chauffeur_id', Auth::id())
                    ->where('status', 'en_cours')
                    ->firstOrFail();

        $tache->update([
            'status' => 'terminée',
            'end_date' => Carbon::now(),
            // plus tard : 'end_latitude' => ..., 'end_longitude' => ...
        ]);

        session()->flash('success', 'Tâche terminée.');
    }
}

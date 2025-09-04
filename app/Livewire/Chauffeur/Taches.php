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

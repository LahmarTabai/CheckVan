<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Location;
use App\Models\Tache;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Map extends Component
{
    public $locations = [];

    public function refresh()
    {
        $adminId = Auth::user()->user_id;

        // Récupérer les positions GPS récentes des chauffeurs de cet admin
        $chauffeursAdmin = User::where('role', 'chauffeur')
            ->where('admin_id', $adminId)
            ->get();

        $this->locations = [];

        foreach ($chauffeursAdmin as $chauffeur) {
            // Récupérer la dernière position GPS du chauffeur
            $lastLocation = Location::where('chauffeur_id', $chauffeur->user_id)
                ->latest('recorded_at')
                ->first();

            if ($lastLocation) {
                // Récupérer la tâche en cours du chauffeur
                $tacheEnCours = Tache::where('chauffeur_id', $chauffeur->user_id)
                    ->where('status', 'en_cours')
                    ->with('vehicule')
                    ->first();

                $this->locations[] = [
                    'latitude' => (float) $lastLocation->latitude,
                    'longitude' => (float) $lastLocation->longitude,
                    'recorded_at' => $lastLocation->recorded_at,
                    'chauffeur_id' => $chauffeur->user_id,
                    'chauffeur_nom' => $chauffeur->nom . ' ' . $chauffeur->prenom,
                    'vehicule' => $tacheEnCours ? $tacheEnCours->vehicule->immatriculation : 'Aucun véhicule',
                    'tache_id' => $tacheEnCours ? $tacheEnCours->id : null,
                    'status' => $tacheEnCours ? 'en_cours' : 'disponible'
                ];
            }
        }
    }

    public function render()
    {
        if (empty($this->locations)) {
            $this->refresh();
        }

        return view('livewire.admin.map')->layout('layouts.admin');
    }
}




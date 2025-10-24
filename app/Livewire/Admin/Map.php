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
    public $chauffeurFiltre = null; // null = tous les chauffeurs
    public $chauffeurs = []; // Liste des chauffeurs pour le dropdown

    public function mount()
    {
        $adminId = Auth::user()->user_id;

        // Charger la liste des chauffeurs de cet admin pour le filtre
        $this->chauffeurs = User::where('role', 'chauffeur')
            ->where('admin_id', $adminId)
            ->select('user_id', 'nom', 'prenom')
            ->orderBy('nom')
            ->get();
    }

    public function refresh()
    {
        $adminId = Auth::user()->user_id;

        // Récupérer les positions GPS récentes des chauffeurs de cet admin
        $query = User::where('role', 'chauffeur')
            ->where('admin_id', $adminId);

        // Si un chauffeur est sélectionné, filtrer uniquement pour lui
        if ($this->chauffeurFiltre) {
            $query->where('user_id', $this->chauffeurFiltre);
        }

        $chauffeursAdmin = $query->get();

        $locations = [];

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

                $locations[] = [
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

        // Mets à jour la propriété pour l'affichage des compteurs, etc.
        $this->locations = $locations;

        // 🔔 Envoie aussi les données côté JS pour mettre à jour la carte sans la recréer
        // Livewire v3 :
        $this->dispatch('locations-updated', locations: $locations);
    }

    // Méthode appelée quand le filtre change
    public function updatedChauffeurFiltre()
    {
        $this->refresh();
    }

    public function render()
    {
        if (empty($this->locations)) {
            $this->refresh();
        }

        return view('livewire.admin.map')->layout('layouts.admin');
    }
}




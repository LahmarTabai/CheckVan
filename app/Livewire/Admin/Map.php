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

    // Filtres
    public $search = '';
    public $filterEnCours = true;
    public $filterDisponible = true;
    public $filterHorsLigne = true;

    // Auto-refresh
    public $autoRefresh = false;

    // KPI
    public $kpis = [
        'total' => 0,
        'en_cours' => 0,
        'disponible' => 0,
        'hors_ligne' => 0,
        'derniere_maj' => null
    ];

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

    public function toggleAutoRefresh()
    {
        $this->autoRefresh = !$this->autoRefresh;
    }

    public function refresh()
    {
        $adminId = Auth::user()->user_id;
        $now = now();

        // Récupérer les chauffeurs avec leurs relations (optimisé, pas de N+1) ✅
        $query = User::where('role', 'chauffeur')
            ->where('admin_id', $adminId)
            ->with(['lastLocation', 'currentTache.vehicule']);

        // Filtre par chauffeur spécifique
        if ($this->chauffeurFiltre) {
            $query->where('user_id', $this->chauffeurFiltre);
        }

        // Filtre par recherche (nom ou prénom)
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                  ->orWhere('prenom', 'like', '%' . $this->search . '%');
            });
        }

        $chauffeurs = $query->get();

        // Transformer les données + calculer les KPI
        $enCours = 0;
        $disponible = 0;
        $horsLigne = 0;

        $locations = $chauffeurs->filter(function($chauffeur) {
            return $chauffeur->lastLocation !== null;
        })->map(function($chauffeur) use ($now, &$enCours, &$disponible, &$horsLigne) {
            $tache = $chauffeur->currentTache;
            $lastLoc = $chauffeur->lastLocation;

            // Calculer si hors ligne (> 5 minutes)
            $recordedAt = \Carbon\Carbon::parse($lastLoc->recorded_at);
            $isStale = $now->diffInMinutes($recordedAt) > 5;

            // Déterminer le statut
            if ($isStale) {
                $status = 'hors_ligne';
                $horsLigne++;
            } elseif ($tache) {
                $status = 'en_cours';
                $enCours++;
            } else {
                $status = 'disponible';
                $disponible++;
            }

            return [
                'latitude'      => (float) $lastLoc->latitude,
                'longitude'     => (float) $lastLoc->longitude,
                'recorded_at'   => $lastLoc->recorded_at->toIso8601String(),
                'chauffeur_id'  => $chauffeur->user_id,
                'chauffeur_nom' => $chauffeur->nom . ' ' . $chauffeur->prenom,
                'vehicule'      => $tache?->vehicule?->immatriculation ?? 'Aucun véhicule',
                'tache_id'      => $tache?->id,
                'status'        => $status,
                'is_stale'      => $isStale,
            ];
        })->filter(function($location) {
            // Appliquer les filtres de statut
            if ($location['status'] === 'en_cours' && !$this->filterEnCours) return false;
            if ($location['status'] === 'disponible' && !$this->filterDisponible) return false;
            if ($location['status'] === 'hors_ligne' && !$this->filterHorsLigne) return false;
            return true;
        })->values()->all();

        // Mettre à jour les KPI
        $this->kpis = [
            'total' => count($locations),
            'en_cours' => $enCours,
            'disponible' => $disponible,
            'hors_ligne' => $horsLigne,
            'derniere_maj' => $now->format('H:i:s')
        ];

        $this->locations = $locations;

        // 🔔 Envoie les données à la carte
        $this->dispatch('locations-updated', locations: $locations);
    }

    // Méthodes appelées quand les filtres changent
    public function updatedChauffeurFiltre()
    {
        $this->refresh();
    }

    public function updatedSearch()
    {
        $this->refresh();
    }

    public function updatedFilterEnCours()
    {
        $this->refresh();
    }

    public function updatedFilterDisponible()
    {
        $this->refresh();
    }

    public function updatedFilterHorsLigne()
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




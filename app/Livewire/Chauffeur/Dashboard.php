<?php

namespace App\Livewire\Chauffeur;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Affectation;
use App\Models\Tache;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $vehiculeAffecte;
    public $tachesRecentes;
    public $statistiques;
    public $tachesEnCours;
    public $tachesEnAttente;

    public function mount()
    {
        $chauffeurId = Auth::user()->user_id;

        // Récupère la dernière affectation en cours
        $this->vehiculeAffecte = Affectation::with(['vehicule.marque', 'vehicule.modele'])
            ->where('chauffeur_id', $chauffeurId)
            ->where('status', 'en_cours')
            ->latest()
            ->first();

        // Récupère les 5 dernières tâches du chauffeur
        $this->tachesRecentes = Tache::with(['vehicule.marque', 'vehicule.modele'])
            ->where('chauffeur_id', $chauffeurId)
            ->latest()
            ->take(5)
            ->get();

        // Tâches en cours
        $this->tachesEnCours = Tache::with(['vehicule.marque', 'vehicule.modele'])
            ->where('chauffeur_id', $chauffeurId)
            ->where('status', 'en_cours')
            ->get();

        // Tâches en attente
        $this->tachesEnAttente = Tache::with(['vehicule.marque', 'vehicule.modele'])
            ->where('chauffeur_id', $chauffeurId)
            ->where('status', 'en_attente')
            ->get();

        // Statistiques complètes
        $this->statistiques = $this->calculerStatistiques($chauffeurId);
    }

    private function calculerStatistiques($chauffeurId)
    {
        $aujourdhui = Carbon::today();
        $ceMois = Carbon::now()->startOfMonth();
        $ceMoisFin = Carbon::now()->endOfMonth();

        // Tâches totales
        $totalTaches = Tache::where('chauffeur_id', $chauffeurId)->count();

        // Tâches terminées
        $tachesTerminees = Tache::where('chauffeur_id', $chauffeurId)
            ->where('status', 'terminée')
            ->count();

        // Tâches aujourd'hui
        $tachesAujourdhui = Tache::where('chauffeur_id', $chauffeurId)
            ->whereDate('start_date', $aujourdhui)
            ->count();

        // Tâches ce mois
        $tachesCeMois = Tache::where('chauffeur_id', $chauffeurId)
            ->whereBetween('start_date', [$ceMois, $ceMoisFin])
            ->count();

        // Kilométrage total parcouru
        $kilometrageTotal = Tache::where('chauffeur_id', $chauffeurId)
            ->where('status', 'terminée')
            ->whereNotNull('debut_kilometrage')
            ->whereNotNull('fin_kilometrage')
            ->get()
            ->sum(function ($tache) {
                return $tache->fin_kilometrage - $tache->debut_kilometrage;
            });

        // Tâches validées
        $tachesValidees = Tache::where('chauffeur_id', $chauffeurId)
            ->where('is_validated', true)
            ->count();

        // Taux de validation
        $tauxValidation = $tachesTerminees > 0 ? round(($tachesValidees / $tachesTerminees) * 100, 1) : 0;

        return [
            'total_taches' => $totalTaches,
            'taches_terminees' => $tachesTerminees,
            'taches_aujourdhui' => $tachesAujourdhui,
            'taches_ce_mois' => $tachesCeMois,
            'kilometrage_total' => $kilometrageTotal,
            'taches_validees' => $tachesValidees,
            'taux_validation' => $tauxValidation,
            'taches_en_cours' => $this->tachesEnCours->count(),
            'taches_en_attente' => $this->tachesEnAttente->count(),
        ];
    }

    public function render()
    {
        return view('livewire.chauffeur.dashboard')->layout('layouts.chauffeur');
    }
}

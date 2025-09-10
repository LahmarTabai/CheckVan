<?php

namespace App\Livewire\Admin;

use App\Models\Affectation;
use App\Models\User;
use App\Models\Vehicule;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\FcmService;

class Affectations extends Component
{
    public $chauffeur_id, $vehicule_id, $status = 'en_cours', $affectation_id;
    public $date_debut, $date_fin, $description;
    public $isEdit = false;

    // Filtres
    public $filterStatus = '';
    public $filterChauffeur = '';

    public function render()
    {
        $query = Affectation::with(['chauffeur', 'vehicule.marque', 'vehicule.modele'])
            ->whereHas('chauffeur', function ($query) {
                $query->where('admin_id', Auth::user()->user_id);
            });

        // Appliquer les filtres
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterChauffeur) {
            $query->where('chauffeur_id', $this->filterChauffeur);
        }

        $affectations = $query->latest()->get();

            // dd($affectations);

        $chauffeurs = User::where('role', 'chauffeur')
            ->where('admin_id', Auth::user()->user_id)
            ->get();

        // Récupérer les véhicules disponibles (non affectés ou rendus)
        $vehiculesAffectes = Affectation::where('status', 'en_cours')->pluck('vehicule_id');
        $vehicules = Vehicule::with(['marque', 'modele'])
            ->where('admin_id', Auth::user()->user_id)
            ->whereNotIn('id', $vehiculesAffectes)
            ->get();

        return view('livewire.admin.affectations', compact('affectations', 'chauffeurs', 'vehicules'))->layout('layouts.admin');
    }

    public function resetForm()
    {
        $this->chauffeur_id = null;
        $this->vehicule_id = null;
        $this->status = 'en_cours';
        $this->affectation_id = null;
        $this->date_debut = null;
        $this->date_fin = null;
        $this->description = null;
        $this->isEdit = false;
    }

    public function resetFilters()
    {
        $this->filterStatus = '';
        $this->filterChauffeur = '';
    }

    public function save()
    {
        $this->validate([
            'chauffeur_id' => 'required|exists:users,user_id',
            'vehicule_id' => 'required|exists:vehicules,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'description' => 'nullable|string|max:500',
        ]);

        // Règle métier : Un chauffeur ne peut avoir qu'un seul véhicule en_cours à la fois
        $existingAffectation = Affectation::where('chauffeur_id', $this->chauffeur_id)
            ->where('status', 'en_cours')
            ->first();

        if ($existingAffectation) {
            session()->flash('error', 'Ce chauffeur a déjà un véhicule en cours d\'affectation.');
            return;
        }

        // Règle métier : Un véhicule ne peut être affecté qu'à un seul chauffeur à la fois
        $vehiculeAffecte = Affectation::where('vehicule_id', $this->vehicule_id)
            ->where('status', 'en_cours')
            ->first();

        if ($vehiculeAffecte) {
            session()->flash('error', 'Ce véhicule est déjà affecté à un autre chauffeur.');
            return;
        }

        $affectation = Affectation::create([
            'chauffeur_id' => $this->chauffeur_id,
            'vehicule_id' => $this->vehicule_id,
            'status' => $this->status,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'description' => $this->description,
        ]);

        // Notifier l'admin et le chauffeur via FCM si possible
        $chauffeur = User::find($this->chauffeur_id);
        if ($chauffeur && $chauffeur->fcm_token) {
            app(FcmService::class)->sendToToken(
                $chauffeur->fcm_token,
                'Nouvelle affectation',
                'Un véhicule vous a été affecté.',
                ['type' => 'affectation', 'vehicule_id' => $this->vehicule_id]
            );
        }

        session()->flash('success', 'Affectation enregistrée');
        $this->resetForm();
    }

    public function edit($id)
    {
        $aff = Affectation::findOrFail($id);
        $this->affectation_id = $aff->id;
        $this->chauffeur_id = $aff->chauffeur_id;
        $this->vehicule_id = $aff->vehicule_id;
        $this->status = $aff->status;
        $this->date_debut = $aff->date_debut;
        $this->date_fin = $aff->date_fin;
        $this->description = $aff->description;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate([
            'chauffeur_id' => 'required|exists:users,user_id',
            'vehicule_id' => 'required|exists:vehicules,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'description' => 'nullable|string|max:500',
        ]);

        $aff = Affectation::findOrFail($this->affectation_id);
        $aff->update([
            'chauffeur_id' => $this->chauffeur_id,
            'vehicule_id' => $this->vehicule_id,
            'status' => $this->status,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'description' => $this->description,
        ]);

        session()->flash('success', 'Affectation mise à jour');
        $this->resetForm();
    }

    public function delete($id)
    {
        Affectation::findOrFail($id)->delete();
        session()->flash('success', 'Affectation supprimée');
    }

    public function terminerAffectation($id)
    {
        $affectation = Affectation::findOrFail($id);

        if ($affectation->status === 'en_cours') {
            $affectation->update([
                'status' => 'terminée',
                'date_fin' => now()->toDateString()
            ]);
            session()->flash('success', 'Affectation terminée avec succès');
        } else {
            session()->flash('error', 'Cette affectation est déjà terminée');
        }
    }
}

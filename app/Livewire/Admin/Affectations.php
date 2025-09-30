<?php

namespace App\Livewire\Admin;

use App\Models\Affectation;
use App\Models\User;
use App\Models\Vehicule;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Services\FcmService;
use App\Services\ExportService;

class Affectations extends Component
{
    use WithPagination;
    public $chauffeur_id, $vehicule_id, $status = 'en_cours', $affectation_id;
    public $date_debut, $date_fin, $description;
    public $isEdit = false;

    // Filtres
    public $filterStatus = '';
    public $filterChauffeur = '';

    // Recherche dans les formulaires
    public $searchChauffeur = '';
    public $searchVehicule = '';
    public $showChauffeurDropdown = false;
    public $showVehiculeDropdown = false;

    // Modal suppression
    public $showDeleteModal = false;
    public $affectationToDelete = null;

    // Affichage du formulaire
    public $showForm = false;

    // Tri
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function mount()
    {
        $this->resetForm();
    }

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

        // Tri
        $query->orderBy($this->sortField, $this->sortDirection);

        $affectations = $query->paginate(10);

            // dd($affectations);

        // Récupérer les chauffeurs disponibles (sans véhicule en cours)
        $chauffeursAffectes = Affectation::where('status', 'en_cours')->pluck('chauffeur_id');
        $chauffeursQuery = User::where('role', 'chauffeur')
            ->where('admin_id', Auth::user()->user_id);

        // Si on est en mode édition, inclure le chauffeur actuellement affecté
        if ($this->isEdit && $this->chauffeur_id) {
            $chauffeursQuery->where(function($query) use ($chauffeursAffectes) {
                $query->whereNotIn('user_id', $chauffeursAffectes)
                      ->orWhere('user_id', $this->chauffeur_id);
            });
        } else {
            $chauffeursQuery->whereNotIn('user_id', $chauffeursAffectes);
        }

        // Filtrer par recherche si nécessaire
        if ($this->searchChauffeur) {
            $chauffeursQuery->where(function($query) {
                $query->where('nom', 'like', '%' . $this->searchChauffeur . '%')
                      ->orWhere('prenom', 'like', '%' . $this->searchChauffeur . '%');
            });
        }

        $chauffeurs = $chauffeursQuery->get();

        // Récupérer les véhicules disponibles (non affectés ou rendus)
        $vehiculesAffectes = Affectation::where('status', 'en_cours')->pluck('vehicule_id');
        $vehiculesQuery = Vehicule::with(['marque', 'modele'])
            ->where('admin_id', Auth::user()->user_id);

        // Si on est en mode édition, inclure le véhicule actuellement affecté
        if ($this->isEdit && $this->vehicule_id) {
            $vehiculesQuery->where(function($query) use ($vehiculesAffectes) {
                $query->whereNotIn('id', $vehiculesAffectes)
                      ->orWhere('id', $this->vehicule_id);
            });
        } else {
            $vehiculesQuery->whereNotIn('id', $vehiculesAffectes);
        }

        // Filtrer par recherche si nécessaire
        if ($this->searchVehicule) {
            $vehiculesQuery->where(function($query) {
                $query->where('immatriculation', 'like', '%' . $this->searchVehicule . '%')
                      ->orWhereHas('marque', function($q) {
                          $q->where('nom', 'like', '%' . $this->searchVehicule . '%');
                      })
                      ->orWhereHas('modele', function($q) {
                          $q->where('nom', 'like', '%' . $this->searchVehicule . '%');
                      });
            });
        }

        $vehicules = $vehiculesQuery->get();

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
        $this->searchChauffeur = '';
        $this->searchVehicule = '';
        $this->showChauffeurDropdown = false;
        $this->showVehiculeDropdown = false;
        $this->resetErrorBag();
    }

    // Méthodes pour contrôler l'affichage du formulaire
    public function showAddForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function hideForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetFilters()
    {
        $this->filterStatus = '';
        $this->filterChauffeur = '';

        // Réinitialiser les Select2
        $this->dispatch('reset-filter-select2');
    }

    public function updatedFilterStatus()
    {
        \Log::info('Filtre Status changé:', ['filterStatus' => $this->filterStatus]);
    }

    public function updatedFilterChauffeur()
    {
        \Log::info('Filtre Chauffeur changé:', ['filterChauffeur' => $this->filterChauffeur]);
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
        $this->showForm = false;
        $this->resetErrorBag();
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

        // Réinitialiser les dropdowns
        $this->hideDropdowns();

        // Réinitialiser les recherches pour afficher toutes les options
        $this->searchChauffeur = '';
        $this->searchVehicule = '';

        // Déclencher la synchronisation des Select2 après un délai
        $this->dispatch('sync-select2-values');

        // Synchroniser les valeurs du formulaire
        $this->dispatch('sync-form-select2', values: [
            'chauffeur_id' => $this->chauffeur_id,
            'vehicule_id' => $this->vehicule_id,
            'status' => $this->status
        ]);
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

        // Règles métier pour la modification
        if ($this->status === 'en_cours') {
            // Règle métier : Un chauffeur ne peut avoir qu'un seul véhicule en_cours à la fois
            $existingAffectation = Affectation::where('chauffeur_id', $this->chauffeur_id)
                ->where('status', 'en_cours')
                ->where('id', '!=', $this->affectation_id)
                ->first();

            if ($existingAffectation) {
                session()->flash('error', 'Ce chauffeur a déjà un véhicule en cours d\'affectation.');
                return;
            }

            // Règle métier : Un véhicule ne peut être affecté qu'à un seul chauffeur à la fois
            $vehiculeAffecte = Affectation::where('vehicule_id', $this->vehicule_id)
                ->where('status', 'en_cours')
                ->where('id', '!=', $this->affectation_id)
                ->first();

            if ($vehiculeAffecte) {
                session()->flash('error', 'Ce véhicule est déjà affecté à un autre chauffeur.');
                return;
            }
        }

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
        $this->showForm = false;
        $this->resetErrorBag();
    }

    public function confirmDelete($id)
    {
        $this->affectationToDelete = (int) $id;
        $this->showDeleteModal = true;
    }

    public function delete($id)
    {
        Affectation::findOrFail($id)->delete();
        session()->flash('success', 'Affectation supprimée');
        $this->showDeleteModal = false;
        $this->affectationToDelete = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->affectationToDelete = null;
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

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function exportExcel()
    {
        $filters = [
            'status' => $this->filterStatus,
            'chauffeur_id' => $this->filterChauffeur,
        ];

        return ExportService::exportAffectations($filters);
    }

    // Méthodes pour la recherche dans les formulaires
    public function updatedSearchChauffeur()
    {
        $this->showChauffeurDropdown = true;
        $this->chauffeur_id = null;
    }

    public function updatedSearchVehicule()
    {
        $this->showVehiculeDropdown = true;
        $this->vehicule_id = null;
    }

    public function selectChauffeur($chauffeurId, $chauffeurName)
    {
        $this->chauffeur_id = $chauffeurId;
        $this->searchChauffeur = $chauffeurName;
        $this->showChauffeurDropdown = false;
    }

    public function selectVehicule($vehiculeId, $vehiculeName)
    {
        $this->vehicule_id = $vehiculeId;
        $this->searchVehicule = $vehiculeName;
        $this->showVehiculeDropdown = false;
    }

    public function hideDropdowns()
    {
        $this->showChauffeurDropdown = false;
        $this->showVehiculeDropdown = false;
    }
}

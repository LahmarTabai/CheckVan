<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Tache;
use App\Models\User;
use App\Models\Vehicule;
use Livewire\WithPagination;
use App\Services\FcmService;
use App\Services\ExportService;
use Illuminate\Support\Facades\Auth;

class Taches extends Component
{
    use WithPagination;

    // Filtres
    public $statusFilter = '';
    public $chauffeurFilter = '';
    public $vehiculeFilter = '';
    public $validationFilter = '';
    public $dateDebutFilter = '';
    public $dateFinFilter = '';
    public $search = '';

    // Tri
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Modal suppression
    public $showDeleteModal = false;
    public $tacheToDelete = null;

    // Formulaire
    public $tacheId;
    public $chauffeur_id, $vehicule_id, $start_date;
    public $debut_kilometrage, $debut_carburant;
    public $fin_kilometrage, $fin_carburant;
    public $description, $type_tache;
    public $isEdit = false;

    // Modal détails
    public $showDetailsModal = false;

    public function mount()
    {
        $this->resetForm();
    }
    public $selectedTache = null;

    public function render()
    {
        $query = Tache::with(['chauffeur', 'vehicule.marque', 'vehicule.modele'])
            ->whereHas('vehicule', function ($q) {
                $q->where('admin_id', Auth::user()->user_id);
            });

        // Filtres
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->chauffeurFilter) {
            $query->where('chauffeur_id', $this->chauffeurFilter);
        }

        if ($this->vehiculeFilter) {
            $query->where('vehicule_id', $this->vehiculeFilter);
        }

        if ($this->validationFilter !== '') {
            $query->where('is_validated', $this->validationFilter === '1');
        }

        if ($this->dateDebutFilter) {
            $query->whereDate('start_date', '>=', $this->dateDebutFilter);
        }

        if ($this->dateFinFilter) {
            $query->whereDate('start_date', '<=', $this->dateFinFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('chauffeur', function ($subQ) {
                    $subQ->where('nom', 'like', '%' . $this->search . '%')
                         ->orWhere('prenom', 'like', '%' . $this->search . '%');
                })->orWhereHas('vehicule', function ($subQ) {
                    $subQ->where('immatriculation', 'like', '%' . $this->search . '%')
                         ->orWhere('marque', 'like', '%' . $this->search . '%')
                         ->orWhere('modele', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Tri
        $query->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.admin.taches', [
            'taches' => $query->paginate(10),
            'chauffeurs' => User::where('role', 'chauffeur')->where('admin_id', Auth::user()->user_id)->get(),
            'vehicules' => Vehicule::with(['marque', 'modele'])->where('admin_id', Auth::user()->user_id)->get(),
        ])->layout('layouts.admin');
    }

    public function resetForm()
    {
        $this->tacheId = null;
        $this->chauffeur_id = '';
        $this->vehicule_id = '';
        $this->start_date = '';
        $this->debut_kilometrage = '';
        $this->debut_carburant = '';
        $this->fin_kilometrage = '';
        $this->fin_carburant = '';
        $this->description = '';
        $this->type_tache = 'autre';
        $this->isEdit = false;
        $this->resetErrorBag();
    }

    public function resetFilters()
    {
        $this->statusFilter = '';
        $this->chauffeurFilter = '';
        $this->vehiculeFilter = '';
        $this->validationFilter = '';
        $this->dateDebutFilter = '';
        $this->dateFinFilter = '';
        $this->search = '';
        $this->resetPage();
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

    public function showDetails($id)
    {
        $this->selectedTache = Tache::with(['chauffeur', 'vehicule.marque', 'vehicule.modele', 'photos'])->findOrFail($id);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedTache = null;
    }

    public function exportExcel()
    {
        // Préparer les filtres pour l'export avancé
        $filters = [
            'search' => $this->search,
            'status' => $this->statusFilter,
            'chauffeur_id' => $this->chauffeurFilter,
            'vehicule_id' => $this->vehiculeFilter,
            'validation' => $this->validationFilter,
            'date_debut_debut' => $this->dateDebutFilter,
            'date_debut_fin' => $this->dateFinFilter,
        ];

        return ExportService::exportTachesAvance($filters);
    }

    public function create()
    {
        $this->validate([
            'chauffeur_id' => 'required|exists:users,user_id',
            'vehicule_id' => 'required|exists:vehicules,id',
            'start_date' => 'required|date',
            'debut_kilometrage' => 'nullable|integer|min:0',
            'debut_carburant' => 'nullable|numeric|min:0|max:100',
            'fin_kilometrage' => 'nullable|integer|min:0',
            'fin_carburant' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string|max:1000',
            'type_tache' => 'required|in:maintenance,livraison,inspection,autre',
        ], [
            'chauffeur_id.required' => 'Le chauffeur est obligatoire.',
            'chauffeur_id.exists' => 'Le chauffeur sélectionné n\'existe pas.',
            'vehicule_id.required' => 'Le véhicule est obligatoire.',
            'vehicule_id.exists' => 'Le véhicule sélectionné n\'existe pas.',
            'start_date.required' => 'La date de début est obligatoire.',
            'start_date.date' => 'La date de début doit être une date valide.',
            'debut_kilometrage.integer' => 'Le kilométrage de début doit être un nombre entier.',
            'debut_kilometrage.min' => 'Le kilométrage de début ne peut pas être négatif.',
            'debut_carburant.numeric' => 'Le carburant de début doit être un nombre.',
            'debut_carburant.min' => 'Le carburant de début ne peut pas être négatif.',
            'debut_carburant.max' => 'Le carburant de début ne peut pas dépasser 100%.',
            'fin_kilometrage.integer' => 'Le kilométrage de fin doit être un nombre entier.',
            'fin_kilometrage.min' => 'Le kilométrage de fin ne peut pas être négatif.',
            'fin_carburant.numeric' => 'Le carburant de fin doit être un nombre.',
            'fin_carburant.min' => 'Le carburant de fin ne peut pas être négatif.',
            'fin_carburant.max' => 'Le carburant de fin ne peut pas dépasser 100%.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'type_tache.required' => 'Le type de tâche est obligatoire.',
            'type_tache.in' => 'Le type de tâche sélectionné n\'est pas valide.',
        ]);

        // Vérifier qu'il n'y a pas déjà une tâche en cours pour ce chauffeur
        $existingTache = Tache::where('chauffeur_id', $this->chauffeur_id)
            ->where('status', 'en_cours')
            ->first();

        if ($existingTache) {
            session()->flash('error', 'Ce chauffeur a déjà une tâche en cours.');
            return;
        }

        // Vérifier qu'il n'y a pas déjà une tâche en cours pour ce véhicule
        $existingVehiculeTache = Tache::where('vehicule_id', $this->vehicule_id)
            ->where('status', 'en_cours')
            ->first();

        if ($existingVehiculeTache) {
            session()->flash('error', 'Ce véhicule a déjà une tâche en cours.');
            return;
        }

        Tache::create([
            'chauffeur_id' => $this->chauffeur_id,
            'vehicule_id' => $this->vehicule_id,
            'start_date' => $this->start_date,
            'debut_kilometrage' => $this->debut_kilometrage,
            'debut_carburant' => $this->debut_carburant,
            'fin_kilometrage' => $this->fin_kilometrage,
            'fin_carburant' => $this->fin_carburant,
            'description' => $this->description,
            'type_tache' => $this->type_tache,
            'status' => 'en_attente',
            'is_validated' => false,
        ]);

        session()->flash('success', 'Tâche créée avec succès.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $tache = Tache::findOrFail($id);
        $this->tacheId = $tache->id;
        $this->chauffeur_id = $tache->chauffeur_id;
        $this->vehicule_id = $tache->vehicule_id;
        $this->start_date = $tache->start_date->format('Y-m-d\TH:i');
        $this->debut_kilometrage = $tache->debut_kilometrage;
        $this->debut_carburant = $tache->debut_carburant;
        $this->fin_kilometrage = $tache->fin_kilometrage;
        $this->fin_carburant = $tache->fin_carburant;
        $this->description = $tache->description;
        $this->type_tache = $tache->type_tache;
        $this->isEdit = true;

        // Déclencher la synchronisation des Select2 après un délai
        $this->dispatch('sync-select2-values');
    }

    public function update()
    {
        $tache = Tache::findOrFail($this->tacheId);

        $this->validate([
            'chauffeur_id' => 'required|exists:users,user_id',
            'vehicule_id' => 'required|exists:vehicules,id',
            'start_date' => 'required|date',
            'debut_kilometrage' => 'nullable|integer|min:0',
            'debut_carburant' => 'nullable|numeric|min:0|max:100',
            'fin_kilometrage' => 'nullable|integer|min:0',
            'fin_carburant' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string|max:1000',
            'type_tache' => 'required|in:maintenance,livraison,inspection,autre',
        ], [
            'chauffeur_id.required' => 'Le chauffeur est obligatoire.',
            'chauffeur_id.exists' => 'Le chauffeur sélectionné n\'existe pas.',
            'vehicule_id.required' => 'Le véhicule est obligatoire.',
            'vehicule_id.exists' => 'Le véhicule sélectionné n\'existe pas.',
            'start_date.required' => 'La date de début est obligatoire.',
            'start_date.date' => 'La date de début doit être une date valide.',
            'debut_kilometrage.integer' => 'Le kilométrage de début doit être un nombre entier.',
            'debut_kilometrage.min' => 'Le kilométrage de début ne peut pas être négatif.',
            'debut_carburant.numeric' => 'Le carburant de début doit être un nombre.',
            'debut_carburant.min' => 'Le carburant de début ne peut pas être négatif.',
            'debut_carburant.max' => 'Le carburant de début ne peut pas dépasser 100%.',
            'fin_kilometrage.integer' => 'Le kilométrage de fin doit être un nombre entier.',
            'fin_kilometrage.min' => 'Le kilométrage de fin ne peut pas être négatif.',
            'fin_carburant.numeric' => 'Le carburant de fin doit être un nombre.',
            'fin_carburant.min' => 'Le carburant de fin ne peut pas être négatif.',
            'fin_carburant.max' => 'Le carburant de fin ne peut pas dépasser 100%.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'type_tache.required' => 'Le type de tâche est obligatoire.',
            'type_tache.in' => 'Le type de tâche sélectionné n\'est pas valide.',
        ]);

        $tache->update([
            'chauffeur_id' => $this->chauffeur_id,
            'vehicule_id' => $this->vehicule_id,
            'start_date' => $this->start_date,
            'debut_kilometrage' => $this->debut_kilometrage,
            'debut_carburant' => $this->debut_carburant,
            'fin_kilometrage' => $this->fin_kilometrage,
            'fin_carburant' => $this->fin_carburant,
            'description' => $this->description,
            'type_tache' => $this->type_tache,
        ]);

        session()->flash('success', 'Tâche mise à jour.');
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $this->tacheToDelete = (int) $id;
        $this->showDeleteModal = true;
    }

    public function delete($id)
    {
        Tache::findOrFail($id)->delete();
        session()->flash('success', 'Tâche supprimée.');
        $this->showDeleteModal = false;
        $this->tacheToDelete = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->tacheToDelete = null;
    }

    public function valider($id)
    {
        $tache = Tache::findOrFail($id);
        $tache->update(['is_validated' => true]);

        // Envoyer une notification FCM au chauffeur
        $chauffeur = $tache->chauffeur;
        if ($chauffeur && $chauffeur->fcm_token) {
            app(FcmService::class)->sendToToken(
                $chauffeur->fcm_token,
                'Tâche validée',
                'Votre tâche a été validée par l\'administrateur.',
                ['type' => 'tache', 'tache_id' => $tache->id]
            );
        }
        session()->flash('success', 'Tâche validée.');
    }
}

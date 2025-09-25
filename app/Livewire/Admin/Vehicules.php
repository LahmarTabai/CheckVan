<?php

namespace App\Livewire\Admin;

use App\Models\Vehicule;
use App\Models\Marque;
use App\Models\Modele;
use App\Models\VehiculePhoto;
use App\Services\VehiculeApiService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\ExportService;

class Vehicules extends Component
{
    use WithPagination, WithFileUploads;

    // Propriétés du formulaire
    public $marque_id, $modele_id, $immatriculation, $vehiculeId;
    public $type = 'propriete', $annee, $couleur, $kilometrage;
    public $statut = 'disponible', $description, $prix_achat, $date_achat;
    public $prix_location_jour, $date_location;
    public $numero_chassis, $numero_moteur, $derniere_revision, $prochaine_revision;

    // Propriété pour savoir si on est en mode édition
    public $isEdit = false;

    // Photos multiples
    public $photos = [];

    // Modal suppression
    public $showDeleteModal = false;
    public $vehiculeToDelete = null;

    // Interface
    public $search = '';
    public $filterType = '';
    public $filterStatut = '';
    public $filterMarque = '';
    public $filterModele = '';
    public $filterAnnee = '';
    public $filterCouleur = '';

    // Tri
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Données pour les listes déroulantes
    public $marques = [];
    public $formModeles = [];
    public $filterModeles = [];
    public $couleurs = [
        'Blanc', 'Noir', 'Gris', 'Rouge', 'Bleu', 'Vert', 'Jaune', 'Orange',
        'Marron', 'Beige', 'Argent', 'Or', 'Violet', 'Rose', 'Turquoise'
    ];

    protected function rules()
    {
        $rules = [
            'marque_id' => 'required|exists:marques,id',
            'modele_id' => 'required|exists:modeles,id',
            'immatriculation' => 'required|string|max:255|unique:vehicules,immatriculation' . ($this->isEdit ? ',' . $this->vehiculeId : ''),
            'type' => 'required|in:location,propriete',
            'annee' => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'couleur' => 'nullable|string|max:50',
            'kilometrage' => 'nullable|integer|min:0',
            'statut' => 'required|in:disponible,en_mission,en_maintenance,hors_service',
            'description' => 'nullable|string|max:1000',
            'numero_chassis' => 'nullable|string|max:50',
            'numero_moteur' => 'nullable|string|max:50',
            'derniere_revision' => 'nullable|date',
            'prochaine_revision' => 'nullable|date',
            'photos.*' => 'nullable|image|max:8192', // 8MB max par photo (limite PHP)
        ];

        // Règles conditionnelles selon le type
        if ($this->type === 'propriete') {
            $rules['prix_achat'] = 'nullable|numeric|min:0';
            $rules['date_achat'] = 'nullable|date';
        } else if ($this->type === 'location') {
            $rules['prix_location_jour'] = 'required|numeric|min:0';
            $rules['date_location'] = 'required|date';
        }

        return $rules;
    }

    protected $messages = [
        'prix_location_jour.required' => 'Le prix de location par jour est obligatoire pour les véhicules en location.',
        'date_location.required' => 'La date de location est obligatoire pour les véhicules en location.',
        'prix_achat.required_if' => 'Le prix d\'achat est obligatoire pour les véhicules en propriété.',
        'date_achat.required_if' => 'La date d\'achat est obligatoire pour les véhicules en propriété.',
    ];

    public function updatedType()
    {
        \Log::info('=== updatedType appelé ===', ['type' => $this->type]);

        // Réinitialiser complètement les champs selon le type
        if ($this->type === 'location') {
            $this->prix_achat = null;
            $this->date_achat = null;
            \Log::info('Type location - champs achat vidés');
        } else { // propriete
            $this->prix_location_jour = null;
            $this->date_location = null;
            \Log::info('Type propriete - champs location vidés');
        }

        // Réinitialiser les erreurs de validation
        $this->resetErrorBag([
            'prix_achat', 'date_achat', 'prix_location_jour', 'date_location'
        ]);
    }

    public function updateTypeFields()
    {
        \Log::info('=== updateTypeFields appelé ===', ['type' => $this->type]);

        // Cette méthode sera appelée par wire:change
        $this->resetErrorBag([
            'prix_achat', 'date_achat', 'prix_location_jour', 'date_location'
        ]);

        // Forcer le rafraîchissement
        $this->dispatch('type-changed');
    }

    protected $listeners = [
        'vehicule-added' => '$refresh',
        'vehicule-updated' => '$refresh',
        'marque-changed' => 'handleMarqueChanged',
        'vehicule-deleted' => '$refresh',
    ];

    public function mount()
    {
        $this->loadMarques();
        $this->resetForm();
    }

    public function loadMarques()
    {
        \Log::info('=== loadMarques appelé ===');
        // Utiliser directement la base de données pour éviter les problèmes de conversion
        $this->marques = Marque::where('is_active', true)
            ->orderBy('nom')
            ->get();
    }

    public function updatedMarqueId($value = null)
    {
        \Log::info('=== updatedMarqueId DÉBUT ===', ['marque_id' => $this->marque_id]);

        $this->modele_id = null;

        if (!$this->marque_id) {
            $this->formModeles = collect();
            \Log::info('Marque ID vide, modèles (form) vidés');
            return;
        }

        try {
            app(\App\Services\VehiculeApiService::class)
                ->syncModelesFromApi($this->marque_id);
        } catch (\Exception $e) {
            \Log::error('Erreur sync API modèles: '.$e->getMessage());
        }

        $this->formModeles = \App\Models\Modele::where('marque_id', $this->marque_id)
            ->where('is_active', true)
            ->orderBy('nom')
            ->get(['id','nom']);

        \Log::info('Modèles (form) chargés depuis BDD', ['count' => $this->formModeles->count()]);

        // Déclencher une réinitialisation ciblée de Select2
        $this->dispatch('refresh-select2');
    }

    public function handleMarqueChanged($marqueId)
    {
        \Log::info('=== handleMarqueChanged appelé ===', ['marqueId' => $marqueId]);
        $this->marque_id = $marqueId;
        $this->updatedMarqueId();
    }

    public function updatedFilterMarque()
    {
        if ($this->filterMarque) {
            $this->filterModeles = Modele::where('marque_id', $this->filterMarque)
                ->where('is_active', true)
                ->orderBy('nom')
                ->get(['id','nom']);
        } else {
            $this->filterModeles = collect();
        }
        $this->filterModele = null;
    }


    public function render()
    {

        $query = Vehicule::with(['marque', 'modele', 'photos'])
            ->where('admin_id', Auth::user()->user_id);

        // Filtres
        if ($this->search) {
            \Log::info('Recherche effectuée: ' . $this->search);
            $query->where(function ($q) {
                $q->where('immatriculation', 'like', '%' . $this->search . '%')
                  ->orWhereHas('marque', function ($q) {
                      $q->where('nom', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('modele', function ($q) {
                      $q->where('nom', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterStatut) {
            $query->where('statut', $this->filterStatut);
        }

        if ($this->filterMarque) {
            $query->where('marque_id', $this->filterMarque);
        }

        if ($this->filterModele) {
            $query->where('modele_id', $this->filterModele);
        }

        if ($this->filterAnnee) {
            $query->where('annee', $this->filterAnnee);
        }

        if ($this->filterCouleur) {
            $query->where('couleur', $this->filterCouleur);
        }

        // Tri
        if ($this->sortField === 'marque') {
            $query->join('marques', 'vehicules.marque_id', '=', 'marques.id')
                  ->orderBy('marques.nom', $this->sortDirection)
                  ->select('vehicules.*');
        } elseif ($this->sortField === 'modele') {
            $query->join('modeles', 'vehicules.modele_id', '=', 'modeles.id')
                  ->orderBy('modeles.nom', $this->sortDirection)
                  ->select('vehicules.*');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $vehicules = $query->paginate(10);

        return view('livewire.admin.vehicules', compact('vehicules'))->layout('layouts.admin');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterType = '';
        $this->filterStatut = '';
        $this->filterMarque = '';
        $this->filterModele = '';
        $this->filterAnnee = '';
        $this->filterCouleur = '';
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
            'search' => $this->search,
            'type' => $this->filterType,
            'statut' => $this->filterStatut,
            'marque_id' => $this->filterMarque,
            'modele_id' => $this->filterModele,
            'annee' => $this->filterAnnee,
            'couleur' => $this->filterCouleur,
        ];

        return ExportService::exportVehicules($filters);
    }

    // Propriétés pour la modal de détails
    public $showDetailsModal = false;
    public $selectedVehicule = null;

    public function showDetails($id)
    {
        $this->selectedVehicule = Vehicule::with(['marque', 'modele', 'photos'])->findOrFail($id);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedVehicule = null;
    }

    public function resetForm()
    {
        $this->marque_id = null;
        $this->modele_id = null;
        $this->formModeles = [];
        $this->immatriculation = '';
        $this->type = 'propriete';
        $this->annee = null;
        $this->couleur = null;
        $this->kilometrage = null;
        $this->statut = 'disponible';
        $this->description = null;
        $this->prix_achat = null;
        $this->date_achat = null;
        $this->prix_location_jour = null;
        $this->date_location = null;
        $this->numero_chassis = null;
        $this->numero_moteur = null;
        $this->derniere_revision = null;
        $this->prochaine_revision = null;
        $this->photos = [];
        $this->vehiculeId = null;
        $this->isEdit = false;
        $this->modeles = [];
        $this->resetErrorBag();
    }

    public function store()
    {
        try {
            $this->validate();

            $data = [
                'admin_id' => Auth::user()->user_id,
                'marque_id' => $this->marque_id,
                'modele_id' => $this->modele_id,
                'immatriculation' => $this->immatriculation,
                'type' => $this->type,
                'annee' => $this->annee,
                'couleur' => $this->couleur,
                'kilometrage' => $this->kilometrage,
                'statut' => $this->statut,
                'description' => $this->description,
                'numero_chassis' => $this->numero_chassis,
                'numero_moteur' => $this->numero_moteur,
                'derniere_revision' => $this->derniere_revision,
                'prochaine_revision' => $this->prochaine_revision,
            ];

            // Champs spécifiques au type
            if ($this->type === 'propriete') {
                $data['prix_achat'] = $this->prix_achat;
                $data['date_achat'] = $this->date_achat;
                $data['prix_location_jour'] = null;
                $data['date_location'] = null;
            } else {
                $data['prix_location_jour'] = $this->prix_location_jour;
                $data['date_location'] = $this->date_location;
                $data['prix_achat'] = null;
                $data['date_achat'] = null;
            }

            $vehicule = Vehicule::create($data);

            // Sauvegarder les photos
            $this->savePhotos($vehicule);

            session()->flash('success', 'Véhicule ajouté avec succès.');
            $this->resetForm();
            $this->dispatch('vehicule-added');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de l\'ajout du véhicule: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $vehicule = Vehicule::with(['marque', 'modele', 'photos'])->findOrFail($id);

        $this->vehiculeId = $vehicule->id;
        $this->marque_id = $vehicule->marque_id;
        $this->modele_id = $vehicule->modele_id;
        $this->immatriculation = $vehicule->immatriculation;
        $this->type = $vehicule->type;
        $this->annee = $vehicule->annee;
        $this->couleur = $vehicule->couleur;
        $this->kilometrage = $vehicule->kilometrage;
        $this->statut = $vehicule->statut;
        $this->description = $vehicule->description;
        $this->prix_achat = $vehicule->prix_achat;
        $this->date_achat = $vehicule->date_achat;
        $this->prix_location_jour = $vehicule->prix_location_jour;
        $this->date_location = $vehicule->date_location;
        $this->numero_chassis = $vehicule->numero_chassis;
        $this->numero_moteur = $vehicule->numero_moteur;
        $this->derniere_revision = $vehicule->derniere_revision;
        $this->prochaine_revision = $vehicule->prochaine_revision;

        // Charger les modèles pour la marque sélectionnée
        $this->updatedMarqueId();

        $this->isEdit = true;
    }

    public function update()
    {
        $vehicule = Vehicule::findOrFail($this->vehiculeId);

        $this->validate();

        $data = [
            'marque_id' => $this->marque_id,
            'modele_id' => $this->modele_id,
            'immatriculation' => $this->immatriculation,
            'type' => $this->type,
            'annee' => $this->annee,
            'couleur' => $this->couleur,
            'kilometrage' => $this->kilometrage,
            'statut' => $this->statut,
            'description' => $this->description,
            'numero_chassis' => $this->numero_chassis,
            'numero_moteur' => $this->numero_moteur,
            'derniere_revision' => $this->derniere_revision,
            'prochaine_revision' => $this->prochaine_revision,
        ];

        // Champs spécifiques au type
        if ($this->type === 'propriete') {
            $data['prix_achat'] = $this->prix_achat;
            $data['date_achat'] = $this->date_achat;
            $data['prix_location_jour'] = null;
            $data['date_location'] = null;
        } else {
            $data['prix_location_jour'] = $this->prix_location_jour;
            $data['date_location'] = $this->date_location;
            $data['prix_achat'] = null;
            $data['date_achat'] = null;
        }

        $vehicule->update($data);

        // Sauvegarder les nouvelles photos
        if (!empty($this->photos)) {
            $this->savePhotos($vehicule);
        }

        session()->flash('success', 'Véhicule modifié avec succès.');
        $this->resetForm();
        $this->dispatch('vehicule-updated');
    }

    public function confirmDelete($id)
    {
        $this->vehiculeToDelete = (int) $id;
        $this->showDeleteModal = true;
    }

    public function destroy($id)
    {
        $vehicule = Vehicule::findOrFail($id);

        // Supprimer les photos
        foreach ($vehicule->photos as $photo) {
            Storage::disk('public')->delete($photo->chemin);
        }

        $vehicule->delete();
        session()->flash('success', 'Véhicule supprimé avec succès.');
        $this->dispatch('vehicule-deleted');
        $this->showDeleteModal = false;
        $this->vehiculeToDelete = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->vehiculeToDelete = null;
    }

    public function deletePhoto($photoId)
    {
        $photo = VehiculePhoto::findOrFail($photoId);
        Storage::disk('public')->delete($photo->chemin);
        $photo->delete();
        session()->flash('success', 'Photo supprimée avec succès.');
    }

    private function savePhotos($vehicule)
    {
        if (!empty($this->photos)) {
            // Récupérer le dernier ordre depuis la table directement
            $ordre = VehiculePhoto::where('vehicule_id', $vehicule->id)->max('ordre') ?? 0;

            foreach ($this->photos as $photo) {
                $ordre++;
                $path = $photo->store('vehicules', 'public');

                VehiculePhoto::create([
                    'vehicule_id' => $vehicule->id,
                    'chemin' => $path,
                    'nom_fichier' => $photo->getClientOriginalName(),
                    'extension' => $photo->getClientOriginalExtension(),
                    'taille' => $photo->getSize(),
                    'ordre' => $ordre,
                    'type' => 'exterieur'
                ]);
            }
        }
    }
}

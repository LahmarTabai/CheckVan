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

class Vehicules extends Component
{
    use WithPagination, WithFileUploads;

    // Propriétés du formulaire
    public $marque_id, $modele_id, $immatriculation, $vehiculeId;
    public $type = 'propriete', $annee, $couleur, $kilometrage;
    public $statut = 'disponible', $description, $prix_achat, $date_achat;
    public $numero_chassis, $numero_moteur, $derniere_revision, $prochaine_revision;

    // Photos multiples
    public $photos = [];

    // Interface
    public $isEdit = false;
    public $search = '';
    public $filterType = '';
    public $filterStatut = '';
    public $filterMarque = '';
    public $filterModele = '';
    public $filterAnnee = '';
    public $filterCouleur = '';

    // Données pour les listes déroulantes
    public $marques = [];
    public $modeles = [];
    public $couleurs = [
        'Blanc', 'Noir', 'Gris', 'Rouge', 'Bleu', 'Vert', 'Jaune', 'Orange',
        'Marron', 'Beige', 'Argent', 'Or', 'Violet', 'Rose', 'Turquoise'
    ];

    protected function rules()
    {
        return [
            'marque_id' => 'required|exists:marques,id',
            'modele_id' => 'required|exists:modeles,id',
            'immatriculation' => 'required|string|max:255|unique:vehicules,immatriculation',
            'type' => 'required|in:location,propriete',
            'annee' => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'couleur' => 'nullable|string|max:50',
            'kilometrage' => 'nullable|integer|min:0',
            'statut' => 'required|in:disponible,en_mission,en_maintenance,hors_service',
            'description' => 'nullable|string|max:1000',
            'prix_achat' => 'nullable|numeric|min:0',
            'date_achat' => 'nullable|date',
            'numero_chassis' => 'nullable|string|max:50',
            'numero_moteur' => 'nullable|string|max:50',
            'derniere_revision' => 'nullable|date',
            'prochaine_revision' => 'nullable|date',
            'photos.*' => 'nullable|image|max:8192', // 8MB max par photo (limite PHP)
        ];
    }

    protected $listeners = [
        'vehicule-added' => '$refresh',
        'vehicule-updated' => '$refresh',
        'vehicule-deleted' => '$refresh',
    ];

    public function mount()
    {
        $this->loadMarques();
        $this->resetForm();
    }

    public function loadMarques()
    {
        // Utiliser directement la base de données pour éviter les problèmes de conversion
        $this->marques = Marque::where('is_active', true)
            ->orderBy('nom')
            ->get();
    }

    public function updatedMarqueId()
    {
        if ($this->marque_id) {
            // Charger les modèles de la marque sélectionnée
            $this->modeles = Modele::where('marque_id', $this->marque_id)
                ->where('is_active', true)
                ->orderBy('nom')
                ->get();
        } else {
            $this->modeles = collect();
        }
        $this->modele_id = null;
    }

    public function updatedFilterMarque()
    {
        if ($this->filterMarque) {
            // Charger les modèles de la marque sélectionnée pour les filtres
            $this->modeles = Modele::where('marque_id', $this->filterMarque)
                ->where('is_active', true)
                ->orderBy('nom')
                ->get();
        } else {
            $this->modeles = collect();
        }
        $this->filterModele = null;
    }

    public function updatedType()
    {
        // Réinitialiser les champs prix et date quand le type change
        $this->prix_achat = null;
        $this->date_achat = null;
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

        $vehicules = $query->latest()->paginate(10);

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
        $this->immatriculation = '';
        $this->type = 'propriete';
        $this->annee = null;
        $this->couleur = '';
        $this->kilometrage = null;
        $this->statut = 'disponible';
        $this->description = '';
        $this->prix_achat = null;
        $this->date_achat = null;
        $this->numero_chassis = '';
        $this->numero_moteur = '';
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
        $this->validate();

        $vehicule = Vehicule::create([
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
            'prix_achat' => $this->prix_achat,
            'date_achat' => $this->date_achat,
            'numero_chassis' => $this->numero_chassis,
            'numero_moteur' => $this->numero_moteur,
            'derniere_revision' => $this->derniere_revision,
            'prochaine_revision' => $this->prochaine_revision,
        ]);

        // Sauvegarder les photos
        $this->savePhotos($vehicule);

        session()->flash('success', 'Véhicule ajouté avec succès.');
        $this->resetForm();
        $this->dispatch('vehicule-added');
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

        $this->validate([
            'marque_id' => 'required|exists:marques,id',
            'modele_id' => 'required|exists:modeles,id',
            'immatriculation' => 'required|string|max:255|unique:vehicules,immatriculation,' . $vehicule->id,
            'type' => 'required|in:location,propriete',
            'annee' => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'couleur' => 'nullable|string|max:50',
            'kilometrage' => 'nullable|integer|min:0',
            'statut' => 'required|in:disponible,en_mission,en_maintenance,hors_service',
            'description' => 'nullable|string|max:1000',
            'prix_achat' => 'nullable|numeric|min:0',
            'date_achat' => 'nullable|date',
            'numero_chassis' => 'nullable|string|max:50',
            'numero_moteur' => 'nullable|string|max:50',
            'derniere_revision' => 'nullable|date',
            'prochaine_revision' => 'nullable|date',
            'photos.*' => 'nullable|image|max:8192', // 8MB max par photo (limite PHP)
        ]);

        $vehicule->update([
            'marque_id' => $this->marque_id,
            'modele_id' => $this->modele_id,
            'immatriculation' => $this->immatriculation,
            'type' => $this->type,
            'annee' => $this->annee,
            'couleur' => $this->couleur,
            'kilometrage' => $this->kilometrage,
            'statut' => $this->statut,
            'description' => $this->description,
            'prix_achat' => $this->prix_achat,
            'date_achat' => $this->date_achat,
            'numero_chassis' => $this->numero_chassis,
            'numero_moteur' => $this->numero_moteur,
            'derniere_revision' => $this->derniere_revision,
            'prochaine_revision' => $this->prochaine_revision,
        ]);

        // Sauvegarder les nouvelles photos
        if (!empty($this->photos)) {
            $this->savePhotos($vehicule);
        }

        session()->flash('success', 'Véhicule modifié avec succès.');
        $this->resetForm();
        $this->dispatch('vehicule-updated');
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

<?php

namespace App\Livewire\Chauffeur;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Affectation;
use App\Models\Vehicule;
use App\Models\Photo;
use App\Models\Dommage;
use App\Services\TacheNotificationService;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PriseEnCharge extends Component
{
    use WithFileUploads;

    // Propriétés pour la prise en charge
    public $vehicule_id;
    public $plaque_immatriculation;
    public $latitude;
    public $longitude;
    public $photos_faces = [];
    public $photo_compteur;
    public $photo_carburant;
    public $kilometrage;
    public $niveau_carburant;
    public $message;

    // Propriétés pour la restitution
    public $photos_fin = [];
    public $photo_compteur_fin;
    public $photo_carburant_fin;
    public $kilometrage_fin;
    public $niveau_carburant_fin;
    public $latitude_fin;
    public $longitude_fin;

    // Propriétés pour les dommages
    public $showDommageModal = false;
    public $dommage_type = '';
    public $dommage_severite = 'mineur';
    public $dommage_description = '';
    public $dommage_photo;
    public $dommage_coord_x;
    public $dommage_coord_y;
    public $dommage_coord_z = 0;

    public function mount()
    {
        $this->resetForm();
    }

    public function render()
    {
        $adminId = Auth::user()->admin_id;
        $vehicules = Vehicule::with(['marque', 'modele'])
            ->where('admin_id', $adminId)
            ->whereDoesntHave('affectations', function ($query) {
                $query->where('status', 'en_cours');
            })
            ->get();

        $affectation = Affectation::where('chauffeur_id', Auth::user()->user_id)
                            ->where('status', 'en_cours')
                            ->with(['vehicule.marque', 'vehicule.modele', 'photos', 'dommages'])
                            ->first();

        return view('livewire.chauffeur.prise-en-charge', compact('vehicules', 'affectation'))->layout('layouts.chauffeur');
    }

    public function resetForm()
    {
        $this->vehicule_id = '';
        $this->plaque_immatriculation = '';
        $this->latitude = '';
        $this->longitude = '';
        $this->photos_faces = [];
        $this->photo_compteur = null;
        $this->photo_carburant = null;
        $this->kilometrage = '';
        $this->niveau_carburant = '';
        $this->message = '';
        $this->resetErrorBag();
    }

    public function prendreEnCharge()
    {
        // Vérifier que le dossier de stockage existe
        $storagePath = storage_path('app/public/photos/affectations');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        // Vérifier que le lien symbolique public existe
        $publicPath = public_path('storage');
        if (!file_exists($publicPath)) {
            \Artisan::call('storage:link');
        }

        $this->validate([
            'vehicule_id' => 'required|exists:vehicules,id',
            'plaque_immatriculation' => 'required|string|max:20',
            'photos_faces' => 'required|array|min:2|max:6',
            'photos_faces.*' => 'image|max:2048',
            'photo_compteur' => 'required|image|max:2048',
            'photo_carburant' => 'required|image|max:2048',
            'kilometrage' => 'required|integer|min:0',
            'niveau_carburant' => 'required|numeric|min:0|max:100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ], [
            'vehicule_id.required' => 'Le véhicule est obligatoire.',
            'plaque_immatriculation.required' => 'La plaque d\'immatriculation est obligatoire.',
            'photos_faces.required' => 'Vous devez prendre au moins 2 photos des faces du véhicule.',
            'photos_faces.min' => 'Vous devez prendre au moins 2 photos des faces du véhicule.',
            'photo_compteur.required' => 'La photo du compteur est obligatoire.',
            'photo_carburant.required' => 'La photo du niveau de carburant est obligatoire.',
            'kilometrage.required' => 'Le kilométrage est obligatoire.',
            'niveau_carburant.required' => 'Le niveau de carburant est obligatoire.',
            'latitude.required' => 'La géolocalisation est obligatoire.',
            'longitude.required' => 'La géolocalisation est obligatoire.',
        ]);

        // Vérifier qu'il n'y a pas déjà une affectation en cours
        $existingAffectation = Affectation::where('chauffeur_id', Auth::user()->user_id)
            ->where('status', 'en_cours')
            ->first();

        if ($existingAffectation) {
            session()->flash('error', 'Vous avez déjà un véhicule pris en charge.');
            return;
        }

        // Vérifier que le véhicule n'est pas déjà pris en charge
        $existingVehiculeAffectation = Affectation::where('vehicule_id', $this->vehicule_id)
            ->where('status', 'en_cours')
            ->first();

        if ($existingVehiculeAffectation) {
            session()->flash('error', 'Ce véhicule est déjà pris en charge par un autre chauffeur.');
            return;
        }

        // Créer l'affectation
        $affectation = Affectation::create([
            'chauffeur_id' => Auth::user()->user_id,
            'vehicule_id' => $this->vehicule_id,
            'status' => 'en_cours',
            'date_debut' => now()->toDateString(),
            'description' => 'Prise en charge du véhicule',
        ]);

        // Enregistrer les photos des faces
        foreach ($this->photos_faces as $index => $photo) {
            $path = $photo->store('photos/affectations', 'public');
            Photo::create([
                'affectation_id' => $affectation->id,
                'type' => 'exterieur',
                'path' => $path,
            ]);
        }

        // Enregistrer la photo du compteur
        $compteurPath = $this->photo_compteur->store('photos/affectations', 'public');
        Photo::create([
            'affectation_id' => $affectation->id,
            'type' => 'debut_kilometrage',
            'path' => $compteurPath,
        ]);

        // Enregistrer la photo du carburant
        $carburantPath = $this->photo_carburant->store('photos/affectations', 'public');
        Photo::create([
            'affectation_id' => $affectation->id,
            'type' => 'debut_carburant',
            'path' => $carburantPath,
        ]);

        // Mettre à jour l'affectation avec les données
        $affectation->update([
            'latitude_debut' => $this->latitude,
            'longitude_debut' => $this->longitude,
            'kilometrage_debut' => $this->kilometrage,
            'niveau_carburant_debut' => $this->niveau_carburant,
        ]);

        // Envoyer notification à l'admin
        app(TacheNotificationService::class)->notifyAffectationCreated($affectation);

        session()->flash('success', 'Véhicule pris en charge avec succès. Vous pouvez maintenant demander des tâches.');
        $this->resetForm();
    }

    public function rendreVehicule()
    {
        // Vérifier que le dossier de stockage existe
        $storagePath = storage_path('app/public/photos/affectations');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        // Vérifier que le lien symbolique public existe
        $publicPath = public_path('storage');
        if (!file_exists($publicPath)) {
            \Artisan::call('storage:link');
        }

        $this->validate([
            'photos_fin' => 'required|array|min:2|max:6',
            'photos_fin.*' => 'image|max:2048',
            'photo_compteur_fin' => 'required|image|max:2048',
            'photo_carburant_fin' => 'required|image|max:2048',
            'kilometrage_fin' => 'required|integer|min:0',
            'niveau_carburant_fin' => 'required|numeric|min:0|max:100',
            'latitude_fin' => 'required|numeric',
            'longitude_fin' => 'required|numeric',
        ], [
            'photos_fin.required' => 'Vous devez prendre au moins 2 photos des faces du véhicule.',
            'photos_fin.min' => 'Vous devez prendre au moins 2 photos des faces du véhicule.',
            'photo_compteur_fin.required' => 'La photo du compteur est obligatoire.',
            'photo_carburant_fin.required' => 'La photo du niveau de carburant est obligatoire.',
            'kilometrage_fin.required' => 'Le kilométrage est obligatoire.',
            'niveau_carburant_fin.required' => 'Le niveau de carburant est obligatoire.',
            'latitude_fin.required' => 'La géolocalisation est obligatoire.',
            'longitude_fin.required' => 'La géolocalisation est obligatoire.',
        ]);

        $affectation = Affectation::where('chauffeur_id', Auth::user()->user_id)
                        ->where('status', 'en_cours')
                        ->first();

        if (!$affectation) {
            session()->flash('error', 'Aucun véhicule pris en charge.');
            return;
        }

        // Enregistrer les photos de fin
        foreach ($this->photos_fin as $index => $photo) {
            $path = $photo->store('photos/affectations', 'public');
            Photo::create([
                'affectation_id' => $affectation->id,
                'type' => 'exterieur',
                'path' => $path,
            ]);
        }

        // Enregistrer la photo du compteur de fin
        if ($this->photo_compteur_fin) {
            try {
                $compteurFinPath = $this->photo_compteur_fin->store('photos/affectations', 'public');
                Photo::create([
                    'affectation_id' => $affectation->id,
                    'type' => 'fin_kilometrage',
                    'path' => $compteurFinPath,
                ]);
            } catch (\Exception $e) {
                session()->flash('error', 'Erreur lors de l\'upload de la photo du compteur : ' . $e->getMessage());
                return;
            }
        }

        // Enregistrer la photo du carburant de fin
        if ($this->photo_carburant_fin) {
            try {
                $carburantFinPath = $this->photo_carburant_fin->store('photos/affectations', 'public');
                Photo::create([
                    'affectation_id' => $affectation->id,
                    'type' => 'fin_carburant',
                    'path' => $carburantFinPath,
                ]);
            } catch (\Exception $e) {
                session()->flash('error', 'Erreur lors de l\'upload de la photo du carburant : ' . $e->getMessage());
                return;
            }
        }

        // Mettre à jour l'affectation
        $affectation->update([
            'status' => 'terminée',
            'date_fin' => now()->toDateString(),
            'latitude_fin' => $this->latitude_fin,
            'longitude_fin' => $this->longitude_fin,
            'kilometrage_fin' => $this->kilometrage_fin,
            'niveau_carburant_fin' => $this->niveau_carburant_fin,
        ]);

        // Envoyer notification à l'admin
        app(TacheNotificationService::class)->notifyAffectationTerminated($affectation);

        session()->flash('success', 'Véhicule rendu avec succès.');
        $this->resetForm();
    }

    public function showDommageModal()
    {
        $this->resetDommageForm();
        $this->showDommageModal = true;
    }

    public function closeDommageModal()
    {
        $this->showDommageModal = false;
        $this->resetDommageForm();
    }

    public function resetDommageForm()
    {
        $this->dommage_type = '';
        $this->dommage_severite = 'mineur';
        $this->dommage_description = '';
        $this->dommage_photo = null;
        $this->dommage_coord_x = '';
        $this->dommage_coord_y = '';
        $this->dommage_coord_z = 0;
        $this->resetErrorBag();
    }

    public function ajouterDommage()
    {
        $this->validate([
            'dommage_type' => 'required|string|max:50',
            'dommage_severite' => 'required|in:mineur,moyen,majeur',
            'dommage_description' => 'required|string|max:500',
            'dommage_photo' => 'required|image|max:2048',
            'dommage_coord_x' => 'required|numeric|between:0,1',
            'dommage_coord_y' => 'required|numeric|between:0,1',
        ], [
            'dommage_type.required' => 'Le type de dommage est obligatoire.',
            'dommage_severite.required' => 'La sévérité est obligatoire.',
            'dommage_description.required' => 'La description est obligatoire.',
            'dommage_photo.required' => 'La photo du dommage est obligatoire.',
            'dommage_coord_x.required' => 'La position X est obligatoire.',
            'dommage_coord_y.required' => 'La position Y est obligatoire.',
        ]);

        $affectation = Affectation::where('chauffeur_id', Auth::user()->user_id)
                        ->where('status', 'en_cours')
                        ->first();

        if (!$affectation) {
            session()->flash('error', 'Aucun véhicule pris en charge.');
            return;
        }

        // Enregistrer la photo du dommage
        $dommagePath = $this->dommage_photo->store('photos/dommages', 'public');

        // Créer le dommage
        Dommage::create([
            'affectation_id' => $affectation->id,
            'chauffeur_id' => Auth::user()->user_id,
            'coord_x' => $this->dommage_coord_x,
            'coord_y' => $this->dommage_coord_y,
            'coord_z' => $this->dommage_coord_z,
            'type' => $this->dommage_type,
            'severite' => $this->dommage_severite,
            'description' => $this->dommage_description,
            'photo_path' => $dommagePath,
            'reparé' => false,
        ]);

        // Envoyer notification à l'admin
        app(TacheNotificationService::class)->notifyDommageAdded($affectation);

        session()->flash('success', 'Dommage signalé avec succès.');
        $this->closeDommageModal();
    }

    public function getCurrentLocation()
    {
        // Cette méthode sera appelée depuis JavaScript
        // La géolocalisation sera gérée côté client
    }
}

<?php

namespace App\Livewire\Chauffeur;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tache;
use App\Models\Photo;
use App\Models\Affectation;
use App\Models\Vehicule;
use App\Services\TacheNotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Taches extends Component
{
    use WithFileUploads;

    // Propriétés pour les modales
    public $showStartModal = false;
    public $showEndModal = false;
    public $selectedTache = null;

    // Propriétés pour démarrer une tâche
    public $debut_kilometrage;
    public $debut_carburant;
    public $start_photos = [];
    public $start_latitude;
    public $start_longitude;

    // Propriétés pour terminer une tâche
    public $fin_kilometrage;
    public $fin_carburant;
    public $end_photos = [];
    public $end_latitude;
    public $end_longitude;
    public $description_fin;

    // Propriétés pour demander une tâche
    public $showRequestModal = false;
    public $vehicule_id;
    public $type_tache = 'autre';
    public $description;
    public $start_date;

    public function render()
    {
        $taches = Tache::where('chauffeur_id', Auth::user()->user_id)
                        ->with(['vehicule.marque', 'vehicule.modele', 'photos'])
                        ->latest()
                        ->get();

        // Récupérer seulement les véhicules de l'admin du chauffeur qui ne sont pas déjà pris en charge
        $vehicules = Vehicule::with(['marque', 'modele'])
            ->where('admin_id', Auth::user()->admin_id)
            ->whereDoesntHave('affectations', function ($query) {
                $query->where('status', 'en_cours');
            })
            ->get();

        // Récupérer le véhicule pris en charge actuellement
        $affectation = Affectation::where('chauffeur_id', Auth::user()->user_id)
            ->where('status', 'en_cours')
            ->with('vehicule.marque', 'vehicule.modele')
            ->first();

        return view('livewire.chauffeur.taches', compact('taches', 'vehicules', 'affectation'))->layout('layouts.chauffeur');
    }

    public function showStartModal($id)
    {
        $this->selectedTache = Tache::where('id', $id)
            ->where('chauffeur_id', Auth::user()->user_id)
            ->where('status', 'en_attente')
            ->with(['vehicule.marque', 'vehicule.modele'])
            ->firstOrFail();

        // Vérifier que la tâche est validée
        if (!$this->selectedTache->is_validated) {
            session()->flash('error', "Cette tâche n'est pas encore validée par l'administrateur.");
            return;
        }

        // Vérifier qu'un véhicule est pris en charge
        $affectation = Affectation::where('chauffeur_id', Auth::user()->user_id)
            ->where('status', 'en_cours')
            ->first();

        if (!$affectation || $affectation->vehicule_id !== $this->selectedTache->vehicule_id) {
            session()->flash('error', "Vous devez d'abord prendre en charge le véhicule assigné à cette tâche.");
            return;
        }

        $this->resetStartForm();
        $this->showStartModal = true;
    }

    public function showEndModal($id)
    {
        $this->selectedTache = Tache::where('id', $id)
            ->where('chauffeur_id', Auth::user()->user_id)
            ->where('status', 'en_cours')
            ->with(['vehicule.marque', 'vehicule.modele'])
            ->firstOrFail();

        $this->resetEndForm();
        $this->showEndModal = true;
    }

    public function closeStartModal()
    {
        $this->showStartModal = false;
        $this->selectedTache = null;
        $this->resetStartForm();
    }

    public function closeEndModal()
    {
        $this->showEndModal = false;
        $this->selectedTache = null;
        $this->resetEndForm();
    }

    public function resetStartForm()
    {
        $this->debut_kilometrage = '';
        $this->debut_carburant = '';
        $this->start_photos = [];
        $this->start_latitude = '';
        $this->start_longitude = '';
        $this->resetErrorBag();
    }

    public function resetEndForm()
    {
        $this->fin_kilometrage = '';
        $this->fin_carburant = '';
        $this->end_photos = [];
        $this->end_latitude = '';
        $this->end_longitude = '';
        $this->description_fin = '';
        $this->resetErrorBag();
    }

    public function openRequestModal()
    {
        // Vérifier qu'un véhicule est pris en charge
        $affectation = Affectation::where('chauffeur_id', Auth::user()->user_id)
            ->where('status', 'en_cours')
            ->first();

        if (!$affectation) {
            session()->flash('error', 'Vous devez d\'abord prendre en charge un véhicule avant de demander une tâche.');
            return;
        }

        $this->resetRequestForm();
        $this->showRequestModal = true;
    }

    public function closeRequestModal()
    {
        $this->showRequestModal = false;
        $this->resetRequestForm();
    }


    public function resetRequestForm()
    {
        // Récupérer le véhicule pris en charge
        $affectation = Affectation::where('chauffeur_id', Auth::user()->user_id)
            ->where('status', 'en_cours')
            ->with('vehicule')
            ->first();

        $this->vehicule_id = $affectation ? $affectation->vehicule_id : '';
        $this->type_tache = 'autre';
        $this->description = '';
        $this->start_date = now()->format('Y-m-d\TH:i');
        $this->resetErrorBag();
    }

    public function requestTache()
    {
        $this->validate([
            'vehicule_id' => 'required|exists:vehicules,id',
            'type_tache' => 'required|in:maintenance,livraison,inspection,autre',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date|after:now',
        ], [
            'vehicule_id.required' => 'Le véhicule est obligatoire.',
            'vehicule_id.exists' => 'Le véhicule sélectionné n\'existe pas.',
            'type_tache.required' => 'Le type de tâche est obligatoire.',
            'type_tache.in' => 'Le type de tâche sélectionné n\'est pas valide.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'start_date.required' => 'La date de début est obligatoire.',
            'start_date.date' => 'La date de début doit être une date valide.',
            'start_date.after' => 'La date de début doit être dans le futur.',
        ]);

        // Vérifier qu'il n'y a pas déjà une tâche en cours pour ce chauffeur
        $existingTache = Tache::where('chauffeur_id', Auth::user()->user_id)
            ->whereIn('status', ['en_cours', 'en_attente'])
            ->first();

        if ($existingTache) {
            session()->flash('error', 'Vous avez déjà une tâche en cours ou en attente.');
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

        // Créer la tâche
        $tache = Tache::create([
            'chauffeur_id' => Auth::user()->user_id,
            'vehicule_id' => $this->vehicule_id,
            'start_date' => $this->start_date,
            'description' => $this->description,
            'type_tache' => $this->type_tache,
            'status' => 'en_attente',
            'is_validated' => false,
        ]);

        // Envoyer une notification à l'admin
        app(TacheNotificationService::class)->notifyTacheCreated($tache);

        session()->flash('success', 'Demande de tâche envoyée. Vous serez notifié quand l\'administrateur l\'aura validée.');
        $this->closeRequestModal();
    }

    public function startTache()
    {
        $this->validate([
            'debut_kilometrage' => 'required|integer|min:0',
            'debut_carburant' => 'required|numeric|min:0|max:100',
            'start_photos' => 'required|array|min:3|max:5',
            'start_photos.*' => 'image|max:2048',
            'start_latitude' => 'required|numeric',
            'start_longitude' => 'required|numeric',
        ], [
            'debut_kilometrage.required' => 'Le kilométrage de début est obligatoire.',
            'debut_kilometrage.integer' => 'Le kilométrage doit être un nombre entier.',
            'debut_kilometrage.min' => 'Le kilométrage ne peut pas être négatif.',
            'debut_carburant.required' => 'Le niveau de carburant de début est obligatoire.',
            'debut_carburant.numeric' => 'Le carburant doit être un nombre.',
            'debut_carburant.min' => 'Le carburant ne peut pas être négatif.',
            'debut_carburant.max' => 'Le carburant ne peut pas dépasser 100%.',
            'start_photos.required' => 'Vous devez prendre au moins 3 photos.',
            'start_photos.min' => 'Vous devez prendre au moins 3 photos.',
            'start_photos.max' => 'Vous ne pouvez pas prendre plus de 5 photos.',
            'start_photos.*.image' => 'Chaque fichier doit être une image.',
            'start_photos.*.max' => 'Chaque photo ne peut pas dépasser 2MB.',
            'start_latitude.required' => 'La géolocalisation est obligatoire.',
            'start_longitude.required' => 'La géolocalisation est obligatoire.',
        ]);

        // Mettre à jour la tâche
        $this->selectedTache->update([
            'status' => 'en_cours',
            'start_date' => Carbon::now(),
            'start_latitude' => $this->start_latitude,
            'start_longitude' => $this->start_longitude,
            'debut_kilometrage' => $this->debut_kilometrage,
            'debut_carburant' => $this->debut_carburant,
        ]);

        // Sauvegarder les photos
        $this->savePhotos($this->start_photos, 'start');

        session()->flash('success', 'Tâche démarrée avec succès !');
        $this->closeStartModal();
    }

    public function endTache()
    {
        Log::info('endTache method called');

        if (!$this->selectedTache) {
            session()->flash('error', 'Aucune tâche sélectionnée.');
            return;
        }

        $this->validate([
            'fin_kilometrage' => 'required|integer|min:0',
            'fin_carburant' => 'required|numeric|min:0|max:100',
            'end_photos' => 'required|array|min:1|max:5',
            'end_photos.*' => 'image|max:2048',
            'end_latitude' => 'required|numeric',
            'end_longitude' => 'required|numeric',
            'description_fin' => 'nullable|string|max:1000',
        ], [
            'fin_kilometrage.required' => 'Le kilométrage de fin est obligatoire.',
            'fin_kilometrage.integer' => 'Le kilométrage doit être un nombre entier.',
            'fin_kilometrage.min' => 'Le kilométrage ne peut pas être négatif.',
            'fin_carburant.required' => 'Le niveau de carburant de fin est obligatoire.',
            'fin_carburant.numeric' => 'Le carburant doit être un nombre.',
            'fin_carburant.min' => 'Le carburant ne peut pas être négatif.',
            'fin_carburant.max' => 'Le carburant ne peut pas dépasser 100%.',
            'end_photos.required' => 'Vous devez prendre au moins 1 photo.',
            'end_photos.min' => 'Vous devez prendre au moins 1 photo.',
            'end_photos.max' => 'Vous ne pouvez pas prendre plus de 5 photos.',
            'end_photos.*.image' => 'Chaque fichier doit être une image.',
            'end_photos.*.max' => 'Chaque photo ne peut pas dépasser 2MB.',
            'end_latitude.required' => 'La géolocalisation est obligatoire.',
            'end_longitude.required' => 'La géolocalisation est obligatoire.',
            'description_fin.max' => 'La description ne peut pas dépasser 1000 caractères.',
        ]);

        try {
            // Mettre à jour la tâche
            $this->selectedTache->update([
                'status' => 'terminée',
                'end_date' => Carbon::now(),
                'end_latitude' => $this->end_latitude,
                'end_longitude' => $this->end_longitude,
                'fin_kilometrage' => $this->fin_kilometrage,
                'fin_carburant' => $this->fin_carburant,
                'description' => $this->description_fin,
            ]);

            // Sauvegarder les photos
            $this->savePhotos($this->end_photos, 'end');

            // Envoyer notification à l'admin
            app(TacheNotificationService::class)->notifyTacheCompleted($this->selectedTache);

            session()->flash('success', 'Tâche terminée avec succès !');
            $this->closeEndModal();

            Log::info('endTache completed successfully');
        } catch (\Exception $e) {
            Log::error('endTache error: ' . $e->getMessage());
            session()->flash('error', 'Erreur lors de la finalisation de la tâche: ' . $e->getMessage());
        }
    }

    private function savePhotos($photos, $type)
    {
        $photoTypes = [
            'start' => ['debut_kilometrage', 'debut_carburant', 'plaque'],
            'end' => ['fin_kilometrage', 'fin_carburant', 'plaque']
        ];

        foreach ($photos as $index => $photo) {
            $filename = 'tache_' . $this->selectedTache->id . '_' . $type . '_' . time() . '_' . $index . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('photos/taches', $filename, 'public');

            Photo::create([
                'tache_id' => $this->selectedTache->id,
                'type' => $photoTypes[$type][$index] ?? 'autre',
                'path' => $path,
                'description' => 'Photo ' . $type . ' - ' . ($photoTypes[$type][$index] ?? 'autre'),
            ]);
        }
    }

}

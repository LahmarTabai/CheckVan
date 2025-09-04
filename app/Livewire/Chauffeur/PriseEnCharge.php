<?php

namespace App\Livewire\Chauffeur;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Affectation;
use App\Models\Vehicule;
use Illuminate\Support\Facades\Storage;

class PriseEnCharge extends Component
{
    use WithFileUploads;

    public $vehicule_id;
    public $photo_avant;
    public $photo_apres;
    public $message;

    public function mount()
    {
        $current = Affectation::where('chauffeur_id', Auth::id())
                    ->where('status', 'en_cours')
                    ->first();

        if ($current) {
            $this->vehicule_id = $current->vehicule_id;
        }
    }

    public function render()
    {
        $vehicules = Vehicule::where('admin_id', Auth::user()->admin_id)->get();
        $affectation = Affectation::where('chauffeur_id', Auth::id())
                            ->where('status', 'en_cours')->first();

        return view('livewire.chauffeur.prise-en-charge', compact('vehicules', 'affectation'));
    }

    public function prendreEnCharge()
    {
        $this->validate([
            'vehicule_id' => 'required|exists:vehicules,id',
            'photo_avant' => 'nullable|image|max:2048',
        ]);

        // Terminer l'ancienne affectation
        Affectation::where('chauffeur_id', Auth::id())
            ->where('status', 'en_cours')
            ->update(['status' => 'terminée']);

        // Enregistrer nouvelle affectation
        $affectation = Affectation::create([
            'chauffeur_id' => Auth::id(),
            'vehicule_id' => $this->vehicule_id,
            'status' => 'en_cours',
        ]);

        if ($this->photo_avant) {
            $path = $this->photo_avant->store('photos', 'public');
            $affectation->photos()->create([
                'type' => 'avant',
                'path' => $path,
            ]);
        }

        $this->message = 'Véhicule pris en charge avec succès.';
        $this->reset(['photo_avant']);
    }

    public function rendreVehicule()
    {
        $affectation = Affectation::where('chauffeur_id', Auth::id())
                        ->where('status', 'en_cours')->first();

        if (!$affectation) return;

        if ($this->photo_apres) {
            $path = $this->photo_apres->store('photos', 'public');
            $affectation->photos()->create([
                'type' => 'après',
                'path' => $path,
            ]);
        }

        $affectation->update(['status' => 'terminée']);
        $this->message = 'Véhicule rendu avec succès.';

        $this->reset(['photo_apres', 'vehicule_id']);
    }
}

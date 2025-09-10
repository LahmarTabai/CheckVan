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
    public $isEdit = false;

    public function render()
    {
        $affectations = Affectation::with(['chauffeur', 'vehicule'])
            ->whereHas('chauffeur', function ($query) {
                $query->where('admin_id', Auth::user()->user_id);
            })
            ->latest()
            ->get();

        $chauffeurs = User::where('role', 'chauffeur')
            ->where('admin_id', Auth::user()->user_id)
            ->get();

        $vehicules = Vehicule::where('admin_id', Auth::user()->user_id)->get();

        return view('livewire.admin.affectations', compact('affectations', 'chauffeurs', 'vehicules'))->layout('layouts.admin');
    }

    public function resetForm()
    {
        $this->chauffeur_id = null;
        $this->vehicule_id = null;
        $this->status = 'en_cours';
        $this->affectation_id = null;
        $this->isEdit = false;
    }

    public function save()
    {
        $this->validate([
            'chauffeur_id' => 'required|exists:users,id',
            'vehicule_id' => 'required|exists:vehicules,id',
        ]);

        $affectation = Affectation::create([
            'chauffeur_id' => $this->chauffeur_id,
            'vehicule_id' => $this->vehicule_id,
            'status' => $this->status,
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
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate([
            'chauffeur_id' => 'required|exists:users,id',
            'vehicule_id' => 'required|exists:vehicules,id',
        ]);

        $aff = Affectation::findOrFail($this->affectation_id);
        $aff->update([
            'chauffeur_id' => $this->chauffeur_id,
            'vehicule_id' => $this->vehicule_id,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Affectation mise à jour');
        $this->resetForm();
    }

    public function delete($id)
    {
        Affectation::findOrFail($id)->delete();
        session()->flash('success', 'Affectation supprimée');
    }
}

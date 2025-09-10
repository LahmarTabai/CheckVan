<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Tache;
use App\Models\User;
use App\Models\Vehicule;
use Livewire\WithPagination;
use App\Services\FcmService;

class Taches extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $chauffeurFilter = '';
    public $vehiculeFilter = '';

    public $tacheId;
    public $chauffeur_id, $vehicule_id, $start_date;
    public $isEdit = false;

    public function render()
    {
        $query = Tache::with(['chauffeur', 'vehicule'])
            ->whereHas('vehicule', function ($q) {
                $q->where('admin_id', auth()->user()->user_id);
            });

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->chauffeurFilter) {
            $query->where('chauffeur_id', $this->chauffeurFilter);
        }

        if ($this->vehiculeFilter) {
            $query->where('vehicule_id', $this->vehiculeFilter);
        }

        return view('livewire.admin.taches', [
            'taches' => $query->latest()->paginate(10),
            'chauffeurs' => User::where('role', 'chauffeur')->where('admin_id', auth()->user()->user_id)->get(),
            'vehicules' => Vehicule::where('admin_id', auth()->user()->user_id)->get(),
        ])->layout('layouts.admin');
    }

    public function resetForm()
    {
        $this->tacheId = null;
        $this->chauffeur_id = '';
        $this->vehicule_id = '';
        $this->start_date = '';
        $this->isEdit = false;
        $this->resetErrorBag();
    }

    public function create()
    {
        $this->validate([
            'chauffeur_id' => 'required|exists:users,id',
            'vehicule_id' => 'required|exists:vehicules,id',
            'start_date' => 'required|date',
        ]);

        Tache::create([
            'chauffeur_id' => $this->chauffeur_id,
            'vehicule_id' => $this->vehicule_id,
            'start_date' => $this->start_date,
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
        $this->isEdit = true;
    }

    public function update()
    {
        $tache = Tache::findOrFail($this->tacheId);

        $this->validate([
            'chauffeur_id' => 'required|exists:users,id',
            'vehicule_id' => 'required|exists:vehicules,id',
            'start_date' => 'required|date',
        ]);

        $tache->update([
            'chauffeur_id' => $this->chauffeur_id,
            'vehicule_id' => $this->vehicule_id,
            'start_date' => $this->start_date,
        ]);

        session()->flash('success', 'Tâche mise à jour.');
        $this->resetForm();
    }

    public function delete($id)
    {
        Tache::findOrFail($id)->delete();
        session()->flash('success', 'Tâche supprimée.');
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
